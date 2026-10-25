<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$envFile = $root . '/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value);
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

session_start();

require $root . '/vendor/autoload.php';