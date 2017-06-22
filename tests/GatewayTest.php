<?php

namespace Omnipay\Judopay;

use Omnipay\Tests\GatewayTestCase;


class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiToken('jwmXGbpb87xvDM4B');
        $this->gateway->setApiSecret('601dc0a93d2752f5041bdb9a53dc1bf0b4e8ef0f1b03f737416fcf3be1a20b7d');
        $this->gateway->setJudoId('100826-205');
        $this->gateway->setTestMode(true);

        $this->options = array(
            'yourConsumerReference' => '12345',
            'yourPaymentReference' => '12345',
            'yourPaymentMetaData' => array(),
            'amount' => '10.00'
        );
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
    }

}
