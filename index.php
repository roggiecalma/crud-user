<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';

if (currentUser()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
