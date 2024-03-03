<?php

namespace Omnipay\Square\Message;

use Omnipay\Tests\TestCase;

class WebPaymentRequestTest extends TestCase
{
    protected function setUp(): void {
        $this->request = new WebPaymentRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize(
            array(
                'locationId' => getenv('SQUARE_LOCATION_ID'),
                'transactionReference' => 'REF01',
                'currency' => 'USD',
                'items' => array(
                    array(
                        'name' => 'Name',
                        'price' => '620.00',
                        'quantity' => 1
                    )
                )
            )
        );
    }

    public function testGetData()
    {
        $this->request->initialize(
            array(
                'locationId' => getenv('SQUARE_LOCATION_ID'),
                'transactionReference' => 'REF01',
                'currency' => 'USD',
                'items' => array(
                    array(
                        'name' => 'Name',
                        'price' => '620.00',
                        'quantity' => 1
                    )
                )
            )
        );

        $this->request->getData();
    }

    public function testGetDataTestMode()
    {
        $data = $this->request->getData();
    }
}
