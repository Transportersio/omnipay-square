<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Apis\CardsApi;
use Square\Environment;
use Square\Models\Card;
use Square\Models\CreateCardRequest as CreateSquareCardRequest;
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

        return $api_client->getCardsApi();
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId') ?? null;
    }

    public function setLocationId(string $value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getData()
    {
        $idempotencyKey = uniqid();
        $sourceId = $this->getCard(); // Card nonce
        $card = new Card;
        $card->setCustomerId($this->getCustomerReference());

        $data = new CreateSquareCardRequest($idempotencyKey, $sourceId, $card);

        return $data;
    }

    public function sendData($data)
    {
        /** @var CardsApi $api_instance */
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->createCard($data);

            if ($errors = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $errors[0]->getCode(),
                    'detail' => $errors[0]->getDetail(),
                    'field' => $errors[0]->getField(),
                    'category' => $errors[0]->getCategory()
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
