<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class ChargeResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return $this->data['status'] === 'success';
    }

    public function isRedirect()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return "";
    }

    public function getRedirectMethod()
    {
        return "";
    }

    public function getRedirectData()
    {
        return [];
    }

    public function getTransactionId()
    {
        return $this->data['transactionId'] ?? null;
    }

    public function getTenders()
    {
        return $this->data['tenders'] ?? null;
    }

    public function getOrderId()
    {
        return $this->data['orderId'] ?? null;
    }

    public function getCreatedAt()
    {
        return $this->data['created_at'] ?? null;
    }

    public function getReferenceId()
    {
        return $this->data['referenceId'] ?? null;
    }

    public function getMessage()
    {
        $message = '';
        if (isset($this->data['code'])) {
            $message .= $this->data['code'] . ': ';
        }

        return $message . ($this->data['detail'] ?? '');
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return $this->getTransactionId();
    }

    /**
     * Get the tender id that is used for processing refunds
     *
     * @return null|string
     */
    public function getBillingId()
    {
        return $this->getTenders()[0]['id'] ?? null;
    }
}
