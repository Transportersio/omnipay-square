<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class   TransactionResponse extends AbstractResponse implements RedirectResponseInterface
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
        return true;
    }

    public function getRedirectUrl()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }

    public function getTransactionReference() {
        if($this->isSuccessful())  {
            return $this->data['transactionId'];
        }

        return null;
    }

	public function getMessage() {
		return $this->data['message'];
	}
}
