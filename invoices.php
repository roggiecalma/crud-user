<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('invoices');

renderSamplePage(
    'invoices',
    'Invoices',
    'Billing and payment tracking.',
    [['$48,320', 'Billed this month'], ['$31,900', 'Paid'], ['$12,400', 'Outstanding'], ['$4,020', 'Overdue']],
    ['Invoice #', 'Customer', 'Issued', 'Amount', 'Status'],
    [
        ['INV-0231', 'Northwind Trading', '2026-09-02', '$8,400', 'Paid'],
        ['INV-0232', 'Blue Harbor Corp', '2026-09-05', '$12,400', 'Pending'],
        ['INV-0233', 'Sunrise Foods', '2026-08-12', '$4,020', 'Overdue'],
        ['INV-0234', 'Northwind Trading', '2026-09-14', '$6,150', 'Partial'],
        ['INV-0235', 'Pioneer Logistics', '2026-09-18', '$17,350', 'Draft'],
    ],
    [4]
);
