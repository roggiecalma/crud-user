<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('reports');

renderSamplePage(
    'reports',
    'Reports',
    'Monthly performance summary.',
    [['1,284', 'Total orders'], ['$48,320', 'Revenue'], ['92%', 'On-time delivery'], ['37', 'Open issues']],
    ['Report', 'Period', 'Owner', 'Generated', 'Status'],
    [
        ['Sales summary', 'Sep 2026', 'Finance', '2026-09-18', 'Completed'],
        ['Headcount', 'Q3 2026', 'HR', '2026-09-15', 'Completed'],
        ['Inventory valuation', 'Sep 2026', 'Operations', '2026-09-17', 'In progress'],
        ['Customer churn', 'Aug 2026', 'Sales', '2026-09-02', 'Completed'],
        ['Expense breakdown', 'Sep 2026', 'Finance', '-', 'Pending'],
    ],
    [4],
    ['Apr' => 32, 'May' => 41, 'Jun' => 38, 'Jul' => 52, 'Aug' => 47, 'Sep' => 61],
    'Revenue by month ($ thousands)'
);
