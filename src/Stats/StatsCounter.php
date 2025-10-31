<?php
namespace App\Stats;

use MongoDB\Client;

final class StatsCounter
{
    private \MongoDB\Collection $col;

    public function __construct(string $mongoUri, string $dbName = 'namaste_test2')
    {
        $client = new Client($mongoUri, ['retryWrites' => true, 'w' => 'majority']);
        $db = $client->selectDatabase($dbName ?: 'namaste_test2');
        $this->col = $db->selectCollection('counters');
    }

    public function incConfirmed(int $delta = 1): void
    {
        $this->col->updateOne(
            ['_id' => 'reservations_total'],
            [
                '$setOnInsert' => ['_id' => 'reservations_total'],
                '$inc' => ['confirmed' => $delta, 'net' => $delta],
            ],
            ['upsert' => true]
        );
    }

    public function incCancelled(int $delta = 1): void
    {
        $this->col->updateOne(
            ['_id' => 'reservations_total'],
            [
                '$setOnInsert' => ['_id' => 'reservations_total'],
                '$inc' => ['cancelled' => $delta, 'net' => -$delta],
            ],
            ['upsert' => true]
        );
    }

    public function getTotals(): array
    {
        $doc = $this->col->findOne(['_id' => 'reservations_total']) ?? [];
        return [
            'confirmed' => (int)($doc['confirmed'] ?? 0),
            'cancelled' => (int)($doc['cancelled'] ?? 0),
            'net'       => (int)($doc['net'] ?? 0),
        ];
    }
}
