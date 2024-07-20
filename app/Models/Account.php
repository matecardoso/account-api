<?php

namespace App\Models;

class Account
{
    public string $id;
    public int $balance;

    private static $accounts = [];

    public function __construct(string $id, int $balance = 0)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public static function reset(): void
    {
        self::$accounts = [];
    }

    public static function find(string $id): ?Account
    {
        return self::$accounts[$id] ?? null;
    }

    public static function create(string $id, int $balance = 0): Account
    {
        $account = new self($id, $balance);
        self::$accounts[$id] = $account;
        return $account;
    }

    public function save(): void
    {
        self::$accounts[$this->id] = $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
        ];
    }
}
