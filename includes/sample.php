<?php

declare(strict_types=1);

/**
 * Boilerplate renderer used by the sample pages (reports, tasks, invoices, ...).
 *
 * Each sample page passes in hard-coded example data. When you build a page for real,
 * replace the arrays with database queries and edit the markup freely — nothing else
 * depends on this file.
 */

function sampleBadgeClass(string $value): string
{
    $v = strtolower(trim($value));
    if (in_array($v, ['active', 'paid', 'approved', 'present', 'done', 'completed', 'in stock', 'published', 'enabled', 'success'], true)) {
        return 'success';
    }
    if (in_array($v, ['pending', 'in progress', 'late', 'low stock', 'draft', 'partial', 'on leave'], true)) {
        return 'warning';
    }
    if (in_array($v, ['overdue', 'rejected', 'absent', 'out of stock', 'failed', 'cancelled', 'disabled'], true)) {
        return 'danger';
    }
    return 'muted';
}

/**
 * @param array<int, array{0:string,1:string}>      $stats      [value, label] tiles
 * @param array<int, string>                        $columns    table headings
 * @param array<int, array<int, string|int|float>>  $rows       table rows
 * @param array<int, int>                           $badgeCols  column indexes rendered as status badges
 * @param array<string, int|float>|null             $chart      label => value, drawn as a bar chart
 */
function renderSamplePage(
    string $pageKey,
    string $title,
    string $intro,
    array $stats,
    array $columns,
    array $rows,
    array $badgeCols = [],
    ?array $chart = null,
    string $chartTitle = ''
): void {
    $pageTitle = $title;
    $activePage = $pageKey;
    require __DIR__ . '/header.php';
    ?>
<div class="page-head">
  <h1><?= e($title) ?></h1>
  <p class="muted"><?= e($intro) ?></p>
</div>
<div class="alert alert-info">Sample data only &mdash; replace the example values in <code><?= e($pageKey) ?>.php</code> with real queries.</div>

<?php if ($stats): ?>
<div class="stat-grid section-gap">
  <?php foreach ($stats as [$value, $label]): ?>
    <div class="stat-card"><div class="stat-value"><?= e((string)$value) ?></div><div class="stat-label"><?= e($label) ?></div></div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($chart): ?>
<?php $max = max(1, (int)max($chart)); ?>
<div class="card">
  <h2><?= e($chartTitle) ?></h2>
  <div class="bar-chart">
    <?php foreach ($chart as $label => $value): ?>
      <div class="bar-col">
        <div class="bar-value"><?= e((string)$value) ?></div>
        <div class="bar-track"><div class="bar" style="height: <?= (int)round(((float)$value / $max) * 100) ?>%"></div></div>
        <div class="bar-label"><?= e((string)$label) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <table class="table">
    <thead><tr><?php foreach ($columns as $col): ?><th><?= e($col) ?></th><?php endforeach; ?></tr></thead>
    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <?php foreach ($row as $i => $cell): ?>
            <?php if (in_array($i, $badgeCols, true)): ?>
              <td><span class="badge badge-<?= sampleBadgeClass((string)$cell) ?>"><?= e((string)$cell) ?></span></td>
            <?php else: ?>
              <td><?= e((string)$cell) ?></td>
            <?php endif; ?>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="<?= count($columns) ?>">Nothing to show yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php
    require __DIR__ . '/footer.php';
}
