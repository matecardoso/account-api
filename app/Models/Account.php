<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Account
{
    public string $id;
    public int $balance;

    public function __construct(string $id, int $balance = 0)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    public static function reset(): void
    {
        Cache::flush();
    }

    public static function findOrCreate(string $id): Account
    {
        $account = Cache::get($id);
        if (!$account) {
            return self::create($id);
        }
        return new self($account['id'], $account['balance']);
    }

    public static function findOrFail(string $id): Account
    {
        $account = Cache::get($id);
        if (!$account) {
            throw new \Exception('Account not found', 404);
        }
        return new self($account['id'], $account['balance']);
    }

    public static function create(string $id, int $balance = 0): Account
    {
        $account = new self($id, $balance);
        $account->save();
        return $account;
    }

    public function save(): void
    {
        Cache::put($this->id, $this->toArray());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
        ];
    }
}
