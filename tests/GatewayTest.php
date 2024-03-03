<?php

namespace Omnipay\Square;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAppId(getenv('SQUARE_APP_ID'));
        $this->gateway->setAccessToken(getenv('SQUARE_ACCESS_TOKEN'));
        $this->gateway->setTestMode(true);
        $this->gateway->setLocationId(getenv('SQUARE_LOCATION_ID'));
        $this->gateway->setIdempotencyKey(uniqid());

        $this->options = [
            'token' => 'CASH',
            'transactionReference' => 'REF01',
//            'customer_id' => uniqid(),
            'card_nonce' => 'fake-card-nonce-ok',
            'customer_card_id' => 'fake-customer-card-id-ok',
            'currency' => 'USD',
            'amount' => '620.00',
            'items' => [
                [
                    'name' => 'Name',
                    'price' => '620.00',
                    'quantity' => 1,
                ],
            ],
            'cashAmountInteger' => '62000',
            'cashCurrency' => 'USD',
        ];
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
    }
}