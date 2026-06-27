<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP CONFIGURATION FILE
File: config\auth.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Defines maintained application or framework configuration for the official FNLLA PHP stack.
*/

return [
    "session_key" => (string) env("AUTH_SESSION_KEY", "auth.user_id"),
    "providers" => [
        "users" => [
            "table" => (string) env("AUTH_USERS_TABLE", "users"),
            "key" => (string) env("AUTH_USERS_KEY", "id"),
            "identity" => (string) env("AUTH_USERS_IDENTITY", "email"),
            "password" => (string) env("AUTH_USERS_PASSWORD", "password"),
        ],
    ],
];
