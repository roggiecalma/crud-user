<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('tasks');

renderSamplePage(
    'tasks',
    'Tasks',
    'Work items assigned to the team.',
    [['24', 'Open'], ['9', 'In progress'], ['58', 'Completed'], ['4', 'Overdue']],
    ['Task', 'Assigned to', 'Priority', 'Due', 'Status'],
    [
        ['Prepare payroll file', 'Maria Santos', 'High', '2026-09-22', 'In progress'],
        ['Update employee handbook', 'John Reyes', 'Medium', '2026-09-30', 'Pending'],
        ['Reconcile vendor invoices', 'Ana Cruz', 'High', '2026-09-15', 'Overdue'],
        ['Onboard new hires', 'Paolo Lim', 'Low', '2026-09-12', 'Done'],
        ['Renew software licenses', 'Maria Santos', 'Medium', '2026-10-05', 'Pending'],
    ],
    [4]
);
