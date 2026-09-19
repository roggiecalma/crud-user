<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

requirePage('roles');

$pdo = db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Settings is static (every signed-in user gets it), so it is never offered as a grantable page.
$allPages = $pdo->query("SELECT * FROM pages WHERE page_key <> 'settings' ORDER BY sort_order, label")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'save') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $postId = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
        $pageIds = array_map('intval', $_POST['pages'] ?? []);

        if ($name === '') {
            flash('error', 'Role name is required.');
        } else {
            $pdo->beginTransaction();

            if ($postId) {
                $stmt = $pdo->prepare('UPDATE roles SET name = :name, description = :description WHERE id = :id');
                $stmt->execute(['name' => $name, 'description' => $description, 'id' => $postId]);
                $roleId = $postId;
            } else {
                $stmt = $pdo->prepare('INSERT INTO roles (name, description) VALUES (:name, :description)');
                $stmt->execute(['name' => $name, 'description' => $description]);
                $roleId = (int)$pdo->lastInsertId();
            }

            $pdo->prepare('DELETE FROM role_pages WHERE role_id = :role_id')->execute(['role_id' => $roleId]);
            if ($pageIds) {
                $insert = $pdo->prepare('INSERT INTO role_pages (role_id, page_id) VALUES (:role_id, :page_id)');
                foreach ($pageIds as $pageId) {
                    $insert->execute(['role_id' => $roleId, 'page_id' => $pageId]);
                }
            }

            $pdo->commit();
            flash('success', 'Role saved.');
        }
        redirect('roles.php');
    }

    if ($postAction === 'delete') {
        $postId = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM roles WHERE id = :id');
        $stmt->execute(['id' => $postId]);
        flash('success', 'Role deleted.');
        redirect('roles.php');
    }
}

$editing = null;
$editingPageIds = [];
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM roles WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $editing = $stmt->fetch() ?: null;

    if ($editing) {
        $stmt = $pdo->prepare('SELECT page_id FROM role_pages WHERE role_id = :id');
        $stmt->execute(['id' => $id]);
        $editingPageIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}

$roles = $pdo->query('SELECT * FROM roles ORDER BY name')->fetchAll();

$pageTitle = 'Roles';
$activePage = 'roles';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Roles</h1></div>

<div class="card">
  <h2><?= $editing ? 'Edit Role' : 'Add Role' ?></h2>
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
    <div class="field">
      <label>Pages this role can access</label>
      <div class="checkbox-grid">
        <?php foreach ($allPages as $page): ?>
          <label class="checkbox">
            <input type="checkbox" name="pages[]" value="<?= (int)$page['id'] ?>" <?= in_array((int)$page['id'], $editingPageIds, true) ? 'checked' : '' ?>>
            <?= e($page['label']) ?>
          </label>
        <?php endforeach; ?>
        <?php if (!$allPages): ?>
          <span class="muted">No pages defined yet.</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="field-actions">
      <button type="submit" class="btn"><?= $editing ? 'Update' : 'Create' ?></button>
      <?php if ($editing): ?><a href="roles.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <table class="table">
    <thead><tr><th>Name</th><th>Description</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($roles as $role): ?>
        <tr>
          <td><?= e($role['name']) ?></td>
          <td><?= e($role['description']) ?></td>
          <td class="table-actions">
            <a href="roles.php?action=edit&id=<?= (int)$role['id'] ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Delete this role?');" style="display:inline">
              <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$role['id'] ?>">
              <button type="submit" class="btn-link">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$roles): ?><tr><td colspan="3">No roles yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
