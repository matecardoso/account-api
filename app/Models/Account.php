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

    public static function findOrCreate(string $id): Account
    {
        return self::$accounts[$id] ?? self::create($id);
    }

    public static function findOrFail(string $id): Account
    {
        if (!isset(self::$accounts[$id])) {
            throw new \Exception('Account not found', 404);
        }
        return self::$accounts[$id];
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
