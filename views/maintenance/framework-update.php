<?php

declare(strict_types=1);

$pageState = is_array($frameworkUpdatePageState ?? null) ? $frameworkUpdatePageState : [];
$lock = is_array($frameworkUpdateLock ?? null) ? $frameworkUpdateLock : [];
$report = is_array($frameworkUpdateReport ?? null) ? $frameworkUpdateReport : null;
$applicationMeta = (array) ($lock["framework_base"]["application"] ?? []);
$frameworkMeta = (array) ($lock["framework_base"]["framework"] ?? []);
$uiMeta = (array) ($lock["framework_base"]["ui_runtime"] ?? []);
$managedFiles = (array) ($lock["framework_base"]["managed_files"] ?? []);
$sourcePathValue = (string) ($frameworkUpdateSourcePath ?? "");
?>
<section class="section pt-1">
  <div class="container site-page-stack">
    <section class="hero hero-compact" aria-label="Framework maintenance introduction">
      <div class="grid gap-md hero-copy">
        <div class="d-flex flex-wrap items-center gap-md">
          <span class="tag">Framework maintenance</span>
          <span class="badge">Browser-triggered</span>
          <span class="badge">Safe update checks</span>
          <span class="badge">Conflict-aware</span>
        </div>
        <h1 class="hero-title">Keep the application aligned with FNLLA PHP without turning it into a second framework workspace.</h1>
        <p class="hero-text">This page wraps the framework update workflow in a compact, local-first maintenance surface. It compares the current application against a fresh export from a maintained <code>fnlla/php</code> source checkout and only offers safe apply when framework-managed files can move without conflict.</p>
      </div>
    </section>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid grid-3 gap-md">
      <article class="feature-card">
        <p class="feature-kicker">Current base</p>
        <h2 class="content-title mb-xs"><?= h((string) ($frameworkMeta["version"] ?? "unknown")) ?></h2>
        <p class="content-text mb-0">FNLLA PHP for <?= h((string) ($applicationMeta["name"] ?? config("app.name"))) ?>.</p>
      </article>
      <article class="feature-card">
        <p class="feature-kicker">Vendored UI runtime</p>
        <h2 class="content-title mb-xs"><?= h((string) ($uiMeta["version"] ?? "unknown")) ?></h2>
        <p class="content-text mb-0">FNLLA Web currently locked into this application.</p>
      </article>
      <article class="feature-card">
        <p class="feature-kicker">Managed files</p>
        <h2 class="content-title mb-xs"><?= h((string) count($managedFiles)) ?></h2>
        <p class="content-text mb-0">Framework-managed files tracked inside <code>.fnlla/framework-lock.json</code>.</p>
      </article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-grid">
      <aside class="contact-card contact-summary-card" aria-label="Framework update controls summary">
        <p class="contact-kicker">Execution guard</p>
        <h2 class="contact-card-title">Professional by default means local-first, explicit source selection and conflict-aware apply.</h2>
        <p class="contact-text"><?= h((string) ($pageState["message"] ?? "")) ?></p>
        <ul class="contact-list">
          <li>Browser UI enabled: <strong><?= ($pageState["enabled"] ?? false) ? "Yes" : "No" ?></strong></li>
          <li>Local-only mode: <strong><?= ($pageState["local_only"] ?? false) ? "Yes" : "No" ?></strong></li>
          <li>Apply allowed from UI: <strong><?= ($pageState["can_apply"] ?? false) ? "Yes" : "No" ?></strong></li>
          <li>Current request is local: <strong><?= ($pageState["is_local_request"] ?? false) ? "Yes" : "No" ?></strong></li>
        </ul>
        <p class="contact-text mb-0">Use a maintained local <code>fnlla/php</code> checkout as the source path. The page will export a fresh project baseline from that source, compare it to this application and then surface only safe changes or explicit conflicts.</p>
      </aside>

      <article class="cta-card contact-form-card">
        <form class="form contact-form" action="<?= h(route("maintenance.framework_update.run")) ?>" method="post" novalidate>
          <?= csrf_field() ?>
          <div class="form-group">
            <label class="label" for="framework-update-source">Maintained FNLLA PHP source path</label>
            <input class="input" id="framework-update-source" name="source_path" type="text" placeholder="..\fnlla-php or C:\path\to\fnlla-php" value="<?= h($sourcePathValue) ?>" <?= ($pageState["can_run"] ?? false) ? "" : "disabled" ?>>
            <p class="help-text">Point this at the maintained source repository that should act as the framework-update baseline for this application.</p>
          </div>

          <div class="grid grid-2 contact-field-grid">
            <button class="btn btn-outline" type="submit" name="mode" value="check" <?= ($pageState["can_run"] ?? false) ? "" : "disabled" ?>>Check framework drift</button>
            <button class="btn btn-primary" type="submit" name="mode" value="apply" <?= ($pageState["can_apply"] ?? false) ? "" : "disabled" ?>>Apply safe update</button>
          </div>

          <div class="form-message" role="status">
            <h3 class="form-message-title">Recommended sequence</h3>
            <p class="form-message-text mb-0">Run a check first, review conflicts, apply only when the report shows the update can move framework-managed files without clobbering application work.</p>
          </div>
        </form>
      </article>
    </div>
  </div>
