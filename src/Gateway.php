<?php

namespace Omnipay\Square;

use Omnipay\Common\AbstractGateway;

/**
 * Square Gateway
 *
 */
class Gateway extends AbstractGateway
{

    public $square;

    public function getName()
    {
        return 'Square';
    }

    public function getDefaultParameters()
    {
        return [
            'accessToken' => '',
            'locationId' => '',
        ];
    }

    /**
     * Access Token getters and setters
     * @return mixed
     */

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    /**
     * location Id getters and setters
     * @return mixed
     */

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    /**
     * App Id getters and setters
     * @return mixed
     */

    public function getAppId()
    {
        return $this->getParameter('appId');
    }

    public function setAppId($value)
    {
        return $this->setParameter('appId', $value);
    }


    /**
     * Idempotency key getters and setters
     * @return mixed
     */

    public function getIdempotencyKey()
    {
        return $this->getParameter('idempotencyKey');
    }

    public function setIdempotencyKey($value)
    {
        return $this->setParameter('idempotencyKey', $value);
    }

    /**
     * Purchase request functions
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\ChargeRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\TransactionRequest', $parameters);
    }

    /**
     * Customer request functions
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createCustomer(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\CreateCustomerRequest', $parameters);
    }

    public function updateCustomer(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\UpdateCustomerRequest', $parameters);
    }

    public function fetchCustomer(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\FetchCustomerRequest', $parameters);
    }

    public function deleteCustomer(array $parameters = [])
    {
        return $this->createRequest('Omnipay\Square\Message\DeleteCustomerRequest', $parameters);
    }

    /**
     * Card request functions
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */

    public function createCard(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\CreateCardRequest', $parameters);
    }

    public function fetchCard(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\FetchCardRequest', $parameters);
    }

    public function deleteCard(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\DeleteCardRequest', $parameters);
    }


    /**
     * Transaction request functions
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function listTransactions(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\ListTransactionsRequest', $parameters);
    }


    /**
     * Refund request functions
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function listRefunds(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\ListRefundsRequest', $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Square\Message\RefundRequest', $parameters);
    }
}
