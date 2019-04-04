<?php

namespace Omnipay\Square;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $options;
    
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccessToken('sandbox-sq0atb-ULI2NEKmXpkABJb4G17e6A');
        $this->gateway->setLocationId('CBASEDHRl0qakIMd91_K52yx7XcgAQ');
        $this->gateway->setIdempotencyKey(uniqid());

        $this->options = [
            'transactionReference'=> 'REF01',
            'customer_id'         => uniqid(),
            'card_nonce'          => 'fake-card-nonce-ok',
            'customer_card_id'    => 'fake-customer-card-id-ok',
            'currency'            => 'USD',
            'amount'              => '620.00',
            'items'               => [
                [
                    'name'    => 'Name',
                    'price'   => '620.00',
                    'quantity'=> 1
                ]
            ]
        ];
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
    }
}
