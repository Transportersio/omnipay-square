<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;

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

    public function getEnvironment()
    {
        return $this->getTestMode() === true ? Environment::SANDBOX : Environment::PRODUCTION;
    }

    private function getApiInstance()
    {
        $api_client = new SquareClient([
            'accessToken' => $this->getAccessToken(),
            'environment' => $this->getEnvironment()
        ]);

        return $api_client->getCheckoutApi();
    }

    public function getData()
    {
        $items = $this->getItems();

        $items_list = [];

        if (!empty($items) && count($items) > 0) {
            foreach ($items as $index => $item) {
                $base_price_money = new \Square\Models\Money();
                $base_price_money->setAmount($item->getPrice() * 100);
                $base_price_money->setCurrency($this->getCurrency());

                $items_list[$index] = new \Square\Models\OrderLineItem((string) $item->getQuantity());
                $items_list[$index]->setName($item->getName());
                $items_list[$index]->setBasePriceMoney($base_price_money);
            }
        }

        $order = new \Square\Models\Order($this->getLocationId());
        $order->setReferenceId($this->getTransactionReference());
        $order->setLineItems($items_list);

        $order_request = new \Square\Models\CreateOrderRequest();
        $order_request->setOrder($order);

        $data = new \Square\Models\CreateCheckoutRequest(uniqid(), $order_request);
        $data->setAskForShippingAddress(false);
        $data->setRedirectUrl($this->getReturnUrl());

        return $data;
    }

    public function sendData($data)
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->createCheckout($this->getLocationId(), $data);
            $result = $result->getResult()->getCheckout();
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
