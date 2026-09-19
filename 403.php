<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>403 Forbidden</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
<div class="login-box">
  <h1>403</h1>
  <p>You don't have access to this page.</p>
  <?php $home = function_exists('homePage') ? homePage() : null; ?>
  <?php if ($home): ?>
    <a href="<?= htmlspecialchars($home, ENT_QUOTES, 'UTF-8') ?>">Go to your home page</a>
  <?php else: ?>
    <p class="muted">Your role has no pages assigned yet. Ask an administrator to grant access.</p>
    <a href="logout.php">Log out</a>
  <?php endif; ?>
</div>
</body>
</html>
