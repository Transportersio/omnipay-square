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
        return $this->data['status'] === 'APPROVED' || $this->data['status'] === 'PENDING';
    }

    public function getMessage()
    {

        return $this->data['detail'] ?? '';
    }
}
