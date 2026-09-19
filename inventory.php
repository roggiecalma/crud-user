<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('inventory');

renderSamplePage(
    'inventory',
    'Inventory',
    'Stock levels across all items.',
    [['312', 'Items tracked'], ['14', 'Low stock'], ['3', 'Out of stock'], ['$21,450', 'Stock value']],
    ['SKU', 'Item', 'Location', 'Qty', 'Status'],
    [
        ['SKU-1001', 'A4 Paper (ream)', 'Warehouse A', 240, 'In stock'],
        ['SKU-1002', 'Printer toner', 'Warehouse A', 6, 'Low stock'],
        ['SKU-2010', 'Office chair', 'Warehouse B', 0, 'Out of stock'],
        ['SKU-2044', 'Laptop stand', 'Warehouse B', 58, 'In stock'],
        ['SKU-3005', 'Whiteboard markers', 'Warehouse A', 9, 'Low stock'],
    ],
    [4]
);
