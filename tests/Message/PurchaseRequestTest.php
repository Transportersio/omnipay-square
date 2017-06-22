<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Tests\TestCase;

class WebPaymentRequestTest extends TestCase
{
    protected function setUp()
    {
        $this->request = new WebPaymentRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'yourConsumerReference' => '12345',
                'yourPaymentReference' => '12345',
                'yourPaymentMetaData' => array(),
                'amount' => '10.00'
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'judoId' => 'jwmXGbpb87xvDM4B',
                'yourConsumerReference' => '12345',
                'yourPaymentReference' => '12345',
                'amount' => '10.00',
                'currency' => 'GBP'
            )
        );

        $this->request->getData();
    }

    public function testGetDataTestMode()
    {
        $this->request->setTestMode(true);

        $data = $this->request->getData();
    }
}
