<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\Models\ListPaymentsResponse;
use Square\SquareClient;

/**
 * Square List Transactions Request
 */
class ListTransactionsRequest extends AbstractRequest
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

    public function getBeginTime()
    {
        return $this->getParameter('begin_time');
    }

    public function setBeginTime($value)
    {
        return $this->setParameter('begin_time', $value);
    }

    public function getEndTime()
    {
        return $this->getParameter('end_time');
    }

    public function setEndTime($value)
    {
        return $this->setParameter('end_time', $value);
    }

    public function getSortOrder()
    {
        return $this->getParameter('sort_order');
    }

    public function setSortOrder($value)
    {
        return $this->setParameter('sort_order', $value);
    }

    public function getCursor()
    {
        return $this->getParameter('cursor');
    }

    public function setCursor($value)
    {
        return $this->setParameter('cursor', $value);
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
        return [];
    }

    public function sendData($data = '')
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->listPayments(
                $this->getBeginTime(),
                $this->getEndTime(),
                $this->getSortOrder(),
                $this->getCursor(),
                $this->getLocationId()
            );

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $transactions = [];
                $transactionList = $result->getResult()->getPayments();
                if ($transactionList === null) {
                    $transactionList = [];
                }
                /** @var \Square\Models\Payment $transaction */
                foreach ($transactionList as $transaction) {
                    $trans = new \stdClass();
                    $trans->id = $transaction->getID();
                    $trans->orderId = $transaction->getOrderId();
                    $trans->clientId = $transaction->getCustomerId();
                    $trans->referenceId = $transaction->getReferenceId();
                    $trans->locationId = $transaction->getLocationId();
                    $trans->createdAt = $transaction->getCreatedAt();
                    $trans->shippingAddress = $transaction->getShippingAddress();
                    $trans->amount = $transaction->getAmountMoney()->getAmount();
                    $trans->status = $transaction->getStatus();
                    $trans->items = [];
                    $trans->refunds = [];
                    $transactions[] = $trans;
                }
                $response = [
                    'status' => 'success',
                    'transactions' => $transactions,
                    'cursor' => $result->getCursor()
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when calling PaymentsApi->listPayments: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new ListTransactionsResponse($this, $response);
    }
}
