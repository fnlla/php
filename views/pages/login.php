<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP VIEW TEMPLATE
File: views\pages\login.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Defines a maintained page template for the official FNLLA PHP demonstration surface.
*/

$emailError = error_for("email");
$passwordError = error_for("password");
?>
<section class="section pt-1">
  <div class="container">
    <div class="grid grid-2 gap-lg site-login-grid">
      <div class="grid gap-md">
        <div class="section-header mb-0">
          <h1 class="section-title">Starter sign-in surface</h1>
          <p class="section-text">This page demonstrates the auth guard, validation feedback and session-backed redirects already included in the framework starter.</p>
        </div>

        <div class="list-group">
          <?php foreach ($loginHighlights as $highlight): ?>
          <article class="list-group-item">
            <h2 class="list-group-item-title"><?= h($highlight["title"]) ?></h2>
            <p class="list-group-item-text"><?= h($highlight["text"]) ?></p>
          </article>
          <?php endforeach; ?>
        </div>

        <div class="hero-inline-facts" aria-label="Starter auth facts">
          <div class="hero-inline-fact">
            <span class="badge">Protected pages</span>
            <p class="content-text mb-0">After sign-in, the starter can route into dashboard and admin examples using the built-in middleware and authorization gates.</p>
          </div>
          <div class="hero-inline-fact">
            <span class="badge">Good next step</span>
            <p class="content-text mb-0">Replace the demo users, login copy and follow-up pages once the starter becomes a real portal or internal workspace.</p>
          </div>
        </div>
      </div>

      <article class="cta-card contact-form-card">
        <form class="form contact-form" action="<?= h(route("login.submit")) ?>" method="post" novalidate>
          <?= csrf_field() ?>

          <div class="form-group">
            <label class="label" for="login-email">Email</label>
            <input class="input" id="login-email" name="email" type="email" value="<?= h((string) old("email")) ?>" autocomplete="email" <?= $emailError ? 'aria-invalid="true"' : "" ?>>
            <?php if ($emailError): ?>
            <p class="error-text"><?= h($emailError) ?></p>
            <?php else: ?>
            <p class="help-text">Use a user from the `users` table after running migrations and seed data.</p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label class="label" for="login-password">Password</label>
            <input class="input" id="login-password" name="password" type="password" autocomplete="current-password" <?= $passwordError ? 'aria-invalid="true"' : "" ?>>
            <?php if ($passwordError): ?>
            <p class="error-text"><?= h($passwordError) ?></p>
            <?php else: ?>
            <p class="help-text">Passwords are verified by the framework hasher service.</p>
            <?php endif; ?>
          </div>

          <div class="d-flex flex-wrap gap-md">
            <button class="btn btn-primary" type="submit">Sign in</button>
            <a class="btn btn-ghost" href="<?= h(route("about")) ?>">Read the framework model</a>
          </div>
        </form>
      </article>
    </div>
  </div>
</section>
