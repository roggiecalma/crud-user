<?php

declare(strict_types=1);

require __DIR__ . '/../includes/config.php';

if ($argc < 4) {
    fwrite(STDERR, "Usage: php bin/seed_admin.php <email> <password> <full name>\n");
    exit(1);
}

[, $email, $password, $name] = $argv;

$pdo = db();

$roleId = (int)$pdo->query("SELECT id FROM roles WHERE name = 'Administrator' LIMIT 1")->fetchColumn();
if (!$roleId) {
    fwrite(STDERR, "Administrator role not found. Run sql/schema.sql first.\n");
    exit(1);
}

$deptId = (int)$pdo->query('SELECT id FROM departments ORDER BY id LIMIT 1')->fetchColumn();

$stmt = $pdo->prepare(
    'INSERT INTO users (name, email, password, department_id, role_id, status)
     VALUES (:name, :email, :password, :department_id, :role_id, "active")
     ON DUPLICATE KEY UPDATE password = VALUES(password), role_id = VALUES(role_id), name = VALUES(name)'
);
$stmt->execute([
    'name' => $name,
    'email' => $email,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'department_id' => $deptId ?: null,
    'role_id' => $roleId,
]);

echo "Admin user ready: {$email}\n";
