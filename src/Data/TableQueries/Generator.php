<?php

namespace Osm\Data\TableQueries;

use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Formulas\Formulas;
use Osm\Data\Formulas\Formulas\Formula;
use Osm\Data\Formulas\Parser\Token;
use Osm\Data\Formulas\Types;
use Osm\Data\TableQueries\Functions\Generator as FunctionGenerator;

/**
 * @property FunctionGenerator $function_generator @required @part
 * @property string[] $operators @required
 *
 * @property TableQuery $query @temp
 * @property string $sql @temp
 * @property array $bindings @temp
 */
class Generator extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'function_generator': return FunctionGenerator::new([], null, $this);
            case 'operators': return [
                Token::OR_ => " OR ",
                Token::AND_ => " AND ",
                Token::EQ => " = ",
                Token::GT_EQ => " >= ",
                Token::GT => " > ",
                Token::LT_EQ => " <= ",
                Token::LT => " < ",
                Token::LT_GT => " <> ",
                Token::NOT_EQ => " <> ",
                Token::PLUS => " + ",
                Token::MINUS => " - ",
            ];
        }
        return parent::default($property);
    }

    public function reset() {
        $this->sql = '';
        $this->bindings = [];
        return $this;
    }

    public function generateSelect(TableQuery $query) {
        $this->query = $query;
        $this->sql .= 'SELECT ';

        if ($query->distinct) {
            $this->sql .= 'DISTINCT ';
        }

        $this->generateColumns();

        foreach ($this->query->tables as $table) {
            $this->generateJoin($table);
        }

        $this->generateFilter();
        $this->generateGroupBy();
        $this->generateOrderBy();
        $this->generateLimit();
    }

    protected function generateColumns() {
        $first = true;
        foreach ($this->query->columns as $column) {
            if ($first) $first = false; else $this->sql .= ', ';
            $this->handleFormula($column);
        }
    }

    protected function generateJoin(Table $table) {
        switch ($table->join) {
            case Table::JOIN_FROM:
                $this->sql .= "\nFROM {$this->query->db->wrapTable($table->table)}";
                if (!$table->no_alias) {
                    $this->sql .= " AS {$this->query->db->wrap($table->alias)}";
                }
                break;

            case Table::JOIN_INNER:
                $this->sql .= "\nINNER JOIN {$this->query->db->wrapTable($table->table)}";
                if (!$table->no_alias) {
                    $this->sql .= " AS {$this->query->db->wrap($table->alias)}";
                }
                $this->sql .= "\n    ON ";
                $this->handleFormula($table->on);
                break;

            case Table::JOIN_LEFT:
                $this->sql .= "\nLEFT OUTER JOIN {$this->query->db->wrapTable($table->table)}";
                if (!$table->no_alias) {
                    $this->sql .= " AS {$this->query->db->wrap($table->alias)}";
                }
                $this->sql .= "\n    ON ";
                $this->handleFormula($table->on);
                break;

            case Table::JOIN_VIRTUAL:
                // do nothing. Virtual joins are only used in resolution process
                break;

            default:
                throw new NotSupported(osm_t("Table join ':type' not supported", ['type' => $table->join]));
        }
    }

    protected function generateFilter() {
        if ($this->query->filter) {
            $this->sql .= "\nWHERE ";
            $this->handleFormula($this->query->filter);
        }
    }

    protected function generateGroupBy() {
        $first = true;
        foreach ($this->query->groups as $group) {
            if ($first) {
                $this->sql .= "\nGROUP BY ";
                $first = false;
            } else {
                $this->sql .= ', ';
            }
            $this->handleFormula($group);
        }
    }

    protected function generateOrderBy() {
        $first = true;
        foreach ($this->query->sorts as $sort) {
            if ($first) {
                $this->sql .= "\nORDER BY ";
                $first = false;
            } else {
                $this->sql .= ', ';
            }
            $this->handleFormula($sort);
        }
    }

    protected function generateLimit() {
        if ($this->query->limit) {
            if ($this->query->offset) {
                $this->sql .= "\nLIMIT {$this->query->offset}, {$this->query->limit}";
            }
            else {
                $this->sql .= "\nLIMIT {$this->query->limit}";
            }
        }
        else {
            if ($this->query->offset) {
                $limit = PHP_INT_MAX;
                $this->sql .= "\nLIMIT {$this->query->offset}, {$limit}";
            }
        }
    }

    public function generateInsert(TableQuery $query, $table, $values, $onDuplicateKey) {
        $this->query = $query;
        $columns = array_keys($values);
        $this->generateInsertColumns($table, $columns, $onDuplicateKey);

        $this->sql .= ")\nVALUES (";

        $first = true;
        foreach ($values as $value) {
            if ($first) $first = false; else $this->sql .= ', ';
            $this->sql .= "?";
            $this->bindings[] = $value;
        }

        $this->sql .= ")";

        $this->generateOnDuplicateKeyUpdate($columns, $onDuplicateKey);
    }

    protected function generateInsertColumns($table, $columns, $onDuplicateKey) {
        $ignore = $onDuplicateKey == OnDuplicateKey::IGNORE ? "IGNORE " : '';
        $this->sql .= "INSERT {$ignore}INTO {$this->query->db->wrapTable($table)} (";

        $first = true;
        foreach ($columns as $column) {
            if ($first) $first = false; else $this->sql .= ', ';
            $this->sql .= $this->query->db->wrap($column);
        }
    }

    protected function generateOnDuplicateKeyUpdate($columns, $onDuplicateKey) {
        if ($onDuplicateKey != OnDuplicateKey::UPDATE) {
            return;
        }

        $this->sql .= "\nON DUPLICATE KEY UPDATE\n    ";
        $first = true;
        foreach ($columns as $column) {
            if ($first) $first = false; else $this->sql .= ', ';
            $this->sql .= "{$this->query->db->wrap($column)} = VALUES({$this->query->db->wrap($column)})";
        }
    }

    public function generateInto(TableQuery $query, $table, $onDuplicateKey = OnDuplicateKey::ERROR) {
        $this->query = $query;
        $columns = array_keys($query->columns);
        $this->generateInsertColumns($table, $columns, $onDuplicateKey);
        $this->sql .= ")\n";

        $this->generateSelect($query);
        $this->generateOnDuplicateKeyUpdate($columns, $onDuplicateKey);
    }

    public function generateUpdate(TableQuery $query, $mainAlias, $values) {
        $this->query = $query;
        $this->sql .= "UPDATE {$this->query->db->wrapTable($this->query->tables[$mainAlias]->table)}";

        foreach ($this->query->tables as $alias => $table) {
            if ($alias != $mainAlias) {
                $this->generateJoin($table);
            }
        }

        $this->sql .= "\nSET\n    ";

        $first = true;
        foreach ($values as $column => $value) {
            if ($first) $first = false; else $this->sql .= ', ';
            $this->sql .= "{$this->query->db->wrap($column)} = ?";
            $this->bindings[] = $value;
        }

        $this->generateFilter();
    }

    public function generateDelete(TableQuery $query) {
        $this->query = $query;
        $this->sql .= "DELETE {$this->query->db->wrapTable($this->query->tables['this']->table)}";
        $this->sql .= " FROM {$this->query->db->wrapTable($this->query->tables['this']->table)}";

        foreach ($this->query->tables as $alias => $table) {
            if ($alias != 'this') {
                $this->generateJoin($table);
            }
        }

        $this->generateFilter();
    }

    /**
     * @see \Osm\Data\Formulas\Formulas\Formula::$type @handler
     * @param Formula $formula
     */
    public function handleFormula(Formula &$formula) {
        $not = '';
        $first = true;
        switch ($formula->type) {
            case Formula::SORT_EXPR:
                /* @var Formulas\SortExpr $formula */
                $this->handleFormula($formula->expr);

                if (!$formula->ascending) {
                    $this->sql .= " DESC";
                }
                break;

            case Formula::SELECT_EXPR:
                /* @var Formulas\SelectExpr $formula */
                $this->handleFormula($formula->expr);
                $this->sql .= " AS {$this->query->db->wrap($formula->alias)}";
                break;

            case Formula::IDENTIFIER:
                /* @var Formulas\Identifier $formula */
                if ($this->query->tables[$formula->table]->no_alias) {
                    $this->sql .= "{$this->query->db->wrap($this->query->tables[$formula->table]->table)}.";
                }
                else {
                    $this->sql .= "{$this->query->db->wrap($formula->table)}.";
                }
                $this->sql .= "{$this->query->db->wrap($formula->column)}";
                break;

            /** @noinspection PhpMissingBreakStatementInspection */
            case Formula::ADD:
                /* @var Formulas\Operator $formula */
                if ($formula->data_type == Types::STRING_) {
                    $this->sql .= 'CONCAT(';

                    foreach ($formula->operands as $operand) {
                        if ($operand instanceof Formulas\Literal && $operand->value == '') {
                            continue;
                        }

                        if ($first) $first = false; else $this->sql .= ", ";
                        $this->handleFormula($operand);
                    }

                    if ($first) {
                        $this->sql .= "''";
                    }

                    $this->sql .= ')';
                    break;
                }
            case Formula::LOGICAL_OR:
            case Formula::LOGICAL_AND:
            case Formula::EQUAL:
            case Formula::EQUAL_OR_GREATER:
            case Formula::GREATER:
            case Formula::EQUAL_OR_LESS:
            case Formula::LESS:
            case Formula::NOT_EQUAL:
                /* @var Formulas\Operator $formula */
                $this->sql .= "(";
                $this->handleFormula($formula->operands[0]);
                $this->sql .= ")";

                foreach ($formula->operators as $i => $operator) {
                    $this->sql .= $this->operators[$operator];
                    $this->sql .= "(";
                    $this->handleFormula($formula->operands[$i + 1]);
                    $this->sql .= ")";
                }
                break;

            case Formula::COALESCE:
                /* @var Formulas\Operator $formula */
                $this->sql .= 'COALESCE(';

                foreach ($formula->operands as $operand) {
                    if ($first) $first = false; else $this->sql .= ", ";
                    $this->handleFormula($operand);
                }

                $this->sql .= ')';
                break;

            case Formula::EQUAL_OR_NULL:
                /* @var Formulas\Operator $formula */
                $second = $formula->operands[1];
                if (!($second instanceof Formulas\Parameter) || $second->parameter !== null) {
                    $this->handleFormula($formula->operands[0]);
                    $this->sql .= " = ";
                    $this->handleFormula($formula->operands[1]);
                    $this->sql .= " OR ";
                }
                $this->handleFormula($formula->operands[0]);
                $this->sql .= " IS NULL";
                break;


            case Formula::IS_NULL:
                /* @var Formulas\Unary $formula */
                $this->handleFormula($formula->operand);
                $this->sql .= " IS NULL";
                break;

            case Formula::IS_NOT_NULL:
                /* @var Formulas\Unary $formula */
                $this->handleFormula($formula->operand);
                $this->sql .= " IS NOT NULL";
                break;

            /** @noinspection PhpMissingBreakStatementInspection */
            case Formula::NOT_IN:
                $not = "NOT ";
            case Formula::IN_:
                /* @var Formulas\In_ $formula */
                $this->handleFormula($formula->value);
                $this->sql .= " {$not}IN (";

                foreach ($formula->items as $item) {
                    if ($first) $first = false; else $this->sql .= ", ";
                    $this->handleFormula($item);
                }

                $this->sql .= ")";
                break;

            case Formula::PARAMETER:
                /* @var Formulas\Parameter $formula */
                $this->sql .= '?';
                $this->bindings[] = $formula->parameter;
                break;

            case Formula::LITERAL:
                /* @var Formulas\Literal $formula */
                $this->sql .= '?';
                $this->handleLiteral($formula);
                break;

            case Formula::CALL:
                /* @var Formulas\Call $formula */
                $this->function_generator->generate($formula);
                break;

            case Formula::TERNARY:
                /* @var Formulas\Ternary $formula */
                $this->sql .= 'IF(';
                $this->handleFormula($formula->condition);
                $this->sql .= ', ';
                $this->handleFormula($formula->then);
                $this->sql .= ', ';
                $this->handleFormula($formula->else_);
                $this->sql .= ')';
                break;

            case Formula::CAST:
                /* @var Formulas\Cast $formula */
                if ($formula->data_type == Types::ANY ||
                    $formula->data_type == $formula->expr->data_type)
                {
                    $this->handleFormula($formula->expr);
                }
                else {
                    switch ($formula->data_type) {
                        case Types::STRING_:
                            $this->sql .= 'COALESCE(';
                            $this->handleFormula($formula->expr);
                            $this->sql .= ')';
                            break;
                        default:
                            throw new NotSupported(osm_t("Formula type ':type' not supported", ['type' => $formula->type]));
                    }
                }
                break;
            case Formula::LOGICAL_XOR:
            case Formula::BIT_OR:
            case Formula::BIT_AND:
            case Formula::BIT_SHIFT:
            case Formula::MULTIPLY:
            case Formula::BIT_XOR:
                /* @var Formulas\Operator $formula */

            case Formula::NOT_BETWEEN:
            case Formula::BETWEEN:
                /* @var Formulas\Between $formula */

            case Formula::NOT_LIKE:
            case Formula::LIKE:
            case Formula::NOT_REGEXP:
            case Formula::REGEXP:
                /* @var Formulas\Pattern $formula */

            case Formula::POSITIVE:
            case Formula::NEGATIVE:
            case Formula::BIT_INVERT:
            case Formula::LOGICAL_NOT:
                /* @var Formulas\Unary $formula */

            default:
                throw new NotSupported(osm_t("Formula type ':type' not supported", ['type' => $formula->type]));
        }
    }

    /**
     * @see \Osm\Data\Formulas\Formulas\Literal::$token @handler
     * @param Formulas\Literal $formula
     */
    protected function handleLiteral(Formulas\Literal $formula) {
        switch ($formula->token) {
            case Token::STRING_: $this->bindings[] = Token::unescapeString($formula->value); break;
            case Token::INT_: $this->bindings[] = intval($formula->value); break;
            case Token::FLOAT_: $this->bindings[] = floatval($formula->value); break;
            case Token::HEXADECIMAL: $this->bindings[] = hexdec(mb_substr($formula->value, 2)); break;
            case Token::BINARY: $this->bindings[] = bindec(mb_substr($formula->value, 2)); break;
            case Token::TRUE_: $this->bindings[] = true; break;
            case Token::FALSE_: $this->bindings[] = false; break;
            case Token::NULL_: $this->bindings[] = null; break;
            default:
                throw new NotSupported(osm_t("Literal token type ':type' not supported",
                    ['type' => Token::getTitle(Token::STRING_)]));
        }
    }
}
