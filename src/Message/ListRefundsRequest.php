<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

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
    /*
    public function getCheckoutId()
    {
    return $this->getParameter('checkOutId');
    }

    public function setCheckoutId($value)
    {
    return $this->setParameter('checkOutId', $value);
    }
    */

    public function getData()
    {
        $data = array();

        return $data;
        $data['checkoutId'] = $this->getCheckoutId();
        $data['transactionId'] = $this->getTransactionId();

        return $data;
    }

    public function sendData($data)
    {

        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {
            $result = $api_instance->listRefunds(
                			$this->getLocationId(),
                			$this->getBeginTime(),
                			$this->getEndTime(),
                			$this->getSortOrder(),
                			$this->getCursor()
						);

            if ($error = $result->getErrors()) {
                $response = array(
                    'status'=> 'error',
                    'code'  => $error['code'],
                    'detail'=> $error['detail']
                );
            } else {
                $refunds = array();
                $refundItems = $result->getRefunds();
                if (is_null($refundItems)) {
                    $refundItems = array();
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
                    array_push($refunds, $item);
                }
                $response = array(
                    'status' => 'success',
                    'refunds'=> $refunds,
                    'cursor' => $result->getCursor()
                );
            }
            return $this->createResponse($response);
        } catch (Exception $e) {
            echo 'Exception when calling TransactionsApi->listTransactions: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function createResponse($response)
    {
        return $this->response = new ListRefundsResponse($this, $response);
    }
}
