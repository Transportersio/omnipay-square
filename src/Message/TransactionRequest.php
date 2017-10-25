<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class TransactionRequest extends AbstractRequest
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


    public function getData()
    {
        $data = array();

        $data['checkoutId'] = $this->getCheckoutId();
        $data['transactionId'] = $this->getTransactionId();

        return $data;
    }

    public function sendData($data)
    {

        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {
            $result = $api_instance->retrieveTransaction($this->getLocationId(), $data['transactionId']);

            $orders = array();

            $lineItems = $result->getTransaction()->getTenders();
            if (count($lineItems) > 0) {
                foreach ($lineItems as $key => $value) {
                    $data = array();
                    $data['quantity'] = 1;
                    $data['amount'] = $value->getAmountMoney()->getAmount()/100;
                    $data['currency'] = $value->getAmountMoney()->getCurrency();
                    array_push($orders, $data);
                }
            }

            if ($error = $result->getErrors()) {
                $response = array(
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                );
            } else {
                $response = array(
                    'status' => 'success',
                    'transactionId' => $result->getTransaction()->getId(),
                    'referenceId' => $result->getTransaction()->getReferenceId(),
                    'orders' => $orders
                );
            }
            return $this->createResponse($response);
        } catch (Exception $e) {
            echo 'Exception when calling LocationsApi->listLocations: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createResponse($response)
    {
        return $this->response = new TransactionResponse($this, $response);
    }
}
