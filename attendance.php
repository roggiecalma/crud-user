<?php

declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/sample.php';

requirePage('attendance');

renderSamplePage(
    'attendance',
    'Attendance',
    'Daily time-in / time-out log.',
    [['48', 'Present today'], ['3', 'Late'], ['2', 'Absent'], ['5', 'On leave']],
    ['Employee', 'Department', 'Time in', 'Time out', 'Status'],
    [
        ['Maria Santos', 'Finance', '08:02', '17:05', 'Present'],
        ['John Reyes', 'HR', '08:47', '17:30', 'Late'],
        ['Ana Cruz', 'Operations', '-', '-', 'Absent'],
        ['Paolo Lim', 'IT', '07:55', '17:00', 'Present'],
        ['Grace Tan', 'Sales', '-', '-', 'On leave'],
    ],
    [4]
);
