<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('announcements');

renderSamplePage(
    'announcements',
    'Announcements',
    'Company-wide notices and updates.',
    [['12', 'Published'], ['3', 'Drafts'], ['2', 'Scheduled']],
    ['Title', 'Audience', 'Author', 'Date', 'Status'],
    [
        ['Office closed on Oct 1', 'All staff', 'HR', '2026-09-19', 'Published'],
        ['New expense policy', 'Managers', 'Finance', '2026-09-16', 'Published'],
        ['Quarterly town hall', 'All staff', 'Admin', '2026-09-30', 'Draft'],
        ['System maintenance window', 'IT', 'IT Support', '2026-09-25', 'Pending'],
    ],
    [4]
);
