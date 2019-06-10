<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

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
        return [];
    }

    public function sendData()
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {
            $result = $api_instance->listTransactions(
                $this->getLocationId(),
                $this->getBeginTime(),
                $this->getEndTime(),
                $this->getSortOrder(),
                $this->getCursor()
            );

            if ($error = $result->getErrors()) {
                $response = [
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                ];
            } else {
                $transactions = [];
                $transactionList = $result->getTransactions();
                if ($transactionList === null) {
                    $transactionList = [];
                }
                foreach ($transactionList as $transaction) {
                    $trans = new \stdClass();
                    $trans->id = $transaction->getID();
                    $trans->orderId = $transaction->getOrderId();
                    $trans->clientId = $transaction->getClientId();
                    $trans->referenceId = $transaction->getReferenceId();
                    $trans->locationId = $transaction->getLocationId();
                    $trans->createdAt = $transaction->getCreatedAt();
                    $trans->shippingAddress = $transaction->getShippingAddress();
                    $trans->product = $transaction->getProduct();
                    $trans->items = [];
                    $tenderList = $transaction->getTenders();
                    if ($tenderList === null) {
                        $tenderList = [];
                    }
                    foreach ($tenderList as $tender) {
                        $item = new \stdClass();
                        $item->id = $tender->getId();
                        $item->quantity = 1;
                        $item->amount = $tender->getAmountMoney()->getAmount();
                        $item->currency = $tender->getAmountMoney()->getCurrency();
                        if ($tender->getTipMoney() !== null) {
                            $item->tipAmount = $tender->getTipMoney()->getAmount();
                        }
                        $item->processingFee = $tender->getProcessingFeeMoney()->getAmount();
                        $item->note = $tender->getNote();
                        $item->type = $tender->getType();
                        $item->customerId = $tender->getCustomerId();
                        $item->cardDetails = new \stdClass();
                        $cardDetails = $tender->getCardDetails();
                        if (!empty($cardDetails)) {
                            $item->cardDetails->status = $cardDetails->getStatus();
                            $item->cardDetails->card = $cardDetails->getCard();
                            $item->cardDetails->entryMethod = $cardDetails->getEntryMethod();
                        }
                        $item->cashDetails = new \stdClass();
                        $cashDetails = $tender->getcashDetails();
                        if (!empty($cashDetails)) {
                            $item->cashDetails->buyerTenderedMoney = $cashDetails->getBuyerTenderedMoney()->getAmount();
                            $item->cashDetails->chargeBackMoney = $cashDetails->getChangeBackMoney()->getAmount();
                        }
                        $trans->items[] = $item;
                    }
                    $trans->refunds = [];
                    $refundList = $transaction->getRefunds();
                    if ($refundList === null) {
                        $refundList = [];
                    }
                    foreach ($refundList as $refund) {
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
                        $trans->items[] = $item;
                    }
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
                'detail' => 'Exception when calling TransactionsApi->listTransactions: ' . $e->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new ListTransactionsResponse($this, $response);
    }
}
