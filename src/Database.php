<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

final class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            getenv('DB_HOST'),
            getenv('DB_PORT'),
            getenv('DB_NAME')
        );

        for ($attempt = 1; $attempt <= 10; $attempt++) {
            try {
                $this->pdo = new PDO(
                    $dsn,
                    (string) getenv('DB_USER'),
                    (string) getenv('DB_PASSWORD'),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                return;
            } catch (PDOException $exception) {
                if ($attempt === 10) {
                    throw $exception;
                }
                sleep(1);
            }
        }
    }

    public function check(): void
    {
        $this->pdo->query('SELECT 1');
    }
}