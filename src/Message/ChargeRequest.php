<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

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

    public function getCustomerId()
    {
        return $this->getParameter('customerId');
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
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

    public function getData()
    {
        $data = [];

        $data['idempotency_key'] = $this->getIdempotencyKey();
        $data['amount_money'] = [
            'amount' => $this->getAmountInteger(),
            'currency' => $this->getCurrency()
        ];
        $data['card_nonce'] = $this->getNonce();
        $data['customer_id'] = $this->getCustomerId();
        $data['customer_card_id'] = $this->getCustomerCardId();
        $data['reference_id'] = $this->getReferenceId();
        $data['order_id'] = $this->getOrderId();
        $data['note'] = $this->getNote();

        return $data;
    }

    public function sendData($data)
    {

        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        $tenders = array();

        try {
            $result = $api_instance->charge($this->getLocationId(), $data);

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $lineItems = $result->getTransaction()->getTenders();
                if (count($lineItems) > 0) {
                    foreach ($lineItems as $key => $value) {
                        $tender = array();
                        $tender['id'] = $value->getId();
                        $tender['quantity'] = 1;
                        $tender['amount'] = $value->getAmountMoney()->getAmount()/100;
                        $tender['currency'] = $value->getAmountMoney()->getCurrency();
                        $item['note'] = $value->getNote();
                        array_push($tenders, $tender);
                    }
                }
                $response = [
                    'status' => 'success',
                    'transactionId' => $result->getTransaction()->getId(),
                    'referenceId' => $result->getTransaction()->getReferenceId(),
                    'created_at' => $result->getTransaction()->getCreatedAt(),
                    'orderId' => $result->getTransaction()->getOrderId(),
                    'tenders' => $tenders
                ];
            }
            return $this->createResponse($response);
        } catch (Exception $e) {
            echo 'Exception when creating transaction: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createResponse($response)
    {
        return $this->response = new ChargeResponse($this, $response);
    }
}
