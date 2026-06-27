@echo off
REM ============================================================================
REM FNLLA PHP REPOSITORY LAUNCHER
REM File: test-fnlla-php.cmd
REM Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
REM FNLLA PHP is produced, maintained and distributed by TechAyo LTD.
REM Purpose: Provides a Windows launcher for a maintained framework or maintainer workflow command.
REM ============================================================================
setlocal
php "%~dp0scripts\test.php" %*
