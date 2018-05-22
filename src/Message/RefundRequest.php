<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

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

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function setCardNonce($value) {
        return $this->setParameter('card_nonce', $value);
    }

    public function getTenderId() {
        return $this->getParameter('tenderId');
    }

    public function setTenderId($value) {
        return $this->setParameter('tenderId', $value);
    }

    public function getReason() {
        return $this->getParameter('reason');
    }

    public function setReason($value) {
        return $this->setParameter('reason', $value);
    }


    public function getData()
    {
        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        $transactionResponse = $api_instance->retrieveTransaction($this->getLocationId(), $this->getTransactionId());

        if(! empty($errors = $transactionResponse->getErrors())) {
            throw new \Exception('Error while getting the transaction for refund.');
        }

        $data = array(
            'idempotency_key' => uniqid(),
            'tender_id' => $transactionResponse->getTransaction()->getTenders()[0]->getId(),
            'reason'    => $this->getReason(),
            'amount_money' => new SquareConnect\Model\Money(array(
                'amount' => intval($this->getParameter('amount')*100),
                'currency' => $this->getParameter('currency')
            )),
        );

        return $data;
    }

    public function sendData($data)
    {

        SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

        $api_instance = new SquareConnect\Api\TransactionsApi();

        try {

            $result = $api_instance->createRefund($this->getLocationId(), $this->getTransactionId(),$data);

            if ($error = $result->getErrors()) {
                $response = array(
                    'status' => 'error',
                    'code' => $error['code'],
                    'detail' => $error['detail']
                );
            } else {
            	$refund = $result->getRefund();

                $response = array(
                    'status' => 'success',
                    'transactionId' => $refund->getTransactionId(),
                    'referenceId' => $refund->getId(),
	                'description' => $refund->getReason(),
	                'amount'        => $refund->getAmountMoney()->getAmount(),
	                'amount_refunded'        => $refund->getAmountMoney()->getAmount(),
	                'currency'              => $refund->getAmountMoney()->getCurrency(),
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
