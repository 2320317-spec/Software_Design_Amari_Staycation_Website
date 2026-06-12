<?php
/**
 * TEMPLATE — copy this file to "secrets.php" and fill in real values.
 * secrets.php is gitignored; this example IS committed so teammates know the shape.
 *
 *  - Local (XAMPP): if you don't create secrets.php, db-config.php falls back to
 *    root / empty password / amari_db automatically. To send email locally you
 *    still need secrets.php with a real Gmail App Password in the mail block.
 *  - Production (Hostinger): create secrets.php with your hPanel DB credentials.
 */
return [
    'db' => [
        'host' => 'localhost',
        'user' => 'CHANGE_ME',     // e.g. u123456_amari
        'pass' => 'CHANGE_ME',
        'name' => 'CHANGE_ME',     // e.g. u123456_amari
    ],
    'mail' => [
        'user'      => 'amaristaycation08@gmail.com',
        'pass'      => 'CHANGE_ME', // Gmail App Password (16 chars)
        'from_name' => 'Amari Alabang',
    ],
];
