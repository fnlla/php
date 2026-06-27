<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP DATA FACTORY
File: database\factories\UserFactory.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Provides repeatable data generation for tests, seeding and local framework validation.
*/

namespace Database\Factories;

use Fnlla\Php\Database\Factories\Factory;

final class UserFactory extends Factory
{
    protected function table(): string
    {
        return "users";
    }

    protected function definition(): array
    {
        $unique = substr(bin2hex(random_bytes(8)), 0, 12);

        return [
            "name" => "Demo User",
            "email" => "user-" . $unique . "@example.com",
            "password" => app(\Fnlla\Php\Hashing\Hasher::class)->make("password123"),
            "role" => "user",
            "created_at" => gmdate("Y-m-d H:i:s"),
            "updated_at" => gmdate("Y-m-d H:i:s"),
        ];
    }
}
