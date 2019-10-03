<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Create Customer Request
 */
class CreateCustomerRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://connect.squareup.com';
    protected $testEndpoint = 'https://connect.squareupsandbox.com';

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

    public function setAddress(SquareConnect\Model\Address $value)
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

    public function getEndpoint()
    {
        return $this->getTestMode() === true ? $this->testEndpoint : $this->liveEndpoint;
    }

    private function getApiInstance()
    {
        $api_config = new \SquareConnect\Configuration();
        $api_config->setHost($this->getEndpoint());
        $api_config->setAccessToken($this->getAccessToken());
        $api_client = new \SquareConnect\ApiClient($api_config);

        return new \SquareConnect\Api\CustomersApi($api_client);
    }

    public function getData()
    {
        $data = [];

        $data['given_name'] = $this->getFirstName();
        $data['family_name'] = $this->getLastName();
        $data['company_name'] = $this->getCompanyName();
        $data['email_address'] = $this->getEmail();
        $data['reference_id'] = $this->getReferenceId();

        $data['address'] = $this->getAddress();

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
                    'customer' => $result->getCustomer()
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
