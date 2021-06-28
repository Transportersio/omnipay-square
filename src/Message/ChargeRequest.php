<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;

/**
 * Square Purchase Request
 */
class ChargeRequest extends AbstractRequest
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

    public function getVerificationToken()
    {
        return $this->getParameter('verificationToken');
    }

    public function setVerificationToken($verificationToken)
    {
        return $this->setParameter('verificationToken', $verificationToken);
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

        return $api_client->getPaymentsApi();
    }

    public function getData()
    {
        $amountMoney = new Money();
        $amountMoney->setAmount($this->getAmountInteger());
        $amountMoney->setCurrency($this->getCurrency());

        $sourceId = $this->getNonce() ?? $this->getCustomerCardId();
        $data = new CreatePaymentRequest($sourceId, $this->getIdempotencyKey(), $amountMoney);
        $data->setCustomerId($this->getCustomerReference());
        $data->setLocationId($this->getLocationId());
        $data->setNote($this->getNote());

        if ($this->getVerificationToken()) {
            $data->setVerificationToken($this->getVerificationToken());
        }

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
                    'transactionId' => $result->getResult()->getPayment()->getId(),
                    'referenceId' => $result->getResult()->getPayment()->getReferenceId(),
                    'created_at' => $result->getResult()->getPayment()->getCreatedAt(),
                    'orderId' => $result->getResult()->getPayment()->getOrderId()
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
