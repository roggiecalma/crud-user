<?php
/** @var string $pageTitle */
/** @var string $activePage */

$user = currentUser();
$allowedPages = $_SESSION['allowed_pages'] ?? [];

$navItems = [
    'dashboard' => ['label' => 'Dashboard', 'href' => 'dashboard.php'],
    'users' => ['label' => 'Users', 'href' => 'users.php'],
    'departments' => ['label' => 'Departments', 'href' => 'departments.php'],
    'roles' => ['label' => 'Roles', 'href' => 'roles.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'CRUD User') ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="brand">CRUD User</div>
    <nav>
      <?php foreach ($navItems as $key => $item): ?>
        <?php if (in_array($key, $allowedPages, true)): ?>
          <a href="<?= e($item['href']) ?>" class="<?= ($activePage ?? '') === $key ? 'active' : '' ?>"><?= e($item['label']) ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>
  </aside>
  <div class="main">
    <header class="topbar">
      <div></div>
      <div class="user-menu">
        <span><?= e($user['name'] ?? '') ?> &middot; <?= e($user['role_name'] ?? 'No role') ?></span>
        <a href="logout.php">Logout</a>
      </div>
    </header>
    <main class="content">
      <?php $successMsg = flash('success'); $errorMsg = flash('error'); ?>
      <?php if ($successMsg): ?><div class="alert alert-success"><?= e($successMsg) ?></div><?php endif; ?>
      <?php if ($errorMsg): ?><div class="alert alert-error"><?= e($errorMsg) ?></div><?php endif; ?>
