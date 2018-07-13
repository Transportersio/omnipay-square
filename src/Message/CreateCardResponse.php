<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class CreateCardResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        if ($this->data['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    public function getErrorDetail()
    {
        return $this->data['detail'];
    }

    public function getErrorCode()
    {
        return $this->data['code'];
    }

    public function getCard()
    {
        return $this->data['card'];
    }

    public function getCardReference()
    {
        return $this->data['card']['id'];
    }
}
