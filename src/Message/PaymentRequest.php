<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Purchase Request
 */
class PaymentRequest extends AbstractRequest
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

    public function getYourConsumerReference()
    {
        return $this->getParameter('yourConsumerReference');
    }

    public function setYourConsumerReference($value)
    {
        return $this->setParameter('yourConsumerReference', $value);
    }

    public function getYourPaymentReference()
    {
        return $this->getParameter('yourPaymentReference');
    }

    public function setYourPaymentReference($value)
    {
        return $this->setParameter('yourPaymentReference', $value);
    }


    public function getData()
    {
        $this->validate('amount');

        $data = array();
        $data['judoId'] = $this->getJudoId();
        $data['yourConsumerReference'] = $this->getYourConsumerReference();
        $data['yourPaymentReference'] = $this->getYourPaymentReference();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['cardNumber'] = $this->getCard()->getNumber();
        $data['expiryDate'] = $this->getCard()->getExpiryDate('m/y');
        $data['cv2'] = $this->getCard()->getCvv();

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

        $payment = $judopay->getModel('Payment');
        $payment->setAttributeValues($data);

        try {
            $response = $payment->create();
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
        return $this->response = new PaymentResponse($this, $response);
    }
}
