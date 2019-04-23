<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class WebPaymentRequest extends AbstractRequest
{
    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getData()
    {
        $items = $this->getItems();

        $items_list = [];

        if (!empty($items) && count($items) > 0) {
            foreach ($items as $index => $item) {
                $items_list[$index] = new SquareConnect\Model\OrderLineItem(
                    [
                        'name' => $item->getName(),
                        'quantity' => (string) $item->getQuantity(),
                        'base_price_money' => new SquareConnect\Model\Money(
                            [
                                'amount' => $item->getPrice() * 100,
                                'currency' => $this->getCurrency()
                            ]
                        )
                    ]
                );
            }
        }

        $data_array = [
            'idempotency_key' => uniqid(),
            'order' => new SquareConnect\Model\Order([
                'reference_id' => $this->getTransactionReference(),
                'line_items' => $items_list
            ]),
            'ask_for_shipping_address' => false,
            'redirect_url' => $this->getReturnUrl()
        ];

        $data = new \SquareConnect\Model\CreateCheckoutRequest($data_array);

        return $data;
    }

    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\CheckoutApi();

        try {
            $result = $api_instance->createCheckout($this->getLocationId(), $data);
            $result = $result->getCheckout();
            $response = [
                'id' => $result->getId(),
                'checkout_url' => $result->getCheckoutPageUrl()
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating web payment request: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new WebPaymentResponse($this, $response);
    }
}
