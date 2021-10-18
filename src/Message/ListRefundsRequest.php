<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\SquareClient;

/**
 * Square List Refunds Request
 */
class ListRefundsRequest extends AbstractRequest
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

        return $api_client->getRefundsApi();
    }

    public function getData()
    {
        return [];
    }

    public function sendData($data = '')
    {
        $api_instance = $this->getApiInstance();

        try {
            $result = $api_instance->listPaymentRefunds(
                $this->getBeginTime(),
                $this->getEndTime(),
                $this->getSortOrder(),
                $this->getCursor(),
                $this->getLocationId()
            );

            if ($errors = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $errors[0]->getCode(),
                    'detail' => $errors[0]->getDetail(),
                    'field' => $errors[0]->getField(),
                    'category' => $errors[0]->getCategory()
                ];
            } else {
                $refunds = [];
                $refundItems = $result->getResult()->getRefunds();
                if ($refundItems === null) {
                    $refundItems = [];
                }
                foreach ($refundItems as $refund) {
                    $item = new \stdClass();
                    $item->id = $refund->getId();
                    $item->tenderId = $refund->getTenderId();
                    $item->locationId = $refund->getLocationId();
                    $item->transactionId = $refund->getTransactionId();
                    $item->createdAt = $refund->getCreatedAt();
                    $item->reason = $refund->getReason();
                    $item->status = $refund->getStatus();
                    $item->amount = $refund->getAmountMoney()->getAmount();
                    $item->processingFee = $refund->getProcessingFeeMoney();
                    $item->currency = $refund->getAmountMoney()->getCurrency();
                    $refunds[] = $item;
                }
                $response = [
                    'status' => 'success',
                    'refunds' => $refunds,
                    'cursor' => $result->getCursor()
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when calling RefundsApi->listPaymentRefunds: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new ListRefundsResponse($this, $response);
    }
}
