<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountEventTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testResetState()
    {
        $this->post('/reset')
            ->assertResponseOk();
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetBalanceForNonExistingAccount()
    {
        $response = $this->get('/balance?account_id=1234')
                        ->seeStatusCode(404);

        $this->assertEquals(0, $response->response->getContent());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateAccountWithInitialBalance()
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

    /**
     * @runInSeparateProcess
     */
    public function testDepositIntoExistingAccount()
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

    /**
     * @runInSeparateProcess
     */
    public function testGetBalanceForExistingAccount()
    {
        $response = $this->get('/balance?account_id=100')
                        ->seeStatusCode(200);

        $this->assertEquals(20, $response->response->getContent());
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithdrawFromNonExistingAccount()
    {
        $response = $this->post('/event', ['type' => 'withdraw', 'origin' => '200', 'amount' => 10])
                        ->seeStatusCode(404);

        $this->assertEquals(0, $response->response->getContent());
    }

    /**
     * @runInSeparateProcess
     */
    public function testWithdrawFromExistingAccount()
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

    /**
     * @runInSeparateProcess
     */
    public function testTransferFromExistingAccount()
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

    /**
     * @runInSeparateProcess
     */
    public function testTransferFromNonExistingAccount()
    {
        $response = $this->post('/event', ['type' => 'transfer', 'origin' => '200', 'amount' => 15, 'destination' => '300'])
                        ->seeStatusCode(404);

        $this->assertEquals(0, $response->response->getContent());
    }
}
