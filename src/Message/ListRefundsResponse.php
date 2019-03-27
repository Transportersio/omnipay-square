<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
* Square List Refunds Response
*/
class ListRefundsResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        if ($this->data['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    public function getMessage()
    {
        $message = '';
        if (array_key_exists('code', $this->data) && strlen($this->data['code'])) {
            $message .= $this->data['code'] . ': ';
        }
        if (array_key_exists('error', $this->data) && strlen($this->data['error'])) {
            $message .= $this->data['error'];
        }
        return $message;
    }
}
