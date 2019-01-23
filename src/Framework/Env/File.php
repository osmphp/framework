<?php

namespace Manadev\Framework\Env;

use Dotenv\Loader;

class File extends Loader
{
    public function all() {
        $this->ensureFileIsReadable();

        $filePath = $this->filePath;
        $lines = $this->readLinesFromFile($filePath);
        foreach ($lines as $line) {
            if ($this->isComment($line)) {
                continue;
            }

            if (!$this->looksLikeSetter($line)) {
                continue;
            }

            list($name) = $this->splitCompoundStringIntoParts($line, '');
            yield $name => $this->getEnvironmentVariable($name);
        }
    }

    public function get($name) {
        return $this->getEnvironmentVariable($name);
    }

    public function set($name, $value) {
        $this->ensureFileIsReadable();

        $filePath = $this->filePath;
        $lines = $this->readLinesFromFile($filePath);
        $found = false;
        foreach ($lines as &$line) {
            if ($this->isComment($line)) {
                continue;
            }

            if (!$this->looksLikeSetter($line)) {
                continue;
            }

            list($lineName) = $this->splitCompoundStringIntoParts($line, '');
            if ($lineName != $name) {
                continue;
            }

            $line = "$name=$value";
            $found = true;
            break;
        }

        if (!$found) {
            $lines[] = "$name=$value";
        }

        file_put_contents($this->filePath, implode("\n", $lines));
        putenv("$name=$value");
    }

    protected function readLinesFromFile($filePath)
    {
        // Read file into an array of lines with auto-detected line endings
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($filePath, FILE_IGNORE_NEW_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        return $lines;
    }
}