<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP CONTROLLER SOURCE
File: src\Controllers\FrameworkUpdateController.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Serves the optional framework maintenance page used by downstream
  applications to run local framework-update checks and safe apply flows from
  the browser.
*/

namespace Fnlla\Php\Controllers;

use Fnlla\Php\Http\Request;
use Fnlla\Php\Http\Response;
use Fnlla\Php\Support\FrameworkLock;
use Fnlla\Php\Support\FrameworkUpdater;
use RuntimeException;

final class FrameworkUpdateController extends Controller
{
    public function show(Request $request): Response
    {
        $pageState = $this->pageState($request);
        $lock = $this->safeLoadLock();
        $report = flash("framework_update_report");

        return $this->view("maintenance/framework-update", [
            "pageTitle" => "Framework updates",
            "pageTitleSection" => "Maintenance",
            "frameworkUpdatePageState" => $pageState,
            "frameworkUpdateLock" => $lock,
            "frameworkUpdateReport" => is_array($report) ? $report : null,
            "frameworkUpdateSourcePath" => (string) old("source_path", (string) config("framework_update.source_path", "")),
        ]);
    }

    public function run(Request $request): Response
    {
        $pageState = $this->pageState($request);

        if ($pageState["can_run"] !== true) {
            flash_set("status", [
                "variant" => "warning",
                "title" => "Framework updates are currently locked",
                "text" => $pageState["message"],
                "toast" => false,
            ]);
            regenerate_csrf_token();

            return $this->redirect(route("maintenance.framework_update"));
        }

        $sourcePath = trim((string) $request->input("source_path", (string) config("framework_update.source_path", "")));
        $mode = trim((string) $request->input("mode", "check"));
        flash_set("old", [
            "source_path" => $sourcePath,
        ]);

        if ($sourcePath === "") {
            flash_set("status", [
                "variant" => "warning",
                "title" => "Source path still needed",
                "text" => "Set the maintained fnlla/php source path before running a framework update check.",
                "toast" => false,
            ]);
            regenerate_csrf_token();

            return $this->redirect(route("maintenance.framework_update"));
        }

        if (!in_array($mode, ["check", "apply"], true)) {
            flash_set("status", [
                "variant" => "warning",
                "title" => "Unknown framework update action",
                "text" => "Choose a supported action before rerunning the framework update workflow.",
                "toast" => false,
            ]);
            regenerate_csrf_token();

            return $this->redirect(route("maintenance.framework_update"));
        }

        if ($mode === "apply" && $pageState["can_apply"] !== true) {
            flash_set("status", [
                "variant" => "warning",
                "title" => "Safe apply is disabled here",
                "text" => "This application currently allows browser-based checks only. Enable apply explicitly in the local environment when you are ready.",
                "toast" => false,
            ]);
            regenerate_csrf_token();

            return $this->redirect(route("maintenance.framework_update"));
        }

        try {
            $report = $mode === "apply"
                ? FrameworkUpdater::apply(base_path(), $sourcePath, (string) config("app.name"))
                : FrameworkUpdater::check(base_path(), $sourcePath, (string) config("app.name"));

            flash_set("framework_update_report", array_merge($report, [
                "mode" => $mode,
                "executed_at_utc" => gmdate(DATE_ATOM),
                "source_path" => $sourcePath,
            ]));
            flash_set("status", [
                "variant" => ($mode === "apply" && (int) ($report["applied_changes"] ?? 0) > 0) || ($mode === "check" && $report["conflicts"] === []) ? "success" : "info",
                "title" => $mode === "apply" ? "Safe framework update finished" : "Framework update check finished",
                "text" => $mode === "apply"
                    ? "Review the structured report below, then rerun validation before treating the application as ready."
                    : "The application compared its framework base against the maintained source export and prepared a structured drift report.",
                "toast" => false,
            ]);
        } catch (RuntimeException $exception) {
            flash_set("status", [
                "variant" => "danger",
                "title" => "Framework update could not run",
                "text" => $exception->getMessage(),
                "toast" => false,
            ]);
        }

        regenerate_csrf_token();

        return $this->redirect(route("maintenance.framework_update"));
    }

    private function pageState(Request $request): array
    {
        $enabled = (bool) config("framework_update.ui_enabled", false);
        $localOnly = (bool) config("framework_update.ui_local_only", true);
        $applyEnabled = (bool) config("framework_update.ui_apply_enabled", false);
        $isLocalRequest = in_array($request->ip(), ["127.0.0.1", "::1"], true);
        $isLocalContext = !$localOnly || $isLocalRequest;
        $canRun = $enabled && $isLocalContext;

        $message = match (true) {
            $enabled !== true => "Enable FRAMEWORK_UPDATE_UI_ENABLED in the local environment to run browser-based framework checks.",
            $isLocalContext !== true => "This page is configured for local-only usage. Open it from the same machine as the project runtime.",
            default => "Framework update checks are available from this page.",
        };

        return [
            "enabled" => $enabled,
            "local_only" => $localOnly,
            "apply_enabled" => $applyEnabled,
            "is_local_request" => $isLocalRequest,
            "can_run" => $canRun,
            "can_apply" => $canRun && $applyEnabled,
            "message" => $message,
        ];
    }

    private function safeLoadLock(): ?array
    {
        try {
            return FrameworkLock::load(base_path());
        } catch (RuntimeException) {
            return null;
        }
    }
}
