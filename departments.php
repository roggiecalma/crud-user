<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

requirePage('departments');

$pdo = db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'save') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $postId = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;

        if ($name === '') {
            flash('error', 'Department name is required.');
        } else {
            if ($postId) {
                $stmt = $pdo->prepare('UPDATE departments SET name = :name, description = :description WHERE id = :id');
                $stmt->execute(['name' => $name, 'description' => $description, 'id' => $postId]);
                flash('success', 'Department updated.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO departments (name, description) VALUES (:name, :description)');
                $stmt->execute(['name' => $name, 'description' => $description]);
                flash('success', 'Department created.');
            }
        }
        redirect('departments.php');
    }

    if ($postAction === 'delete') {
        $postId = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM departments WHERE id = :id');
        $stmt->execute(['id' => $postId]);
        flash('success', 'Department deleted.');
        redirect('departments.php');
    }
}

$editing = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $editing = $stmt->fetch() ?: null;
}

$departments = $pdo->query('SELECT * FROM departments ORDER BY name')->fetchAll();

$pageTitle = 'Departments';
$activePage = 'departments';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Departments</h1></div>

<div class="card">
  <h2><?= $editing ? 'Edit Department' : 'Add Department' ?></h2>
  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <input type="hidden" name="action" value="save">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
    <div class="field">
      <label>Name</label>
      <input type="text" name="name" required value="<?= e($editing['name'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Description</label>
      <input type="text" name="description" value="<?= e($editing['description'] ?? '') ?>">
    </div>
    <div class="field-actions">
      <button type="submit" class="btn"><?= $editing ? 'Update' : 'Create' ?></button>
      <?php if ($editing): ?><a href="departments.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <table class="table">
    <thead>
      <tr><th>Name</th><th>Description</th><th></th></tr>
    </thead>
    <tbody>
      <?php foreach ($departments as $dept): ?>
        <tr>
          <td><?= e($dept['name']) ?></td>
          <td><?= e($dept['description']) ?></td>
          <td class="table-actions">
            <a href="departments.php?action=edit&id=<?= (int)$dept['id'] ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Delete this department?');" style="display:inline">
              <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$dept['id'] ?>">
              <button type="submit" class="btn-link">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$departments): ?>
        <tr><td colspan="3">No departments yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
