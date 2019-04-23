<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Refund Request
 */
class RefundRequest extends AbstractRequest
{
    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId');
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getIdempotencyKey()
    {
        return $this->getParameter('idempotencyKey');
    }

    public function setIdempotencyKey($value)
    {
        return $this->setParameter('idempotencyKey', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getTenderId()
    {
        return $this->getParameter('tenderId');
    }

    public function setTenderId($value)
    {
        return $this->setParameter('tenderId', $value);
    }

    public function getReason()
    {
        return $this->getParameter('reason');
    }

    public function setReason($value)
    {
        return $this->setParameter('reason', $value);
    }

    public function getData()
    {
        $data = [];

        $data['location_id'] = $this->getLocationId();
        $data['transaction_id'] = $this->getTransactionId();
        $data['body'] = new \SquareConnect\Model\CreateRefundRequest();
        $data['body']->setIdempotencyKey($this->getIdempotencyKey());
        $data['body']->setTenderId($this->getTenderId());
        $data['body']->setReason($this->getReason());
        $money = new \SquareConnect\Model\Money();
        $money->setAmount($this->getAmountInteger());
        $money->setCurrency($this->getCurrency());
        $data['body']->setAmountMoney($money);

        return $data;
    }

    public function sendData($data)
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {
            $result = $api_instance->createRefund($data['location_id'], $data['transaction_id'], $data['body']);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => $result->getRefund()->getStatus(),
                    'id' => $result->getRefund()->getId(),
                    'location_id' => $result->getRefund()->getLocationId(),
                    'transaction_id' => $result->getRefund()->getTransactionId(),
                    'tender_id' => $result->getRefund()->getTenderId(),
                    'created_at' => $result->getRefund()->getCreatedAt(),
                    'reason' => $result->getRefund()->getReason(),
                    'amount' => $result->getRefund()->getAmountMoney()->getAmount(),
                    'currency' => $result->getRefund()->getAmountMoney()->getCurrency(),
                ];
                $processing_fee = $result->getRefund()->getProcessingFeeMoney();
                if (!empty($processing_fee)) {
                    $response['processing_fee'] = $processing_fee->getAmount();
                }
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating refund: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new RefundResponse($this, $response);
    }
}
