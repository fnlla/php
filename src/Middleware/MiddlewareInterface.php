<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP MIDDLEWARE SOURCE
File: src\Middleware\MiddlewareInterface.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Implements middleware behavior for request hardening, policy and response shaping.
*/

namespace Fnlla\Php\Middleware;

use Fnlla\Php\Http\Request;

interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed;
}
