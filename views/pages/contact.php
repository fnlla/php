<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP VIEW TEMPLATE
File: views\pages\contact.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Defines a maintained page template for the official FNLLA PHP demonstration surface.
*/

$nameError = error_for("name");
$emailError = error_for("email");
$briefError = error_for("brief");
$allErrors = errors();
?>
<section class="section pt-1">
  <div class="container site-page-stack">
    <section class="hero hero-compact" aria-label="Contact page introduction">
      <div class="grid gap-md hero-copy">
        <div class="d-flex flex-wrap items-center gap-md">
          <span class="tag">Working form example</span>
          <span class="badge">Validation</span>
          <span class="badge">CSRF</span>
          <span class="badge">Flash feedback</span>
        </div>
        <h1 class="hero-title">A real contact flow is already part of the starter, not just static demo markup.</h1>
        <p class="hero-text">This page demonstrates how FNLLA PHP and FNLLA Web work together in a practical user journey: page rendering, form structure, validation, redirect-after-post and visible success or error feedback.</p>
        <ul class="hero-proof-list">
          <li>Failed submits keep old values and return field-level messages.</li>
          <li>Successful submits flash confirmation into the next request.</li>
          <li>The entire form remains inside the shared FNLLA Web layout and component contract.</li>
        </ul>
      </div>
      <div class="hero-inline-facts" aria-label="Contact page support facts">
        <div class="hero-inline-fact">
          <span class="badge">Good for</span>
          <p class="content-text mb-0">Service requests, onboarding forms, internal intake flows and protected admin submissions.</p>
        </div>
        <div class="hero-inline-fact">
          <span class="badge">Replace first</span>
          <p class="content-text mb-0">Swap the demo copy, mail destination, service tracks and validation rules for your real project context.</p>
        </div>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <section class="feature-section" aria-label="Engagement tracks">
      <div class="section-header mb-0">
        <p class="feature-kicker">Project use cases</p>
        <h2 class="section-title">Three starter-friendly tracks for the kind of work this example is meant to model.</h2>
        <p class="section-text">The cards below are demo content, but the section pattern itself is the useful part: compact offer framing before the form takes over.</p>
      </div>
      <div class="grid grid-3 gap-md">
        <?php foreach ($engagementTracks as $track): ?>
        <article class="feature-card">
          <h3 class="content-title"><?= h($track["title"]) ?></h3>
          <p class="content-text"><?= h($track["text"]) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <section class="contact-section" id="contact-form">
      <div class="contact-grid">
        <aside class="contact-card contact-summary-card" aria-label="Contact section summary">
          <p class="contact-kicker">Flow summary</p>
          <h2 class="contact-card-title">Use one reusable server-rendered intake pattern instead of rebuilding form feedback on every new page.</h2>
          <p class="contact-text">The starter shows a complete baseline: request data capture, validation, flashed status and a real redirect-after-post flow.</p>
          <ul class="contact-list">
            <li>CSRF token verification on submit</li>
            <li>Session-backed flash messages</li>
            <li>Preserved input values after validation errors</li>
            <li>Mail and event hooks after a successful submit</li>
          </ul>
        </aside>

        <article class="cta-card contact-form-card">
          <form class="form contact-form" action="<?= h(route("contact.submit")) ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <?php if ($allErrors !== []): ?>
            <div class="form-message form-message-error" role="alert" aria-labelledby="contact-form-error-title" aria-describedby="contact-form-error-text">
              <h3 class="form-message-title" id="contact-form-error-title">We still need a few details</h3>
              <p class="form-message-text" id="contact-form-error-text">Review the highlighted fields below before resubmitting the request.</p>
            </div>
            <?php endif; ?>

            <div class="grid grid-2 contact-field-grid">
              <div class="form-group contact-field">
                <label class="label" for="contact-name">Name</label>
                <input class="input" id="contact-name" name="name" type="text" autocomplete="name" placeholder="Your name" aria-describedby="<?= $nameError ? 'contact-name-error' : 'contact-name-help' ?>" <?= $nameError ? 'aria-invalid="true"' : "" ?> value="<?= h((string) old("name")) ?>" required>
                <div class="contact-field-meta">
                  <?php if ($nameError): ?>
                  <p class="error-text" id="contact-name-error"><?= h($nameError) ?></p>
                  <?php else: ?>
                  <p class="help-text" id="contact-name-help">Enter the person who owns the request.</p>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group contact-field">
                <label class="label" for="contact-company">Company</label>
                <input class="input" id="contact-company" name="company" type="text" autocomplete="organization" placeholder="Your company" value="<?= h((string) old("company")) ?>">
                <div class="contact-field-meta">
                  <p class="help-text">Optional when the request is individual rather than organizational.</p>
                </div>
              </div>
            </div>

            <div class="grid grid-2 contact-field-grid">
              <div class="form-group contact-field">
                <label class="label" for="contact-email">Email</label>
                <input class="input" id="contact-email" name="email" type="email" autocomplete="email" placeholder="you@example.com" aria-describedby="<?= $emailError ? 'contact-email-error' : 'contact-email-help' ?>" <?= $emailError ? 'aria-invalid="true"' : "" ?> value="<?= h((string) old("email")) ?>" required>
                <div class="contact-field-meta">
                  <?php if ($emailError): ?>
                  <p class="error-text" id="contact-email-error"><?= h($emailError) ?></p>
                  <?php else: ?>
                  <p class="help-text" id="contact-email-help">Use the address where project updates should be sent.</p>
                  <?php endif; ?>
                </div>
              </div>

              <div class="form-group contact-field">
                <label class="label" for="contact-scope">Service area</label>
                <select class="select" id="contact-scope" name="scope">
                  <?php $selectedScope = (string) old("scope", "Implementation support"); ?>
                  <?php foreach (["Platform advisory", "Implementation support", "Operational support"] as $scopeOption): ?>
                  <option value="<?= h($scopeOption) ?>" <?= $selectedScope === $scopeOption ? "selected" : "" ?>><?= h($scopeOption) ?></option>
                  <?php endforeach; ?>
                </select>
                <p class="help-text">Choose the track that best matches the requested work.</p>
              </div>
            </div>

            <div class="form-group">
              <label class="label" for="contact-brief">Project brief</label>
              <textarea class="textarea" id="contact-brief" name="brief" placeholder="Outline requirements, preferred timing and any implementation notes." aria-describedby="<?= $briefError ? 'contact-brief-error' : 'contact-brief-help' ?>" <?= $briefError ? 'aria-invalid="true"' : "" ?>><?= h((string) old("brief")) ?></textarea>
              <?php if ($briefError): ?>
              <p class="error-text" id="contact-brief-error"><?= h($briefError) ?></p>
              <?php else: ?>
              <p class="help-text" id="contact-brief-help">A short implementation summary is enough for the demo.</p>
              <?php endif; ?>
            </div>

            <div class="d-flex flex-wrap gap-md">
              <button class="btn btn-primary" type="submit">Submit request</button>
              <a class="btn btn-ghost" href="<?= h(route("platform")) ?>">Review the platform</a>
            </div>
          </form>
        </article>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <section class="process-section" aria-label="Contact flow process">
      <div class="section-header mb-0">
        <p class="process-kicker">Delivery sequence</p>
        <h2 class="section-title">The example is simple, but it follows a pattern that scales into real product work.</h2>
        <p class="section-text">Treat this as a reusable shape for request capture, not as demo-only decoration.</p>
      </div>
      <div class="process-grid">
        <?php foreach ($deliverySteps as $step): ?>
        <article class="process-step">
          <span class="process-step-number"><?= h($step["number"]) ?></span>
          <h3 class="process-step-title"><?= h($step["title"]) ?></h3>
          <p class="process-step-text"><?= h($step["text"]) ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <section class="faq-section" aria-label="Contact FAQ">
      <div class="faq-layout">
        <div class="section-header mb-0">
          <p class="feature-kicker">Form FAQ</p>
          <h2 class="section-title">A couple of practical clarifications for teams treating this page as a starter reference.</h2>
        </div>
        <div class="accordion" data-fnlla-accordion data-fnlla-accordion-single>
          <?php foreach ($contactFaqs as $index => $faqItem): ?>
          <?php $isOpen = $index === 0; ?>
          <div class="accordion-item<?= $isOpen ? " is-open" : "" ?>">
            <button class="accordion-button" id="contact-faq-trigger-<?= $index + 1 ?>" type="button" data-fnlla-accordion-button aria-expanded="<?= $isOpen ? "true" : "false" ?>" aria-controls="contact-faq-panel-<?= $index + 1 ?>">
              <?= h($faqItem["question"]) ?>
            </button>
            <div class="accordion-panel" id="contact-faq-panel-<?= $index + 1 ?>" role="region" aria-labelledby="contact-faq-trigger-<?= $index + 1 ?>">
              <p class="content-text"><?= h($faqItem["answer"]) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </div>
</section>
