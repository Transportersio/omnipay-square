<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class CardResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return $this->data['status'] === 'success';
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
        if(isset($this->data['card'])){
            if(!empty($this->data['card'])){
                return $this->data['card'];
            }
        }
        return null;
    }

    public function getCardReference()
    {
        if(isset($this->data['card'])){
            if(!empty($this->data['card'])){
                return $this->data['card']['id'];
            }
        }
        return null;
    }
}
