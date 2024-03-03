<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Square\Environment;
use Square\SquareClient;

class FetchCardRequest extends AbstractRequest
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
        $data = [];

        $data['customer_id'] = $this->getCustomerReference();
        $data['card_id'] = $this->getCard();

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->retrieveCustomer($data['customer_id']);

            if ($errors = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $errors[0]->getCode(),
                    'detail' => $errors[0]->getDetail(),
                    'field' => $errors[0]->getField(),
                    'category' => $errors[0]->getCategory()
                ];
            } else {
                $cardId = $this->getCard();
                $cards = array_filter($result->getResult()->getCustomer()->getCards(), function ($cur) use ($cardId) {
                    return $cur->getId() == $cardId;
                });

                if ($cards === null || count($cards) == 0) {
                    throw new \Exception('Card not found!');
                }
                $response = [
                    'status' => 'success',
                    'card' => $cards[0]
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating customer: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new CardResponse($this, $response);
    }
}
