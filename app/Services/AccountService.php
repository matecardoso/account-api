<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function reset(): void
    {
        Account::reset();
    }

    public function getBalance(string $accountId): int
    {
        $account = Account::findOrFail($accountId);
        return $account->balance;
    }

    public function deposit(string $accountId, int $amount): array
    {
        $account = Account::findOrCreate($accountId);

        $account->balance += $amount;
        $account->save();

        return $account->toArray();
    }

    public function withdraw(string $accountId, int $amount): array
    {
        $account = Account::findOrFail($accountId);

        $account->balance -= $amount;
        $account->save();

        return $account->toArray();
    }

    public function transfer(string $originId, string $destinationId, int $amount): array
    {
        $originAccount = Account::findOrFail($originId);
        $destinationAccount = Account::findOrCreate($destinationId);

        $originAccount->balance -= $amount;
        $destinationAccount->balance += $amount;

        $originAccount->save();
        $destinationAccount->save();

        return [
            'origin' => $originAccount->toArray(),
            'destination' => $destinationAccount->toArray(),
        ];
    }

}
