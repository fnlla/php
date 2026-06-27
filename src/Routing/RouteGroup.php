<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP ROUTING SOURCE
File: src\Routing\RouteGroup.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Implements maintained route registration, matching and URL generation behavior.
*/

namespace Fnlla\Php\Routing;

final class RouteGroup
{
    public function __construct(
        public readonly string $prefix = "",
        public readonly string $namePrefix = "",
        public readonly array $middleware = []
    ) {
    }
}
