<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP SUPPORT SOURCE
File: src\Support\FrameworkUpdater.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Provides the shared framework-update engine used by both CLI and the optional
  maintenance page.
*/

namespace Fnlla\Php\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

final class FrameworkUpdater
{
    public static function check(string $projectRoot, string $source, ?string $appName = null): array
    {
        [$report, $workspace] = self::prepare($projectRoot, $source, $appName);

        try {
            return $report;
        } finally {
            self::removeDirectory($workspace);
        }
    }

    public static function apply(string $projectRoot, string $source, ?string $appName = null): array
    {
        [$report, $workspace, $exportRoot] = self::prepare($projectRoot, $source, $appName);

        try {
            if ($report["conflicts"] !== []) {
                throw new RuntimeException("Framework updates were not applied because conflicts need manual review first.");
            }

            $appliedChanges = self::applyReport($report, $projectRoot, $exportRoot);
            FrameworkLock::syncFromExport($exportRoot, $projectRoot);
            $report["applied_changes"] = $appliedChanges;

            return $report;
        } finally {
            self::removeDirectory($workspace);
        }
    }

    private static function prepare(string $projectRoot, string $source, ?string $appName): array
    {
        $projectRoot = rtrim($projectRoot, "\\/");
        $currentLock = FrameworkLock::load($projectRoot);
        $resolvedAppName = $appName !== null && trim($appName) !== ""
            ? trim($appName)
            : (string) ($currentLock["framework_base"]["application"]["name"] ?? "FNLLA PHP Project");
        $sourceRoot = self::resolveSourceRoot($source, $projectRoot);
        $workspace = self::createTempWorkspace();

        try {
            $exportRoot = $workspace . DIRECTORY_SEPARATOR . "source-export";
            self::exportSourceProject($sourceRoot, $exportRoot, $resolvedAppName);
            $sourceLock = FrameworkLock::load($exportRoot);
            $report = self::buildReport($currentLock, $sourceLock, $projectRoot);

            return [$report, $workspace, $exportRoot];
        } catch (RuntimeException $exception) {
            self::removeDirectory($workspace);

            throw $exception;
        }
    }

    private static function resolveSourceRoot(string $source, string $projectRoot): string
    {
        $source = trim($source);

        if ($source === "") {
            throw new RuntimeException("framework:update needs a source path pointing at a maintained fnlla/php repository.");
        }

        $resolved = self::isAbsolutePath($source)
            ? self::normalizePath($source)
            : self::normalizePath($projectRoot . DIRECTORY_SEPARATOR . $source);

        if (!is_dir($resolved)) {
            throw new RuntimeException("framework:update source directory does not exist: " . $resolved);
        }

        $launcher = $resolved . DIRECTORY_SEPARATOR . "fnlla";
        $makeProjectCommand = $resolved . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Console" . DIRECTORY_SEPARATOR . "Commands" . DIRECTORY_SEPARATOR . "MakeProjectCommand.php";

        if (!is_file($launcher) || !is_file($makeProjectCommand)) {
            throw new RuntimeException("framework:update source must be a maintained fnlla/php repository: " . $resolved);
        }

        return $resolved;
    }

    private static function createTempWorkspace(): string
    {
        $workspace = rtrim(sys_get_temp_dir(), "\\/") . DIRECTORY_SEPARATOR . "fnlla-php-framework-update-" . bin2hex(random_bytes(8));

        if (!mkdir($workspace, 0777, true) && !is_dir($workspace)) {
            throw new RuntimeException("Unable to create temporary framework update workspace.");
        }

        return $workspace;
    }

    private static function exportSourceProject(string $sourceRoot, string $targetRoot, string $appName): void
    {
        if (!function_exists("exec")) {
            throw new RuntimeException("framework:update requires the PHP exec() function to export a fresh project baseline.");
        }

        $command = self::escapeArgument(PHP_BINARY)
            . " "
            . self::escapeArgument($sourceRoot . DIRECTORY_SEPARATOR . "fnlla")
            . " make:project "
            . self::escapeArgument($targetRoot)
            . " "
            . self::escapeArgument($appName)
            . " 2>&1";

        $lines = [];
        $exitCode = 1;

        exec($command, $lines, $exitCode);

        if ($exitCode !== 0) {
            throw new RuntimeException(
                "Unable to export a fresh project baseline from the provided source repository." . PHP_EOL . implode(PHP_EOL, $lines)
            );
        }
    }

