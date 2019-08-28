<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class ChargeRequest extends AbstractRequest
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

    public function getCheckoutId()
    {
        return $this->getParameter('checkoutId');
    }

    public function setCheckoutId($value)
    {
        return $this->setParameter('ReceiptId', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getIdempotencyKey()
    {
        return $this->getParameter('idempotencyKey');
    }

    public function setIdempotencyKey($value)
    {
        return $this->setParameter('idempotencyKey', $value);
    }

    public function getNonce()
    {
        return $this->getParameter('nonce');
    }

    public function setNonce($value)
    {
        return $this->setParameter('nonce', $value);
    }

    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }


    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    public function getCustomerCardId()
    {
        return $this->getParameter('customerCardId');
    }

    public function setCustomerCardId($value)
    {
        return $this->setParameter('customerCardId', $value);
    }

    public function getReferenceId()
    {
        return $this->getParameter('referenceId');
    }

    public function setReferenceId($value)
    {
        return $this->setParameter('referenceId', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getNote()
    {
        return $this->getParameter('note');
    }

    public function setNote($value)
    {
        return $this->setParameter('note', $value);
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

        return new \SquareConnect\Api\PaymentsApi($api_client);
    }

    public function getData()
    {
        $amountMoney = new \SquareConnect\Model\Money();
        $amountMoney->setAmount($this->getAmountInteger());
        $amountMoney->setCurrency($this->getCurrency());

        $data = new SquareConnect\Model\CreatePaymentRequest();
        $data->setSourceId($this->getNonce() ?? $this->getCustomerCardId());
        $data->setCustomerId($this->getCustomerReference());
        $data->setIdempotencyKey($this->getIdempotencyKey());
        $data->setAmountMoney($amountMoney);
        $data->setLocationId($this->getLocationId());

        return $data;
    }

    public function sendData($data)
    {
        try {
            $api_instance = $this->getApiInstance();

            $result = $api_instance->createPayment($data);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $response = [
                    'status' => 'success',
                    'transactionId' => $result->getPayment()->getId(),
                    'referenceId' => $result->getPayment()->getReferenceId(),
                    'created_at' => $result->getPayment()->getCreatedAt(),
                    'orderId' => $result->getPayment()->getOrderId()
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new ChargeResponse($this, $response);
    }
}
