<?php

namespace Omnipay\Judopay;

use Omnipay\Common\AbstractGateway;
use Judopay;

/**
 * Judopay Gateway
 *
 */
class Gateway extends AbstractGateway
{

    public $judopay;

    public function getName()
    {
        return 'JudoPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'apiToken' => '',
            'apiSecret' => '',
            'judoId' => '',
            'testMode' => false
        );
    }

    public function getApiToken()
    {
        return $this->getParameter('apiToken');
    }

    public function setApiToken($value)
    {
        return $this->setParameter('apiToken', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getJudoId()
    {
        return $this->getParameter('judoId');
    }

    public function setJudoId($value)
    {
        return $this->setParameter('judoId', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\PreAuthorizationRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\WebPaymentRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\TransactionRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\VoidRequest', $parameters);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\RegisteringCardRequest', $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Judopay\Message\SaveCardRequest', $parameters);
    }
}
