<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP CONSOLE SOURCE
File: src\Console\Commands\FnllaWebSyncCommand.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Implements the maintained CLI surface and scheduler-oriented console behavior.
*/

namespace Fnlla\Php\Console\Commands;

use Fnlla\Php\Console\Command;
use Fnlla\Php\Support\FnllaWebGuard;

final class FnllaWebSyncCommand extends Command
{
    public function name(): string
    {
        return "fnlla-web:sync";
    }

    public function description(): string
    {
        return "Sync the vendored FNLLA Web runtime from GitHub.";
    }

    public function handle(array $arguments): int
    {
        FnllaWebGuard::syncNow();
        $this->line("FNLLA Web sync completed.");

        return 0;
    }
}
