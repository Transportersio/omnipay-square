<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Square Purchase Response
 */
class CustomerResponse extends AbstractResponse implements RedirectResponseInterface
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

    public function getCustomer()
    {
        if(isset($this->data['customer'])){
            if(!empty($this->data['customer'])){
                return $this->data['customer'];
            }
        }
        return null;
    }

    public function getCustomerReference(){
        if(isset($this->data['customer'])){
            if(!empty($this->data['customer'])){
                return $this->data['customer']['id'];
            }
        }
        return null;
    }

    public function getCustomerCards(){
        if(isset($this->data['customer'])){
            if(!empty($this->data['customer'])){
                return $this->data['customer']['cards'];
            }
        }
        return null;
    }
}
