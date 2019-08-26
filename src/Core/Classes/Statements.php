<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Osm\Core\Classes;

use Osm\Core\Exceptions\UnexpectedStatements;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor;

class Statements implements NodeVisitor
{
    protected $processor;
    protected $predicate;
    protected $found;
    /**
     * @var Node\Stmt[]
     */
    public $stmts;

    public function __construct($stmts) {
        $this->stmts = $stmts;
    }

    public function each($processor) {
        $this->clear();

        $traverser = new NodeTraverser();
        if (is_callable($processor)) {
            $this->processor = $processor;
            $traverser->addVisitor($this);
        }
        else {
            $traverser->addVisitor($processor);
        }

        $this->stmts = $traverser->traverse($this->stmts);

        return $this;
    }

    public function find($predicate) {
        $this->clear();

        $traverser = new NodeTraverser();
        $this->predicate = $predicate;
        $traverser->addVisitor($this);

        $this->stmts = $traverser->traverse($this->stmts);

        return new static($this->found);
    }

    public function findOne(callable $predicate) {
        $found = $this->find($predicate);

        if (count($found->stmts) != 1) {
            throw new UnexpectedStatements("Single query result expected");
        }

        return $found;
    }

    public function toString() {
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard();

        $result = $prettyPrinter->prettyPrintFile($this->stmts);

        return strpos($result, "<?php\n\n") === 0
            ? substr($result, strlen("<?php\n\n"))
            : $result;
    }

    public function beforeTraverse(array $nodes) {
    }

    public function enterNode(Node $node) {
    }

    public function leaveNode(Node $node) {
        if ($this->predicate) {
            if (call_user_func($this->predicate, $node)) {
                $this->found[] = $node;
            }
        }

        if ($this->processor) {
            return call_user_func($this->processor, $node);
        }
    }

    public function afterTraverse(array $nodes) {
    }

    protected function clear() {
        $this->found = [];
        $this->predicate = null;
        $this->processor = null;
    }
}