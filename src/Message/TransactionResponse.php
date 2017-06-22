<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Judopay Purchase Response
 */
class TransactionResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return true;
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
}
