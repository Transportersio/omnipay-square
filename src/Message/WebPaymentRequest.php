<?php

namespace Omnipay\Judopay\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Judopay Purchase Request
 */
class WebPaymentRequest extends AbstractRequest
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

    public function getYourPaymentMetaData()
    {
        return $this->getParameter('yourPaymentMetaData');
    }

    public function setYourPaymentMetaData($value)
    {
        return $this->setParameter('yourPaymentMetaData', $value);
    }


    public function getData()
    {
        $this->validate('amount');

        $data = array();
        $data['judoId'] = $this->getJudoId();
        $data['yourConsumerReference'] = $this->getYourConsumerReference();
        $data['yourPaymentReference'] = $this->getYourPaymentReference();
        $data['yourPaymentMetaData'] = $this->getYourPaymentMetaData();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['clientIpAddress'] = $this->getRealIpAddr();
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $data['clientUserAgent'] = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $data['clientUserAgent'] = "";
        }


        return $data;
    }

    public function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "";
        }
        return $ip;
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

        $webpayment = $judopay->getModel('WebPayments\Payment');
        $webpayment->setAttributeValues($data);

        try {
            $response = $webpayment->create();
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
        return $this->response = new WebPaymentResponse($this, $response);
    }
}
