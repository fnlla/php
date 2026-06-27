<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP AUTHENTICATION SOURCE
File: src\Auth\UserProviderInterface.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Implements authentication, authorization or access-control primitives for the framework.
*/

namespace Fnlla\Php\Auth;

interface UserProviderInterface
{
    public function findById(string|int $id): ?array;

    public function findByCredentials(array $credentials): ?array;
}
