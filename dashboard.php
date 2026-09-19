<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

requirePage('dashboard');

$pdo = db();
$userCount = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$deptCount = (int)$pdo->query('SELECT COUNT(*) FROM departments')->fetchColumn();
$roleCount = (int)$pdo->query('SELECT COUNT(*) FROM roles')->fetchColumn();

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Dashboard</h1></div>
<div class="stat-grid">
  <div class="stat-card"><div class="stat-value"><?= $userCount ?></div><div class="stat-label">Users</div></div>
  <div class="stat-card"><div class="stat-value"><?= $deptCount ?></div><div class="stat-label">Departments</div></div>
  <div class="stat-card"><div class="stat-value"><?= $roleCount ?></div><div class="stat-label">Roles</div></div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
