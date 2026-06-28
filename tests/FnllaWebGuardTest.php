<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP TEST CASE
File: tests\FnllaWebGuardTest.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Validates maintained framework behavior inside the repository-local test harness.
*/

namespace Fnlla\Php\Tests;

use Fnlla\Php\Support\FnllaWebGuard;
use PHPUnit\Framework\TestCase;

final class FnllaWebGuardTest extends TestCase
{
    private array $originalConfig = [];

    private string $statePath;

    protected function setUp(): void
    {
        $config = config("fnlla_web", []);
        $this->originalConfig = is_array($config) ? $config : [];
        $this->statePath = storage_path("framework/fnlla-web-guard-test.json");

        if (is_file($this->statePath)) {
            unlink($this->statePath);
        }
    }

    protected function tearDown(): void
    {
        config_set("fnlla_web", $this->originalConfig);

        if (is_file($this->statePath)) {
            unlink($this->statePath);
        }
    }

    public function testEnforceSkipsSyncWhenLocalRuntimeAlreadyValid(): void
    {
        config_set("fnlla_web", [
            "enforce" => true,
            "auto_sync" => true,
            "check_interval_seconds" => 900,
            "sync_script" => "scripts/does-not-exist.ps1",
            "state_path" => $this->statePath,
            "version_file" => public_path("vendor/fnlla-web/VERSION"),
            "layout_path" => base_path("views/layouts/app.php"),
            "page_view_glob" => base_path("views/pages/*.php"),
            "required_runtime_files" => [
                public_path("vendor/fnlla-web/assets/css/fnlla-web.css"),
                public_path("vendor/fnlla-web/assets/js/fnlla-web.js"),
                public_path("vendor/fnlla-web/assets/icons"),
                public_path("vendor/fnlla-web/VERSION"),
            ],
            "required_layout_markers" => [
                '<div class="wrapper">',
                "<main",
                "<footer",
                'asset("vendor/fnlla-web/assets/css/fnlla-web.css")',
                'asset("vendor/fnlla-web/assets/js/fnlla-web.js")',
            ],
            "required_page_markers" => [
                'class="section',
                'class="container',
            ],
            "scan_paths" => [],
            "forbidden_markers" => [],
        ]);

        FnllaWebGuard::enforce();

        self::assertFileExists($this->statePath);

        $state = json_decode((string) file_get_contents($this->statePath), true);
        $versionContents = (string) file_get_contents(public_path("vendor/fnlla-web/VERSION"));
        $currentVersion = trim((string) strtok($versionContents, "\r\n"));

        self::assertTrue(is_array($state));
        self::assertSame($currentVersion, $state["local_version"] ?? null);
        self::assertTrue(((int) ($state["last_checked_at"] ?? 0)) > 0);
    }
}
