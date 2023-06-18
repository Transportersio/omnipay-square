<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Square\Environment;
use Square\Models\Address;
use Square\SquareClient;

class UpdateCustomerRequest extends AbstractRequest
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

    public function setNickName($value)
    {
        return $this->setParameter('nickName', $value);
    }

    public function getNickName()
    {
        return $this->getParameter('nickName');
    }

    public function getPhoneNumber()
    {
        return $this->getParameter('phoneNumber');
    }

    public function setPhoneNumber($value)
    {
        return $this->setParameter('phoneNumber', $value);
    }

    public function getNote()
    {
        return $this->getParameter('note');
    }

    public function setNote($value)
    {
        return $this->setParameter('note', $value);
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

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = new \Square\Models\UpdateCustomerRequest();
        $data->setGivenName($this->getFirstName());
        $data->setFamilyName($this->getLastName());
        $data->setCompanyName($this->getCompanyName());
        $data->setEmailAddress($this->getEmail());

        $data->setAddress($this->getAddress());
        $data->setNickname($this->getEmail());
        $data->setPhoneNumber($this->getPhoneNumber());
        $data->setReferenceId($this->getReferenceId());
        $data->setNote($this->getNote());

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param mixed $data The data to send
     * @return ResponseInterface
     */

    public function sendData($data)
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->updateCustomer($this->getCustomerReference(), $data);

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
                    'customer' => $result->getResult()->getCustomer()
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when updating customer: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new CustomerResponse($this, $response);
    }
}
