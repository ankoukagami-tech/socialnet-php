<?php declare(strict_types=1);

// Copy to includes/config.local.php on the server and adjust credentials.
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'socialnet',
    'user' => 'socialnet_user',
    'pass' => 'CHANGE_ME',
    'charset' => 'utf8mb4',
  ],
  'student' => [
    'name' => 'YOUR_NAME',
    'number' => 'YOUR_STUDENT_NUMBER',
  ],
];

