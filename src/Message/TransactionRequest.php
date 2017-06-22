<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Purchase Request
 */
class TransactionRequest extends AbstractRequest
{

    public function getApiToken()
    {
        return $this->getParameter('apiToken');
    }

    public function setApiToken($value)
    {
        return $this->setParameter('apiToken', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getJudoId()
    {
        return $this->getParameter('judoId');
    }

    public function setJudoId($value)
    {
        return $this->setParameter('judoId', $value);
    }

    public function getReceiptId()
    {
        return $this->getParameter('ReceiptId');
    }

    public function setReceiptId($value)
    {
        return $this->setParameter('ReceiptId', $value);
    }

    public function getCardToken()
    {
        return $this->getParameter('CardToken');
    }

    public function setCardToken($value)
    {
        return $this->setParameter('CardToken', $value);
    }

    public function getReference()
    {
        return $this->getParameter('Reference');
    }

    public function setReference($value)
    {
        return $this->setParameter('Reference', $value);
    }


    public function getData()
    {
        $data = array();
        $data['ReceiptId'] = $this->getReceiptId();
        $data['CardToken'] = $this->getCardToken();
        $data['Reference'] = $this->getReference();

        return $data;
    }

    public function sendData($data)
    {
        $judopay = new \Judopay(
            array(
                'apiToken' => $this->getApiToken(),
                'apiSecret' => $this->getApiSecret(),
                'judoId' => $this->getJudoId(),
                'useProduction' => ($this->getTestMode() > 0) ? false : true
            )
        );

        try {
            $existingTransactionRequest = $judopay->getModel('WebPayments\Transaction');
            $response = $existingTransactionRequest->find($data['Reference']);

            return $this->createResponse($response);

        } catch (\Judopay\Exception\ValidationError $e) {
            echo $e->getSummary();
        } catch (\Judopay\Exception\ApiException $e) {
            echo $e->getSummary();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }


    }

    public function createResponse($response)
    {
        return $this->response = new TransactionResponse($this, $response);
    }
}
