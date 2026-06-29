<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP TEST CASE
File: tests\EnvironmentConfigTest.php
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

use PHPUnit\Framework\TestCase;

final class EnvironmentConfigTest extends TestCase
{
    private array $serverBackup = [];
    private mixed $appUrlBackup;
    private mixed $sessionSecureBackup;

    protected function setUp(): void
    {
        $this->serverBackup = $_SERVER;
        $this->appUrlBackup = $_ENV["APP_URL"] ?? null;
        $this->sessionSecureBackup = $_ENV["SESSION_SECURE"] ?? null;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->serverBackup;

        if ($this->appUrlBackup === null) {
            unset($_ENV["APP_URL"], $_SERVER["APP_URL"]);
            putenv("APP_URL");
        } else {
            $_ENV["APP_URL"] = $this->appUrlBackup;
            $_SERVER["APP_URL"] = (string) $this->appUrlBackup;
            putenv("APP_URL=" . (string) $this->appUrlBackup);
        }

        if ($this->sessionSecureBackup === null) {
            unset($_ENV["SESSION_SECURE"], $_SERVER["SESSION_SECURE"]);
            putenv("SESSION_SECURE");
        } else {
            $_ENV["SESSION_SECURE"] = $this->sessionSecureBackup;
            $_SERVER["SESSION_SECURE"] = (string) $this->sessionSecureBackup;
            putenv("SESSION_SECURE=" . (string) $this->sessionSecureBackup);
        }
    }

    public function testSessionConfigDefaultsToNonSecureCookiesOnLocalHttp(): void
    {
        $_ENV["APP_URL"] = "http://127.0.0.1:8080";
        $_SERVER["APP_URL"] = "http://127.0.0.1:8080";
        putenv("APP_URL=http://127.0.0.1:8080");
        unset($_ENV["SESSION_SECURE"], $_SERVER["SESSION_SECURE"]);
        putenv("SESSION_SECURE");

        $config = require base_path("config/session.php");

        self::assertFalse($config["secure"]);
    }

    public function testSessionConfigDefaultsToSecureCookiesOnHttps(): void
    {
        $_ENV["APP_URL"] = "https://fnlla.example.test";
        $_SERVER["APP_URL"] = "https://fnlla.example.test";
        putenv("APP_URL=https://fnlla.example.test");
        unset($_ENV["SESSION_SECURE"], $_SERVER["SESSION_SECURE"]);
        putenv("SESSION_SECURE");

        $config = require base_path("config/session.php");

        self::assertTrue($config["secure"]);
    }
}
