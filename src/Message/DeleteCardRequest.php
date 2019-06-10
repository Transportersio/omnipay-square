<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 17/04/2019
 * Time: 9:44 AM
 */

namespace Omnipay\Square\Message;


use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use SquareConnect;

class DeleteCardRequest extends AbstractRequest
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

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getCardReference()
    {
        return $this->getParameter('cardReference');
    }

    public function setCardReference($value)
    {
        return $this->setParameter('cardReference', $value);
    }


    public function getData()
    {
        $data = [];

        $data['customer_id'] = $this->getCustomerReference();
        $data['card_id'] = $this->getCardReference();

        return $data;
    }

    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\CustomersApi();

        try {
            $result = $api_instance->deleteCustomerCard($data['customer_id'], $data['card_id']);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
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