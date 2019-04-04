<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Square Purchase Response
 */
class ListTransactionsResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return $this->data['status'] === 'success';
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
