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

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getCardNonce()
    {
        return $this->getParameter('cardNonce');
    }

    public function setCardNonce($value)
    {
        return $this->setParameter('cardNonce', $value);
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

        $data['customer_id'] = $this->getCustomerId();
        $data['card_nonce'] = $this->getCardNonce();
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
                'detail' => 'Exception when creating card: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new CreateCardResponse($this, $response);
    }
}
