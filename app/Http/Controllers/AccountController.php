<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class AccountController extends BaseController
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function reset(): String
    {
        $this->accountService->reset();
        return 'OK';
    }

    public function balance(Request $request): JsonResponse
    {
        $accountId = $request->input('account_id');
        try {
            $balance = $this->accountService->getBalance($accountId);
        } catch (\Exception $e) {
            return response()->json(0, 404);
        }

        return response()->json($balance, 200);
    }

    public function event(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $destination = $request->input('destination');
        $origin = $request->input('origin');
        $amount = $request->input('amount');

        try {
            switch ($type) {
                case 'deposit':
                    return $this->handleDeposit($destination, $amount);
                case 'withdraw':
                    return $this->handleWithdraw($origin, $amount);
                case 'transfer':
                    return $this->handleTransfer($origin, $destination, $amount);
                default:
                    return response()->json(0, 400);
            }
        } catch (\Exception $e) {
            return response()->json(0, 404);
        }
    }

    private function handleDeposit(string $destination, int $amount): JsonResponse
    {
        $account = $this->accountService->deposit($destination, $amount);
        return response()->json(['destination' => $account], 201);
    }

    private function handleWithdraw(string $origin, int $amount): JsonResponse
    {
        try {
            $account = $this->accountService->withdraw($origin, $amount);
        } catch (\Exception $e) {
            return response()->json(0, 404);
        }

        return response()->json(['origin' => $account], 201);
    }

    private function handleTransfer(string $origin, string $destination, int $amount): JsonResponse
    {
        try {
            $result = $this->accountService->transfer($origin, $destination, $amount);
        } catch (\Exception $e) {
            return response()->json(0, 404);
        }

        return response()->json($result, 201);
    }
}
