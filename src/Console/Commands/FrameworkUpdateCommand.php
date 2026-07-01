<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP CONSOLE SOURCE
File: src\Console\Commands\FrameworkUpdateCommand.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Checks or applies framework-base updates inside downstream applications
  without blindly overwriting application-owned files.
*/

namespace Fnlla\Php\Console\Commands;

use Fnlla\Php\Console\Command;
use Fnlla\Php\Support\FrameworkLock;
use Fnlla\Php\Support\FrameworkUpdater;
use RuntimeException;

final class FrameworkUpdateCommand extends Command
{
    public function name(): string
    {
        return "framework:update";
    }

    public function description(): string
    {
        return "Check or apply FNLLA PHP framework-base updates from a maintained source repository.";
    }

    public function handle(array $arguments): int
    {
        $options = $this->parseOptions($arguments);

        if ($options["help"] === true) {
            $this->printUsage();

            return 0;
        }

        $projectRoot = rtrim((string) base_path(), "\\/");
        $currentLock = FrameworkLock::load($projectRoot);
        $appName = (string) ($currentLock["framework_base"]["application"]["name"] ?? config("app.name", "FNLLA PHP Project"));

        $report = $options["apply"] === true
            ? FrameworkUpdater::apply($projectRoot, (string) ($options["source"] ?? ""), $appName)
            : FrameworkUpdater::check($projectRoot, (string) ($options["source"] ?? ""), $appName);

        $this->renderReport($report);

        if ($options["apply"] === true) {
            $this->line("");
            $this->line("Applied framework update changes: " . (int) ($report["applied_changes"] ?? 0));
            $this->line("Next: run php fnlla fnlla-web:validate, php scripts/test.php, php scripts/lint.php and php scripts/validate-version-manifest.php.");

            return 0;
        }

        return $report["conflicts"] === [] ? 0 : 1;
    }

    private function parseOptions(array $arguments): array
    {
        $options = [
            "apply" => false,
            "help" => false,
            "source" => null,
        ];

        for ($index = 0, $count = count($arguments); $index < $count; $index++) {
            $argument = trim((string) $arguments[$index]);

            if ($argument === "") {
                continue;
            }

            if ($argument === "--apply") {
                $options["apply"] = true;
                continue;
            }

            if ($argument === "--check") {
                $options["apply"] = false;
                continue;
            }

            if ($argument === "--help" || $argument === "-h") {
                $options["help"] = true;
                continue;
            }

            if (str_starts_with($argument, "--source=")) {
                $options["source"] = substr($argument, strlen("--source="));
                continue;
            }

            if ($argument === "--source") {
                $options["source"] = trim((string) ($arguments[$index + 1] ?? ""));
                $index++;
                continue;
            }

            throw new RuntimeException("Unknown option for framework:update: " . $argument);
        }

        return $options;
    }

    private function printUsage(): void
    {
        $this->line("Usage: php fnlla framework:update --check --source <path-to-fnlla-php>");
        $this->line("   or: php fnlla framework:update --apply --source <path-to-fnlla-php>");
    }

    private function renderReport(array $report): void
    {
        $this->line("Framework update check");
        $this->line("Current framework base: " . $report["current_framework_version"] . " / FNLLA Web " . $report["current_ui_version"]);
        $this->line("Source framework base: " . $report["source_framework_version"] . " / FNLLA Web " . $report["source_ui_version"]);
        $this->line("Managed files tracked: " . $report["tracked_managed_files"] . " (source export: " . $report["source_managed_files"] . ")");
        $this->line("Safe framework changes available: " . count($report["updates"]));
        $this->line("Conflicts: " . count($report["conflicts"]));
        $this->line("Local-only managed changes preserved: " . count($report["local_only_changes"]));

        if ($report["updates"] === [] && $report["conflicts"] === []) {
            $this->line("");
            $this->line(
                $report["local_only_changes"] === []
                    ? "Framework base is already aligned with the provided source export."
                    : "No upstream framework drift was detected. Local managed-file edits stay untouched."
            );
        }

        foreach ($report["updates"] as $path => $update) {
            $this->line("[" . strtoupper((string) $update["action"]) . "] " . $path);
        }

        foreach ($report["conflicts"] as $path => $conflict) {
            $this->error("[CONFLICT] " . $path . " - " . $conflict["reason"]);
        }
    }
}
