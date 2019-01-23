<?php

namespace Manadev\Framework\Testing;

use Manadev\Core\App;
use Manadev\Framework\Processes\Process;
use PHPUnit\Framework\Warning;

abstract class DocumentationTestCase extends TestCase
{
    public $suite = 'doc_tests';

    protected function assertDocumentationIsUpToDate($url, $since, $sources) {
        $filename = __FILE__;
        $package = $this->getThisPackage($filename);

        $message = '';
        $commands = '';

        $commits = Process::cd($package->path, function() use ($since, $sources) {
            return Process::runBuffered("git log \"--format= %h %ad %an: %s\" --date=short " .
                "{$this->getRange($since)} -- {$this->getSources($sources)}");
        });

        if ($commits) {
            $message .= "\nReview the following undocumented commits: \n{$commits}";
            $commands .= " gitk {$this->getRange($since)} -- {$this->getSources($sources)}\n";
        }

        $changes = Process::cd($package->path, function() use ($since, $sources) {
            return Process::runBuffered("git status -s -- {$this->getSources($sources)}");
        });

        if ($changes) {
            $message .= "\nReview the following undocumented file changes: \n{$changes}";
        }

        if ($commands) {
            $commands = "\nUse the following commands to review undocumented changes visually:\n" .
                " cd {$package->path}\n{$commands}";
        }

        if ($message) {
            throw new Warning("Documentation under '{$url}' URL is outdated.\n{$message}{$commands}" .
                "\nAfter updating documentation, set '\$since' parameter to latest commit ID.\n");
        }
    }

    protected function getThisPackage($filename) {
        global $m_app; /* @var App $m_app */

        $this->assertTrue(strpos($filename, $m_app->base_path) === 0,
            "Documentation tests should be within project directory.");
        $filename = substr($filename, strlen($m_app->base_path) + 1);

        foreach ($m_app->packages as $package) {
            if (strpos($filename, $package->path) === 0) {
                return $package;
            }
        }

        $this->assertTrue(false, "Documentation tests should be within component package.");
        return null;
    }

    protected function getRange($since) {
        return $since ? "{$since}.." : '';
    }

    protected function getSources($sources) {
        return implode(' ', $sources);
    }
}