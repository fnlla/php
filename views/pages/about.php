<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP VIEW TEMPLATE
File: views\pages\about.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Defines a maintained page template for the official FNLLA PHP demonstration surface.
*/
?>
<section class="section pt-1">
  <div class="container site-page-stack">
    <section class="hero hero-compact" aria-label="About FNLLA PHP">
      <div class="grid gap-md hero-copy">
        <div class="d-flex flex-wrap items-center gap-md">
          <span class="tag">Framework model</span>
          <span class="badge">Plain PHP views</span>
          <span class="badge">One shared runtime</span>
        </div>
        <h1 class="hero-title">FNLLA PHP stays intentionally explicit so teams can learn and change the whole stack faster.</h1>
        <p class="hero-text">The framework does not try to win by hiding more. It tries to win by keeping routing, bootstrap, UI boundary and release checks readable enough that real delivery work remains approachable.</p>
        <ul class="hero-proof-list">
          <?php foreach ($principles as $principle): ?>
          <li><?= h($principle) ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="hero-actions">
          <a class="btn btn-primary" href="<?= h(route("platform")) ?>">See the platform surface</a>
          <a class="btn btn-outline" href="<?= h(route("contact")) ?>">Open the working form</a>
        </div>
      </div>
      <div class="hero-inline-facts" aria-label="About page support facts">
        <div class="hero-inline-fact">
          <span class="badge">Maintainer repo</span>
          <p class="content-text mb-0">`fnlla/php` remains the shared source of truth for framework behavior, docs and starter export rules.</p>
        </div>
        <div class="hero-inline-fact">
          <span class="badge">Downstream repo</span>
          <p class="content-text mb-0">Each exported starter becomes the actual project repository where client or internal application work belongs.</p>
        </div>
        <div class="hero-inline-fact">
          <span class="badge">UI dependency</span>
          <p class="content-text mb-0">FNLLA Web stays the only supported UI runtime in the official stack, consumed one-way under `public/vendor/fnlla-web/`.</p>
        </div>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-3 gap-md">
      <?php foreach ($starterBoundaries as $boundary): ?>
      <article class="card">
        <h2 class="card-title"><?= h($boundary["title"]) ?></h2>
        <p class="card-text"><?= h($boundary["text"]) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Request lifecycle in one trace</h2>
      <p class="section-text">The lifecycle is short enough to inspect directly, but structured enough to support real forms, protected areas and operational routines.</p>
    </div>

    <div class="timeline">
      <?php foreach ($timelineItems as $timelineItem): ?>
      <article class="timeline-item">
        <div class="timeline-marker" aria-hidden="true"></div>
        <div class="timeline-content">
          <p class="timeline-title"><?= h($timelineItem["title"]) ?></p>
          <p class="timeline-text"><?= h($timelineItem["text"]) ?></p>
          <p class="timeline-meta"><?= h($timelineItem["meta"]) ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-2 gap-md">
      <article class="card">
        <h2 class="card-title">Why the export boundary matters</h2>
        <p class="card-text">A downstream application should not inherit the full framework browser docs workspace, maintainer-only build scripts or local runtime residue from the source repository.</p>
        <p class="card-text">That is why `make:project` now exports the application-facing surface instead of acting like a blunt repository copy.</p>
      </article>
      <article class="card">
        <h2 class="card-title">What remains easy to extend</h2>
        <p class="card-text">Routes, controllers, views, auth flows, migrations, queueing and version checks remain simple enough to extend without introducing large secondary abstraction layers too early.</p>
      </article>
    </div>
  </div>
</section>
