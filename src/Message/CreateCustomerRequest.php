<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;

/**
 * Square Create Customer Request
 */
class CreateCustomerRequest extends AbstractRequest
{
    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }

    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }

    public function getCompanyName()
    {
        return $this->getParameter('companyName');
    }

    public function setCompanyName($value)
    {
        return $this->setParameter('companyName', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function setAddress(\Square\Models\Address $value)
    {
        return $this->setParameter('address', $value);
    }

    public function getAddress()
    {
        return $this->getParameter('address');
    }

    public function getReferenceId()
    {
        return $this->getParameter('referenceId');
    }

    public function setReferenceId($value)
    {
        return $this->setParameter('referenceId', $value);
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
        $data = new \Square\Models\CreateCustomerRequest();
        $data->setGivenName($this->getFirstName());
        $data->setFamilyName($this->getLastName());
        $data->setCompanyName($this->getCompanyName());
        $data->setEmailAddress($this->getEmail());
        $data->setReferenceId($this->getReferenceId());
        $data->setAddress($this->getAddress());

        return $data;
    }

    public function sendData($data)
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->createCustomer($data);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'customer' => $result->getResult()->getCustomer()
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
        return $this->response = new CustomerResponse($this, $response);
    }
}
