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
        if ($this->data['status'] == 'success') {
            return true;
        } else {
            return false;
        }
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
        return array();
    }

    public function getTransactionId()
    {
        return $this->data['transactionId'];
    }


    public function getTenders()
    {
        return $this->data['tenders'];
    }

    public function getOrderId()
    {
        return $this->data['orderId'];
    }

    public function getCreatedAt()
    {
        return $this->data['created_at'];
    }

    public function getReferenceId()
    {
        return $this->data['referenceId'];
    }

    public function getMessage()
    {
        $message = '';
        if (strlen($this->data['code'])) {
            $message .= $this->data['code'] . ': ';
        }
        return $message . $this->data['error'];
    }
}
