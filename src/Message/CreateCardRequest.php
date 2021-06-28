<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;

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

        return $api_client->getCustomersApi();
    }

    public function getData()
    {
        $data = new \Square\Models\CreateCustomerCardRequest($this->getCard());
        $data->setCardholderName($this->getCardholderName());

        return $data;
    }

    public function sendData($data)
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->createCustomerCard($this->getCustomerReference(), $data);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'card' => $result->getResult()->getCard(),
                    'customerId' => $this->getCustomerReference()
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
        return $this->response = new CardResponse($this, $response);
    }
}
