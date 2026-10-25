<?php

declare(strict_types=1);

namespace App;

use MongoDB\Client;

final class MongoStore
{
    public function check(): void
    {
        $client = new Client((string) getenv('MONGO_URI'));
        $database = $client->selectDatabase((string) getenv('MONGO_DB'));
        $database->command(['ping' => 1]);
    }
}