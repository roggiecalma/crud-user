<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('settings');

renderSamplePage(
    'settings',
    'Settings',
    'System-wide configuration.',
    [],
    ['Setting', 'Value', 'Description', 'Status'],
    [
        ['Company name', 'LKPhils', 'Shown in the app header and emails', 'Active'],
        ['Session timeout', '30 minutes', 'Idle time before automatic logout', 'Active'],
        ['Password policy', 'Min. 8 characters', 'Applied when creating or editing users', 'Active'],
        ['Email notifications', 'Off', 'Send alerts for approvals and overdue items', 'Disabled'],
        ['Maintenance mode', 'Off', 'Blocks non-admin logins while on', 'Disabled'],
    ],
    [3]
);