    private static function buildReport(array $currentLock, array $sourceLock, string $projectRoot): array
    {
        $baseManagedFiles = (array) ($currentLock["framework_base"]["managed_files"] ?? []);
        $sourceManagedFiles = (array) ($sourceLock["framework_base"]["managed_files"] ?? []);
        $paths = array_values(array_unique(array_merge(array_keys($baseManagedFiles), array_keys($sourceManagedFiles))));
        sort($paths);

        $updates = [];
        $conflicts = [];
        $localOnlyChanges = [];

        foreach ($paths as $path) {
            $baseHash = $baseManagedFiles[$path] ?? null;
            $sourceHash = $sourceManagedFiles[$path] ?? null;
            $currentHash = self::hashIfFileExists($projectRoot . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $path));

            if ($sourceHash === $baseHash) {
                if ($currentHash !== $baseHash) {
                    $localOnlyChanges[$path] = [
                        "base_hash" => $baseHash,
                        "current_hash" => $currentHash,
                        "reason" => $currentHash === null
                            ? "removed locally while upstream stayed the same"
                            : "modified locally while upstream stayed the same",
                    ];
                }

                continue;
            }

            if ($currentHash === $baseHash) {
                $updates[$path] = [
                    "action" => $baseHash === null ? "add" : ($sourceHash === null ? "remove" : "update"),
                    "base_hash" => $baseHash,
                    "source_hash" => $sourceHash,
                    "current_hash" => $currentHash,
                ];
                continue;
            }

            if ($currentHash === $sourceHash) {
                continue;
            }

            $conflicts[$path] = [
                "base_hash" => $baseHash,
                "source_hash" => $sourceHash,
                "current_hash" => $currentHash,
                "reason" => "framework-managed file changed both locally and upstream",
            ];
        }

        return [
            "current_framework_version" => (string) ($currentLock["framework_base"]["framework"]["version"] ?? "unknown"),
            "source_framework_version" => (string) ($sourceLock["framework_base"]["framework"]["version"] ?? "unknown"),
            "current_ui_version" => (string) ($currentLock["framework_base"]["ui_runtime"]["version"] ?? "unknown"),
            "source_ui_version" => (string) ($sourceLock["framework_base"]["ui_runtime"]["version"] ?? "unknown"),
            "tracked_managed_files" => count($baseManagedFiles),
            "source_managed_files" => count($sourceManagedFiles),
            "updates" => $updates,
            "conflicts" => $conflicts,
            "local_only_changes" => $localOnlyChanges,
        ];
    }

    private static function applyReport(array $report, string $projectRoot, string $sourceExportRoot): int
    {
        $changes = 0;

        foreach ($report["updates"] as $path => $update) {
            $targetPath = $projectRoot . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $path);
            $sourcePath = $sourceExportRoot . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $path);

            if ($update["action"] === "remove") {
                if (is_file($targetPath) && !unlink($targetPath)) {
                    throw new RuntimeException("Unable to remove framework-managed file: " . $targetPath);
                }

                $changes++;
                continue;
            }

            $directory = dirname($targetPath);

            if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new RuntimeException("Unable to create directory for framework update: " . $directory);
            }

            if (!copy($sourcePath, $targetPath)) {
                throw new RuntimeException("Unable to copy framework-managed file during update: " . $path);
            }

            $changes++;
        }

        return $changes;
    }

    private static function hashIfFileExists(string $path): ?string
    {
        if (!is_file($path)) {
            return null;
        }

        $hash = hash_file("sha256", $path);

        if (!is_string($hash) || $hash === "") {
            throw new RuntimeException("Unable to hash project file while checking framework drift: " . $path);
        }

        return $hash;
    }

    private static function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        rmdir($path);
    }

    private static function isAbsolutePath(string $path): bool
    {
        return preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) === 1
            || str_starts_with($path, "\\\\")
            || str_starts_with($path, "/");
    }

    private static function normalizePath(string $path): string
    {
        $path = str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $path);
        $segments = [];
        $prefix = "";

        if (preg_match('/^[A-Za-z]:/', $path) === 1) {
            $prefix = strtoupper(substr($path, 0, 2));
            $path = substr($path, 2);
        } elseif (str_starts_with($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR)) {
            $prefix = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
            $path = substr($path, 2);
        }

        $isAbsolute = str_starts_with($path, DIRECTORY_SEPARATOR);
        $parts = preg_split('/[\\\\\\/]+/', $path) ?: [];

        foreach ($parts as $part) {
            if ($part === "" || $part === ".") {
                continue;
            }

            if ($part === "..") {
                if ($segments !== [] && end($segments) !== "..") {
                    array_pop($segments);
                } elseif (!$isAbsolute) {
                    $segments[] = $part;
                }

                continue;
            }

            $segments[] = $part;
        }

        $normalized = implode(DIRECTORY_SEPARATOR, $segments);

        if ($prefix !== "") {
            return $prefix . DIRECTORY_SEPARATOR . $normalized;
        }

        return ($isAbsolute ? DIRECTORY_SEPARATOR : "") . $normalized;
    }

    private static function escapeArgument(string $value): string
    {
        return '"' . str_replace('"', '\"', $value) . '"';
    }
}
