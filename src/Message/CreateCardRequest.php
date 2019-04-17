<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Create Credit Card Request
 */
class CreateCardRequest extends AbstractRequest
{
    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    public function getCard()
    {
        return $this->getParameter('card');
    }

    public function setCard($value)
    {
        return $this->setParameter('card', $value);
    }

    public function getCardholderName()
    {
        return $this->getParameter('cardholderName');
    }

    public function setCardholderName($value)
    {
        return $this->setParameter('cardholderName', $value);
    }

    public function getData()
    {
        $data = [];

        $data['customer_id'] = $this->getCustomerReference();
        $data['card_nonce'] = $this->getCard();
        $data['cardholder_name'] = $this->getCardholderName();

        return $data;
    }

    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\CustomersApi();

        try {
            $result = $api_instance->createCustomerCard($data['customer_id'], $data);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'card' => $result->getCard(),
                    'customerId' => $data['customer_id']
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating card: ', $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new CardResponse($this, $response);
    }
}
