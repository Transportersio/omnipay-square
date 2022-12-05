<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;

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

    public function getEnvironment()
    {
        return $this->getTestMode() === true ? Environment::SANDBOX : Environment::PRODUCTION;
    }

    private function getApiInstance()
    {
        $api_client = new SquareClient([
            'accessToken' => $this->getAccessToken(),
            'environment' => $this->getEnvironment()
        ]);

        return $api_client->getRefundsApi();
    }

    public function getData()
    {
        $amountMoney = new \Square\Models\Money();
        $amountMoney->setAmount($this->getAmountInteger());
        $amountMoney->setCurrency($this->getCurrency());

        $data = new \Square\Models\RefundPaymentRequest($this->getIdempotencyKey(), $amountMoney, $this->getTransactionId());
        $data->setReason($this->getReason());

        return $data;
    }

    public function sendData($data)
    {
        try {
            $api_instance = $this->getApiInstance();

            $result = $api_instance->refundPayment($data);

            if ($errors = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $errors[0]->getCode(),
                    'detail' => $errors[0]->getDetail(),
                    'field' => $errors[0]->getField(),
                    'category' => $errors[0]->getCategory()
                ];
            } else {
                $response = [
                    'status' => $result->getResult()->getRefund()->getStatus(),
                    'id' => $result->getResult()->getRefund()->getId(),
                    'location_id' => $result->getResult()->getRefund()->getLocationId(),
                    'transaction_id' => $result->getResult()->getRefund()->getPaymentId(),
                    'tender_id' => $result->getResult()->getRefund()->getOrderid(),
                    'created_at' => $result->getResult()->getRefund()->getCreatedAt(),
                    'reason' => $result->getResult()->getRefund()->getReason(),
                    'amount' => $result->getResult()->getRefund()->getAmountMoney()->getAmount(),
                    'currency' => $result->getResult()->getRefund()->getAmountMoney()->getCurrency(),
                ];
                $processing_fee = $result->getResult()->getRefund()->getProcessingFee();
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
