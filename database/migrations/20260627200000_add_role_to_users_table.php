<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP DATABASE MIGRATION
File: database\migrations\20260627200000_add_role_to_users_table.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). All rights reserved.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the proprietary FNLLA PHP framework and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Defines a schema change for the maintained MySQL delivery contract.
*/

use Fnlla\Php\Database\Migrations\Migration;

return new class(app(\Fnlla\Php\Database\DatabaseManager::class)) extends Migration {
    public function up(): void
    {
        $this->statement("ALTER TABLE users ADD COLUMN role VARCHAR(50) NOT NULL DEFAULT 'user' AFTER password");
    }

    public function down(): void
    {
        $this->statement("ALTER TABLE users DROP COLUMN role");
    }
};
