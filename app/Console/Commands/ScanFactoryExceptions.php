<?php

namespace App\Console\Commands;

use App\Services\Issues\ExceptionScanner;
use App\Services\Issues\IssueEngine;
use Illuminate\Console\Command;

class ScanFactoryExceptions extends Command
{
    protected $signature = 'factory:scan-exceptions';

    protected $description = 'Raise issue-engine items for late TNA, silent inquiries, LC and licence dates, late yarn and idle machines';

    public function handle(ExceptionScanner $scanner, IssueEngine $issues): int
    {
        $raised = $scanner->scan();
        $escalated = $issues->escalateDue();
        $this->info("Raised {$raised} exception(s), escalated {$escalated}.");

        return self::SUCCESS;
    }
}
