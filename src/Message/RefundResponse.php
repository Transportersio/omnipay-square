<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Square Refund Response
 */
class RefundResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return $this->data['status'] === 'APPROVED';
    }

    public function isPending()
    {
        return $this->data['status'] === 'PENDING';
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

        if (!empty($this->data['errors'])) {
            foreach ($this->data['errors'] as $error) {
                $message .= $error->detail . ' ';
            }
        }

        if ($this->isPending()) {
            return $message .= 'Refund pending';
        }

        return $message;
    }
}
