<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('leave_requests');

renderSamplePage(
    'leave_requests',
    'Leave Requests',
    'Time-off requests waiting for review.',
    [['6', 'Pending'], ['31', 'Approved this year'], ['2', 'Rejected']],
    ['Employee', 'Type', 'From', 'To', 'Days', 'Status'],
    [
        ['Grace Tan', 'Vacation', '2026-09-19', '2026-09-26', 6, 'Approved'],
        ['Ana Cruz', 'Sick', '2026-09-20', '2026-09-21', 2, 'Pending'],
        ['Paolo Lim', 'Vacation', '2026-10-10', '2026-10-14', 3, 'Pending'],
        ['John Reyes', 'Emergency', '2026-09-05', '2026-09-05', 1, 'Rejected'],
    ],
    [5]
);
