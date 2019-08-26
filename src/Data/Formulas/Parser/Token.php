<?php

namespace Osm\Data\Formulas\Parser;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property int $pos @required @part
 * @property int $type @required @part
 * @property string $text @required @part
 */
class Token extends Object_
{
    // major token classes
    const IDENTIFIER = 101;
    const STRING_ = 102;
    const HEXADECIMAL = 103;
    const BINARY = 104;
    const INT_ = 105;
    const FLOAT_ = 106;
    const EOF = 107;

    // special characters
    const EQ = 201;
    const GT = 202;
    const GT_EQ = 203;
    const DOUBLE_GT = 204;
    const LT = 205;
    const LT_EQ = 206;
    const LT_GT = 207;
    const DOUBLE_LT = 208;
    const NOT_EQ = 209;
    const OPEN_PAR = 210;
    const CLOSE_PAR = 211;
    const COMMA = 212;
    const PIPE = 213;
    const AMPERSAND = 214;
    const PLUS = 215;
    const MINUS = 216;
    const ASTERISK = 217;
    const SLASH = 218;
    const PERCENT = 219;
    const HAT = 220;
    const TILDE = 221;
    const COLON = 222;
    const DOT = 223;
    const QUESTION = 224;
    const DOUBLE_QUESTION = 225;
    const QUESTION_EQ = 226;

    // reserved keywords
    const AS_ = 301;
    const OR_ = 302;
    const XOR_ = 303;
    const AND_ = 304;
    const NOT = 305;
    const IS_ = 306;
    const NULL_ = 307;
    const IN_ = 308;
    const BETWEEN = 309;
    const LIKE = 310;
    const REGEXP = 311;
    const DIV = 312;
    const MOD = 313;
    const TRUE_ = 314;
    const FALSE_ = 315;
    const ASC = 316;
    const DESC = 317;

    public static $reserved_keywords = [
        'as' => self::AS_,
        'or' => self::OR_,
        'xor' => self::XOR_,
        'and' => self::AND_,
        'not' => self::NOT,
        'is' => self::IS_,
        'null' => self::NULL_,
        'in' => self::IN_,
        'between' => self::BETWEEN,
        'like' => self::LIKE,
        'regexp' => self::REGEXP,
        'div' => self::DIV,
        'mod' => self::MOD,
        'true' => self::TRUE_,
        'false' => self::FALSE_,
        'asc' => self::ASC,
        'desc' => self::DESC,
    ];

    /**
     * @param $type
     * @return string
     * @throws NotImplemented
     */
    public static function getTitle($type) {
        switch ($type) {
            case static::IDENTIFIER: return osm_t("identifier");
            case static::STRING_: return osm_t("string");
            case static::HEXADECIMAL: return osm_t("hexadecimal");
            case static::BINARY: return osm_t("binary");
            case static::INT_: return osm_t("int");
            case static::FLOAT_: return osm_t("float");
            case static::EOF: return osm_t("end of formula");

            case static::EQ: return "'='";
            case static::GT: return "'>'";
            case static::GT_EQ: return "'>='";
            case static::DOUBLE_GT: return "'>>'";
            case static::LT: return "'<'";
            case static::LT_EQ: return "'<='";
            case static::LT_GT: return "'<>'";
            case static::DOUBLE_LT: return "'<<'";
            case static::NOT_EQ: return "'!='";
            case static::OPEN_PAR: return "'('";
            case static::CLOSE_PAR: return "')'";
            case static::COMMA: return "','";
            case static::PIPE: return "'|'";
            case static::AMPERSAND: return "'&'";
            case static::PLUS: return "'+'";
            case static::MINUS: return "'-'";
            case static::ASTERISK: return "'*'";
            case static::SLASH: return "'/'";
            case static::PERCENT: return "'%'";
            case static::HAT: return "'^'";
            case static::TILDE: return "'~'";
            case static::COLON: return "':'";
            case static::DOT: return "'.'";
            case static::QUESTION: return "'?'";
            case static::DOUBLE_QUESTION: return "'??'";

            case static::AS_: return "'AS'";
            case static::OR_: return "'OR'";
            case static::XOR_: return "'XOR'";
            case static::AND_: return "'AND'";
            case static::NOT: return "'NOT'";
            case static::IS_: return "'IS'";
            case static::NULL_: return "'NULL'";
            case static::IN_: return "'IN'";
            case static::BETWEEN: return "'BETWEEN'";
            case static::LIKE: return "'LIKE'";
            case static::REGEXP: return "'REGEXP'";
            case static::DIV: return "'DIV'";
            case static::MOD: return "'MOD'";
            case static::TRUE_: return "'TRUE'";
            case static::FALSE_: return "'FALSE'";
            case static::ASC: return "'ASC'";
            case static::DESC: return "'DESC'";

            default:
                throw new NotImplemented();
        }
    }

    /**
     * @deprecated
     * @param $type
     * @return string
     * @throws NotImplemented
     */
    public static function getText($type) {
        switch ($type) {
            case static::EQ: return "=";
            case static::GT: return ">";
            case static::GT_EQ: return ">=";
            case static::DOUBLE_GT: return ">>";
            case static::LT: return "<";
            case static::LT_EQ: return "<=";
            case static::LT_GT: return "<>";
            case static::DOUBLE_LT: return "<<";
            case static::NOT_EQ: return "!=";
            case static::OPEN_PAR: return "(";
            case static::CLOSE_PAR: return ")";
            case static::COMMA: return ",";
            case static::PIPE: return "|";
            case static::AMPERSAND: return "&";
            case static::PLUS: return "+";
            case static::MINUS: return "-";
            case static::ASTERISK: return "*";
            case static::SLASH: return "/";
            case static::PERCENT: return "%";
            case static::HAT: return "^";
            case static::TILDE: return "~";
            case static::COLON: return ":";
            case static::DOT: return ".";
            case static::QUESTION: return "?";
            case static::DOUBLE_QUESTION: return "??";

            case static::AS_: return "AS";
            case static::OR_: return "OR";
            case static::XOR_: return "XOR";
            case static::AND_: return "AND";
            case static::NOT: return "NOT";
            case static::IS_: return "IS";
            case static::NULL_: return "NULL";
            case static::IN_: return "IN";
            case static::BETWEEN: return "BETWEEN";
            case static::LIKE: return "LIKE";
            case static::REGEXP: return "REGEXP";
            case static::DIV: return "DIV";
            case static::MOD: return "MOD";
            case static::TRUE_: return "TRUE";
            case static::FALSE_: return "FALSE";
            case static::ASC: return "ASC";
            case static::DESC: return "DESC";

            default:
                throw new NotImplemented();
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public static function unescapeString($value) {
        static $escapeChars;

        if (!$escapeChars) {
            $escapeChars = [
                '0' => "\0",
                '\'' => "'",
                'b' => chr(8),
                'n' => "\n",
                'r' => "\r",
                't' => "\t",
                'Z' => chr(26),
                '\\' => "\\",
                '%' => "\%",
                '_' => "\_",
            ];
        }

        $result = '';
        $inEscape = false;
        $length = mb_strlen($value);
        for ($i = 1; $i < $length - 1; $i++) {
            $ch = mb_substr($value, $i, 1);
            if ($inEscape) {
                if (isset($escapeChars[$ch])) {
                    $result .= $escapeChars[$ch];
                }
                else {
                    $result .= $ch;
                }
                $inEscape = false;
            }
            else {
                if ($ch == '\\') {
                    $inEscape = true;
                }
                else {
                    $result .= $ch;
                }
            }
        }

        return $result;
    }

}