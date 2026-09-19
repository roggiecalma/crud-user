<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void
{
    if (!currentUser()) {
        header('Location: login.php');
        exit;
    }
}

function allowedPageKeys(int $roleId): array
{
    $stmt = db()->prepare(
        'SELECT p.page_key FROM pages p
         INNER JOIN role_pages rp ON rp.page_id = p.id
         WHERE rp.role_id = :role_id'
    );
    $stmt->execute(['role_id' => $roleId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function requirePage(string $pageKey): void
{
    requireLogin();
    $pages = $_SESSION['allowed_pages'] ?? [];
    if (!in_array($pageKey, $pages, true)) {
        http_response_code(403);
        require __DIR__ . '/../403.php';
        exit;
    }
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(400);
        die('Invalid CSRF token.');
    }
}
