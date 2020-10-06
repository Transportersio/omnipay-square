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

        $data['idempotency_key'] = uniqid();
        $data['payment_id'] = $this->getTransactionId();
        $data['reason'] = $this->getReason();
        $data['amount_money'] = [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency()
        ];

        return $data;
    }

    public function sendData($data)
    {
        $defaultApiConfig = new \SquareConnect\Configuration();
        $defaultApiConfig->setAccessToken($this->getAccessToken());

        if($this->getParameter('testMode')) {
            $defaultApiConfig->setHost("https://connect.squareupsandbox.com");
        }

        $defaultApiClient = new \SquareConnect\ApiClient($defaultApiConfig);
        $api_instance = new SquareConnect\Api\RefundsApi($defaultApiClient);

        try {
            $result = $api_instance->refundPayment($data);

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
                    'transaction_id' => $result->getRefund()->getPaymentId(),
                    'created_at' => $result->getRefund()->getCreatedAt(),
                    'reason' => $result->getRefund()->getReason(),
                    'amount' => $result->getRefund()->getAmountMoney()->getAmount(),
                    'currency' => $result->getRefund()->getAmountMoney()->getCurrency(),
                ];
                $processing_fee = $result->getRefund()->getProcessingFee();
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
