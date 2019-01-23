<?php

namespace Manadev\Framework\Db\Logging;

use Monolog\Formatter\LineFormatter;

class QueryLineFormatter extends LineFormatter
{
    const DEFAULT_CALLER_FORMAT = "\n%context.file% (%context.line%)\n";
    const DEFAULT_QUERY_FORMAT = "    # %context.time% ms, %context.affected%\n    %message%\n";

    /**
     * @var string
     */
    protected $callerFormat;
    /**
     * @var null|string
     */
    protected $queryFormat;

    protected $previousCaller;

    /**
     * @param string $callerFormat               The format of the caller
     * @param string $queryFormat                The format of the query
     * @param string $dateFormat                 The format of the timestamp: one supported by DateTime::format
     * @param bool   $allowInlineLineBreaks      Whether to allow inline line breaks in log entries
     * @param bool   $ignoreEmptyContextAndExtra
     */
    public function __construct($callerFormat = null, $queryFormat = null, $dateFormat = null, $allowInlineLineBreaks = false, $ignoreEmptyContextAndExtra = false)
    {
        $this->callerFormat = $callerFormat ?: static::DEFAULT_CALLER_FORMAT;
        $this->queryFormat = $queryFormat ?: static::DEFAULT_QUERY_FORMAT;

        parent::__construct(null, $dateFormat, $allowInlineLineBreaks, $ignoreEmptyContextAndExtra);
    }

    public function format(array $record) {
        if (!isset($record['context']['file'])) {
            return parent::format($record);
        }

        if (!isset($record['context']['line'])) {
            return parent::format($record);
        }

        $caller = "{$record['context']['file']}_{$record['context']['line']}";
        if ($this->previousCaller == $caller) {
            $this->format = $this->queryFormat;
            $result = parent::format($record);
            $this->format = static::SIMPLE_FORMAT;

            return $result;
        }

        $this->previousCaller = $caller;

        $this->format = $this->callerFormat;
        $result = parent::format($record);
        $this->format = $this->queryFormat;
        $result .= parent::format($record);
        $this->format = static::SIMPLE_FORMAT;

        return $result;
    }

}