<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Refund Request
 */
class RefundRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://connect.squareup.com';
    protected $testEndpoint = 'https://connect.squareupsandbox.com';

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

    public function getEndpoint()
    {
        return $this->getTestMode() === true ? $this->testEndpoint : $this->liveEndpoint;
    }

    private function getApiInstance()
    {
        $api_config = new \SquareConnect\Configuration();
        $api_config->setHost($this->getEndpoint());
        $api_config->setAccessToken($this->getAccessToken());
        $api_client = new \SquareConnect\ApiClient($api_config);

        return new \SquareConnect\Api\RefundsApi($api_client);
    }

    public function getData()
    {
        $amountMoney = new \SquareConnect\Model\Money();
        $amountMoney->setAmount($this->getAmountInteger());
        $amountMoney->setCurrency($this->getCurrency());

        $data = new \SquareConnect\Model\RefundPaymentRequest();
        $data->setPaymentId($this->getTransactionId());
        $data->setIdempotencyKey($this->getIdempotencyKey());
        $data->setReason($this->getReason());
        $data->setAmountMoney($amountMoney);

        return $data;
    }

    public function sendData($data)
    {
        try {
            $api_instance = $this->getApiInstance();

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
                    'location_id' => $result->getRefund()->getLocationId(),
                    'transaction_id' => $result->getRefund()->getPaymentId(),
                    'tender_id' => $result->getRefund()->getOrderid(),
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
                'detail' => 'Exception when creating refund: ' . $e->getMessage(),
                'errors' => method_exists($e, 'getResponseBody') ? $e->getResponseBody()->errors : []
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new RefundResponse($this, $response);
    }
}
