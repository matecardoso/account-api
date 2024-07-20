<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountEventTest extends TestCase
{
    public function testScenario()
    {
        $this->resetState();
        $this->getBalanceForNonExistingAccount();
        $this->createAccountWithInitialBalance();
        $this->depositIntoExistingAccount();
        $this->getBalanceForExistingAccount();
        $this->withdrawFromNonExistingAccount();
        $this->withdrawFromExistingAccount();
        $this->transferFromExistingAccount();
        $this->transferFromNonExistingAccount();
    }

    private function resetState()
    {
        $this->post('/reset')
            ->assertResponseOk();
    }

    private function getBalanceForNonExistingAccount()
    {
        $this->get('/balance?account_id=1234')
            ->seeStatusCode(404);
    }

    private function createAccountWithInitialBalance()
    {
        $this->post('/event', ['type' => 'deposit', 'destination' => '100', 'amount' => 10])
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'destination' => [
                    'id' => '100',
                    'balance' => 10,
                ],
            ]);
    }

    private function depositIntoExistingAccount()
    {
        $this->post('/event', ['type' => 'deposit', 'destination' => '100', 'amount' => 10])
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'destination' => [
                    'id' => '100',
                    'balance' => 20,
                ],
            ]);
    }

    private function getBalanceForExistingAccount()
    {
        $response = $this->get('/balance?account_id=100')
            ->seeStatusCode(200);

        $this->assertEquals('20', $response->response->getContent());
    }

    private function withdrawFromNonExistingAccount()
    {
        $this->post('/event', ['type' => 'withdraw', 'origin' => '200', 'amount' => 10])
            ->seeStatusCode(404);
    }

    private function withdrawFromExistingAccount()
    {
        $this->post('/event', ['type' => 'withdraw', 'origin' => '100', 'amount' => 5])
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'origin' => [
                    'id' => '100',
                    'balance' => 15,
                ],
            ]);
    }

    private function transferFromExistingAccount()
    {
        $this->post('/event', ['type' => 'transfer', 'origin' => '100', 'amount' => 15, 'destination' => '300'])
            ->seeStatusCode(201)
            ->seeJsonEquals([
                'origin' => [
                    'id' => '100',
                    'balance' => 0,
                ],
                'destination' => [
                    'id' => '300',
                    'balance' => 15,
                ],
            ]);
    }

    private function transferFromNonExistingAccount()
    {
        $this->post('/event', ['type' => 'transfer', 'origin' => '200', 'amount' => 15, 'destination' => '300'])
            ->seeStatusCode(404);
    }
}