</section>

<?php if (is_array($report)): ?>
<section class="section">
  <div class="container">
    <section class="feature-section" aria-label="Framework update report">
      <div class="section-header mb-0">
        <p class="feature-kicker">Structured report</p>
        <h2 class="section-title">The last framework update run kept the output readable instead of dumping raw shell text.</h2>
        <p class="section-text">Review the source baseline, safe changes, conflicts and local-only managed edits before moving on.</p>
      </div>

      <div class="grid grid-3 gap-md mb-lg">
        <article class="feature-card">
          <p class="feature-kicker">Mode</p>
          <h3 class="content-title"><?= h(strtoupper((string) ($report["mode"] ?? "check"))) ?></h3>
          <p class="content-text mb-0">Executed at <?= h((string) ($report["executed_at_utc"] ?? "unknown")) ?></p>
        </article>
        <article class="feature-card">
          <p class="feature-kicker">Source baseline</p>
          <h3 class="content-title"><?= h((string) ($report["source_framework_version"] ?? "unknown")) ?></h3>
          <p class="content-text mb-0">FNLLA Web <?= h((string) ($report["source_ui_version"] ?? "unknown")) ?></p>
        </article>
        <article class="feature-card">
          <p class="feature-kicker">Summary</p>
          <h3 class="content-title"><?= h((string) count((array) ($report["updates"] ?? []))) ?> safe / <?= h((string) count((array) ($report["conflicts"] ?? []))) ?> conflicts</h3>
          <p class="content-text mb-0">Local-only managed changes preserved: <?= h((string) count((array) ($report["local_only_changes"] ?? []))) ?></p>
        </article>
      </div>

      <?php if (!empty($report["updates"])): ?>
      <article class="feature-card mb-lg">
        <h3 class="content-title">Safe changes available</h3>
        <ul class="contact-list">
          <?php foreach ((array) $report["updates"] as $path => $update): ?>
          <li><strong><?= h(strtoupper((string) ($update["action"] ?? "update"))) ?></strong> <?= h((string) $path) ?></li>
          <?php endforeach; ?>
        </ul>
      </article>
      <?php endif; ?>

      <?php if (!empty($report["conflicts"])): ?>
      <article class="feature-card mb-lg">
        <h3 class="content-title">Conflicts that need manual review</h3>
        <ul class="contact-list">
          <?php foreach ((array) $report["conflicts"] as $path => $conflict): ?>
          <li><strong><?= h((string) $path) ?></strong> - <?= h((string) ($conflict["reason"] ?? "conflict")) ?></li>
          <?php endforeach; ?>
        </ul>
      </article>
      <?php endif; ?>

      <?php if (!empty($report["local_only_changes"])): ?>
      <article class="feature-card">
        <h3 class="content-title">Local-only managed changes preserved</h3>
        <ul class="contact-list">
          <?php foreach ((array) $report["local_only_changes"] as $path => $change): ?>
          <li><strong><?= h((string) $path) ?></strong> - <?= h((string) ($change["reason"] ?? "local change")) ?></li>
          <?php endforeach; ?>
        </ul>
      </article>
      <?php endif; ?>
    </section>
  </div>
</section>
<?php endif; ?>
