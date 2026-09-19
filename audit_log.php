<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('audit_log');

renderSamplePage(
    'audit_log',
    'Audit Log',
    'A record of who did what, and when.',
    [['1,942', 'Events (30 days)'], ['18', 'Failed logins'], ['7', 'Role changes']],
    ['Time', 'User', 'Action', 'Target', 'Result'],
    [
        ['2026-09-19 10:04', 'roggie@lkphils.com', 'Login', 'Session', 'Success'],
        ['2026-09-19 10:12', 'roggie@lkphils.com', 'Created user', 'users #4', 'Success'],
        ['2026-09-19 09:58', 'unknown@example.com', 'Login', 'Session', 'Failed'],
        ['2026-09-18 16:40', 'maria@lkphils.com', 'Edited role', 'roles #2', 'Success'],
        ['2026-09-18 16:41', 'maria@lkphils.com', 'Deleted user', 'users #9', 'Failed'],
    ],
    [4]
);
