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


    public function incCreated(int $delta = 1): void
    {
        $this->col->updateOne(
            ['_id' => 'sessions_total'],
            [
                '$setOnInsert' => ['_id' => 'sessions_total'],
                '$inc' => ['total' => $delta, 'net' => $delta],
            ],
            ['upsert' => true]
        );
    }

    public function decCreated(int $delta = 1): void
    {
        $this->col->updateOne(
            ['_id' => 'sessions_total'],
            [
                '$setOnInsert' => ['_id' => 'sessions_total'],
                '$inc' => ['cancelled' => $delta, 'net' => -$delta],
            ],
            ['upsert' => true]
        );
    }


    public function getTotals(): array
    {
        $res = $this->col->findOne(['_id' => 'reservations_total']) ?? [];
        $ses = $this->col->findOne(['_id' => 'sessions_total']) ?? [];

        return [
            'confirmed' => (int)($res['confirmed'] ?? 0),
            'cancelled' => (int)($res['cancelled'] ?? 0),
            'net'       => (int)($res['net'] ?? 0),

            'sessions_total'     => (int)($ses['total'] ?? 0),
            'sessions_cancelled' => (int)($ses['cancelled'] ?? 0),
            'sessions_net'       => (int)($ses['net'] ?? 0),
        ];
    }
}
