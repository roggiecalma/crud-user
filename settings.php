<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

// Static page: any signed-in user can open it, so it is not gated by requirePage() or the `pages` table.
requireLogin();

const MIN_PASSWORD_LENGTH = 8;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $userId = (int)currentUser()['id'];
    $stmt = db()->prepare('SELECT password FROM users WHERE id = :id AND status = "active" LIMIT 1');
    $stmt->execute(['id' => $userId]);
    $hash = $stmt->fetchColumn();

    if ($hash === false || !password_verify($current, $hash)) {
        flash('error', 'Current password is incorrect.');
    } elseif (strlen($new) < MIN_PASSWORD_LENGTH) {
        flash('error', 'New password must be at least ' . MIN_PASSWORD_LENGTH . ' characters.');
    } elseif ($new !== $confirm) {
        flash('error', 'New password and confirmation do not match.');
    } elseif ($new === $current) {
        flash('error', 'New password must be different from the current one.');
    } else {
        $stmt = db()->prepare('UPDATE users SET password = :password WHERE id = :id');
        $stmt->execute(['password' => password_hash($new, PASSWORD_DEFAULT), 'id' => $userId]);
        session_regenerate_id(true);
        flash('success', 'Your password has been changed.');
    }
    redirect('settings.php');
}

$pageTitle = 'Settings';
$activePage = 'settings';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head">
  <h1>Settings</h1>
  <p class="muted">Manage your account.</p>
</div>

<div class="card">
  <h2>Change Password</h2>
  <form method="post" class="form" autocomplete="off">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <div class="field">
      <label>Current password</label>
      <input type="password" name="current_password" required autocomplete="current-password">
    </div>
    <div class="field">
      <label>New password (min. <?= MIN_PASSWORD_LENGTH ?> characters)</label>
      <input type="password" name="new_password" required minlength="<?= MIN_PASSWORD_LENGTH ?>" autocomplete="new-password">
    </div>
    <div class="field">
      <label>Confirm new password</label>
      <input type="password" name="confirm_password" required minlength="<?= MIN_PASSWORD_LENGTH ?>" autocomplete="new-password">
    </div>
    <div class="field-actions">
      <button type="submit" class="btn">Change Password</button>
    </div>
  </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
