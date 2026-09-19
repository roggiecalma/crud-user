<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

requirePage('users');

$pdo = db();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

$departments = $pdo->query('SELECT * FROM departments ORDER BY name')->fetchAll();
$roles = $pdo->query('SELECT * FROM roles ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'save') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $departmentId = ($_POST['department_id'] ?? '') !== '' ? (int)$_POST['department_id'] : null;
        $roleId = ($_POST['role_id'] ?? '') !== '' ? (int)$_POST['role_id'] : null;
        $status = ($_POST['status'] ?? '') === 'inactive' ? 'inactive' : 'active';
        $postId = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;

        if ($name === '' || $email === '' || (!$postId && $password === '')) {
            flash('error', 'Name, email, and password are required.');
        } else {
            try {
                if ($postId) {
                    if ($password !== '') {
                        $stmt = $pdo->prepare('UPDATE users SET name=:name, email=:email, password=:password, department_id=:department_id, role_id=:role_id, status=:status WHERE id=:id');
                        $stmt->execute([
                            'name' => $name, 'email' => $email,
                            'password' => password_hash($password, PASSWORD_DEFAULT),
                            'department_id' => $departmentId, 'role_id' => $roleId,
                            'status' => $status, 'id' => $postId,
                        ]);
                    } else {
                        $stmt = $pdo->prepare('UPDATE users SET name=:name, email=:email, department_id=:department_id, role_id=:role_id, status=:status WHERE id=:id');
                        $stmt->execute([
                            'name' => $name, 'email' => $email,
                            'department_id' => $departmentId, 'role_id' => $roleId,
                            'status' => $status, 'id' => $postId,
                        ]);
                    }
                    flash('success', 'User updated.');
                } else {
                    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, department_id, role_id, status) VALUES (:name,:email,:password,:department_id,:role_id,:status)');
                    $stmt->execute([
                        'name' => $name, 'email' => $email,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'department_id' => $departmentId, 'role_id' => $roleId,
                        'status' => $status,
                    ]);
                    flash('success', 'User created.');
                }
            } catch (PDOException $ex) {
                flash('error', 'Could not save user. Email may already be in use.');
            }
        }
        redirect('users.php');
    }

    if ($postAction === 'delete') {
        $postId = (int)($_POST['id'] ?? 0);
        if ($postId === (int)(currentUser()['id'] ?? 0)) {
            flash('error', 'You cannot delete your own account.');
        } else {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute(['id' => $postId]);
            flash('success', 'User deleted.');
        }
        redirect('users.php');
    }
}

$editing = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $editing = $stmt->fetch() ?: null;
}

$users = $pdo->query(
    'SELECT u.*, d.name AS department_name, r.name AS role_name
     FROM users u
     LEFT JOIN departments d ON d.id = u.department_id
     LEFT JOIN roles r ON r.id = u.role_id
     ORDER BY u.name'
)->fetchAll();

$pageTitle = 'Users';
$activePage = 'users';
require __DIR__ . '/includes/header.php';
?>
<div class="page-head"><h1>Users</h1></div>

<div class="card">
  <h2><?= $editing ? 'Edit User' : 'Add User' ?></h2>
  <form method="post" class="form">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <input type="hidden" name="action" value="save">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
    <div class="field">
      <label>Name</label>
      <input type="text" name="name" required value="<?= e($editing['name'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Email</label>
      <input type="email" name="email" required value="<?= e($editing['email'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Password <?= $editing ? '(leave blank to keep current)' : '' ?></label>
      <input type="password" name="password" <?= $editing ? '' : 'required' ?>>
    </div>
    <div class="field">
      <label>Department</label>
      <select name="department_id">
        <option value="">-- None --</option>
        <?php foreach ($departments as $dept): ?>
          <option value="<?= (int)$dept['id'] ?>" <?= (isset($editing['department_id']) && (int)$editing['department_id'] === (int)$dept['id']) ? 'selected' : '' ?>><?= e($dept['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Role</label>
      <select name="role_id">
        <option value="">-- None --</option>
        <?php foreach ($roles as $role): ?>
          <option value="<?= (int)$role['id'] ?>" <?= (isset($editing['role_id']) && (int)$editing['role_id'] === (int)$role['id']) ? 'selected' : '' ?>><?= e($role['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Status</label>
      <select name="status">
        <option value="active" <?= (!$editing || $editing['status'] === 'active') ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= ($editing && $editing['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <div class="field-actions">
      <button type="submit" class="btn"><?= $editing ? 'Update' : 'Create' ?></button>
      <?php if ($editing): ?><a href="users.php" class="btn btn-secondary">Cancel</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Department</th><th>Role</th><th>Status</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= e($u['name']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['department_name'] ?? '—') ?></td>
          <td><?= e($u['role_name'] ?? '—') ?></td>
          <td><span class="badge badge-<?= $u['status'] === 'active' ? 'success' : 'muted' ?>"><?= e(ucfirst($u['status'])) ?></span></td>
          <td class="table-actions">
            <a href="users.php?action=edit&id=<?= (int)$u['id'] ?>">Edit</a>
            <form method="post" onsubmit="return confirm('Delete this user?');" style="display:inline">
              <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
              <button type="submit" class="btn-link">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$users): ?><tr><td colspan="6">No users yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
