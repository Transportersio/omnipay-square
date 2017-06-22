<?php

namespace Omnipay\Square;

use Omnipay\Tests\GatewayTestCase;


class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setAccessToken('sandbox-sq0atb-ULI2NEKmXpkABJb4G17e6A');
        $this->gateway->setLocationId('CBASEDHRl0qakIMd91_K52yx7XcgAQ');

        $this->options = array(
            'transactionReference' => 'REF01',
            'currency' => 'USD',
            'items' => array(
                array(
                    'name' => 'Name',
                    'price' => '620.00',
                    'quantity' => 1
                )
            )
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
    }

}
