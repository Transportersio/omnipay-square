<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;

use Square\Environment;
use Square\SquareClient;

/**
 * Square Complete Payment Request
 */
class CompletePaymentRequest extends AbstractRequest
{
    /**
     * Get the payment_id for the payment to be completed.
     * Mapped to Omnipay's `token` per the Omnipay standard
     */
    public function getPaymentId()
    {
        return $this->getToken();
    }

    /**
     * Set the payment_id for the payment to be completed.
     * Mapped to Omnipay's `token` per the Omnipay standard
     */
    public function setPaymentId(string $value)
    {
        return $this->setToken($value);
    }

    public function getAccessToken()
    {
        return $this->getParameter('accessToken') ?? null;
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    public function getVersionToken()
    {
        return $this->getParameter('versionToken') ?? null;
    }

    /**
     * BETA FEATURE: Used for optimistic concurrency. This opaque token identifies the current `Payment`
     * version that the caller expects. If the server has a different version of the Payment, the update fails and
     * a response with a VERSION_MISMATCH error is returned.
     *
     * https://developer.squareup.com/reference/square/payments-api/complete-payment#request__property-version_token
     */
    public function setVersionToken(string $value)
    {
        return $this->setParameter('versionToken', $value);
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

    public function getLocationId()
    {
        return $this->getParameter('locationId') ?? null;
    }

    public function setLocationId(string $value)
    {
        return $this->setParameter('locationId', $value);
    }


    public function getData()
    {
        $this->validate('token');

        // set up Payment Request body
        return new \Square\Models\CompletePaymentRequest();
    }

    public function sendData($data)
    {

        try {
            $api_instance = $this->getApiInstance();
            $result = $api_instance->completePayment($this->getToken(), $data);
            if ($result->isSuccess()) {
                $response = [
                    'successful' => true,
                    'result' => $result->getResult()
                ];
            } else {
                $errors = $result->getErrors();
                $response = [
                    'successful' => false,
                    'error' => [
                        'code' => ($errors[0]->getCode() ?? ''),
                        'detail' => ($errors[0]->getDetail() ?? ''),
                        'field' => ($errors[0]->getField() ?? ''),
                        'category' => ($errors[0]->getCategory() ?? '')
                    ]
                ];
            }
        } catch (\Exception $e) {
            $error = $e->getResponseBody()->errors[0]->detail ?? $e->getMessage();
            $response = [
                'successful' => false,
                'error' => [
                    'detail' => 'Exception when creating payment: ' . $error,
                ]
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($response)
    {
        return $this->response = new CreatePaymentResponse($this, $response);
    }
}
