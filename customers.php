<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('customers');

renderSamplePage(
    'customers',
    'Customers',
    'Client accounts and contacts.',
    [['186', 'Total customers'], ['12', 'New this month'], ['171', 'Active']],
    ['Company', 'Contact', 'Email', 'City', 'Status'],
    [
        ['Northwind Trading', 'Liza Ramos', 'liza@northwind.example', 'Makati', 'Active'],
        ['Blue Harbor Corp', 'Mark Dela Cruz', 'mark@blueharbor.example', 'Cebu', 'Active'],
        ['Sunrise Foods', 'Joy Bautista', 'joy@sunrise.example', 'Davao', 'Active'],
        ['Pioneer Logistics', 'Ken Aquino', 'ken@pioneer.example', 'Manila', 'Disabled'],
    ],
    [4]
);
