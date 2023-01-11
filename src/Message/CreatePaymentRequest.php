<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use Square\Environment;
use Square\Models\Address;
use Square\Models\ExternalPaymentDetails;
use Square\Models\Money;
use Square\SquareClient;

/**
 * Square Create Payment Request
 */
class CreatePaymentRequest extends AbstractRequest
{
    /**
     * Get the source_id for the payment request
     * Mapped to Omnipay's `token` per the Omnipay standard
     */
    public function getSourceId()
    {
        return $this->getToken();
    }

    /**
     * Set the source_id for the payment request
     * Mapped to Omnipay's `token` per the Omnipay standard
     */
    public function setSourceId(string $value)
    {
        return $this->setToken($value);
    }

    public function getIdempotencyKey()
    {
        return $this->getParameter('idempotencyKey');
    }

    public function setIdempotencyKey($value)
    {
        return $this->setParameter('idempotencyKey', $value);
    }

    public function getDelayDuration()
    {
        return $this->getParameter('delayDuration') ?? null;
    }

    public function setDelayDuration($value)
    {
        return $this->setParameter('delayDuration', $value);
    }

    public function getAutocomplete()
    {
        return $this->getParameter('autocomplete') ?? true;
    }

    public function setAutocomplete($autocomplete)
    {
        return $this->setParameter('autocomplete', $autocomplete);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId') ?? null;
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getCustomerId()
    {
        return $this->getParameter('customerId') ?? null;
    }

    public function setCustomerId($value)
    {
        return $this->setParameter('customerId', $value);
    }

    public function getLocationId()
    {
        return $this->getParameter('locationId') ?? null;
    }

    public function setLocationId($value)
    {
        return $this->setParameter('locationId', $value);
    }

    public function getTeamMemberId()
    {
        return $this->getParameter('teamMemberId') ?? null;
    }

    public function setTeamMemberId($value)
    {
        return $this->setParameter('teamMemberId', $value);
    }

    public function getAcceptPartialAuthorization()
    {
        return $this->getParameter('acceptPartialAuthorization') ?? false;
    }

    public function setAcceptPartialAuthorization($value)
    {
        return $this->setParameter('acceptPartialAuthorization', $value);
    }

    public function getBuyerEmailAddress()
    {
        return $this->getParameter('buyerEmailAddress') ?? null;
    }

    public function setBuyerEmailAddress($value)
    {
        return $this->setParameter('buyerEmailAddress', $value);
    }

    public function getBillingAddress()
    {
        return $this->getParameter('billingAddress') ?? null;
    }

    public function setBillingAddress(?Address $value)
    {
        return $this->setParameter('billingAddress', $value);
    }

    public function getShippingAddress()
    {
        return $this->getParameter('shippingAddress') ?? null;
    }

    public function setShippingAddress(?Address $value)
    {
        return $this->setParameter('shippingAddress', $value);
    }



    /**
     * Get the reference_id for the payment request
     * Mapped to Omnipay's `transactionId` per the Omnipay standard
     */
    public function getReferenceId()
    {
        return $this->getTransactionId();
    }

    /**
     * Set the reference_id for the payment request
     * Mapped to Omnipay's `transactionId` per the Omnipay standard
     */
    public function setReferenceId($value)
    {
        return $this->setTransactionId($value);
    }

    /**
     * Get the note for the payment request
     * Mapped to Omnipay's `description` per the Omnipay standard
     */
    public function getNote()
    {
        return $this->getDescription();
    }

    /**
     * Set the note for the payment request
     * Mapped to Omnipay's `description` per the Omnipay standard
     */
    public function setNote($value)
    {
        return $this->setDescription($value);
    }

    public function getVerificationToken()
    {
        return $this->getParameter('verificationToken') ?? null;
    }

    public function setVerificationToken($value)
    {
        return $this->setParameter('verificationToken', $value);
    }

    public function getStatementDescriptionIdentifier()
    {
        return $this->getParameter('statementDescriptionIdentifier') ?? null;
    }

    public function setStatementDescriptionIdentifier($value)
    {
        return $this->setParameter('statementDescriptionIdentifier', $value);
    }

    /** cash details */
    public function getCashAmountInteger()
    {
        return $this->getParameter('cashAmountInteger') ?? null;
    }

    public function setCashAmountInteger($value)
    {
        return $this->setParameter('cashAmountInteger', (int) $value);
    }

    public function getCashCurrency()
    {
        return $this->getParameter('cashCurrency') ?? null;
    }

    public function setCashCurrency(?string $currency)
    {
        return $this->setParameter('cashCurrency', $currency);
    }

    /** tip details */
    public function getTipAmountInteger()
    {
        return $this->getParameter('tipAmountInteger') ?? null;
    }

    public function setTipAmountInteger($value)
    {
        return $this->setParameter('tipAmountInteger', (int) $value);
    }

    public function getTipCurrency()
    {
        return $this->getParameter('tipCurrency') ?? null;
    }

    public function setTipCurrency(?string $currency)
    {
        return $this->setParameter('tipCurrency', $currency);
    }

    /** app fee details */

    public function getAppFeeAmountInteger()
    {
        return $this->getParameter('appFeeAmountInteger') ?? null;
    }

    public function setAppFeeAmountInteger($value)
    {
        return $this->setParameter('appFeeAmountInteger', (int) $value);
    }

    public function getAppFeeCurrency()
    {
        return $this->getParameter('appFeeCurrency') ?? null;
    }

    public function setAppFeeCurrency(?string $currency)
    {
        return $this->setParameter('appFeeCurrency', $currency);
    }

    /** external payment details */
    public function getExternalPaymentType()
    {
        return $this->getParameter('externalPaymentType') ?? null;
    }

    public function setExternalPaymentType($value)
    {
        return $this->setParameter('externalPaymentType', $value);
    }

    public function getExternalPaymentSource()
    {
        return $this->getParameter('externalPaymentSource') ?? null;
    }

    public function setExternalPaymentSource($value)
    {
        return $this->setParameter('externalPaymentSource', $value);
    }

    public function getExternalPaymentSourceId()
    {
        return $this->getParameter('externalPaymentSourceId') ?? null;
    }

    public function setExternalPaymentSourceId($value)
    {
        return $this->setParameter('externalPaymentSourceId', $value);
    }

    public function getExternalPaymentSourceFeeAmountInteger()
    {
        return $this->getParameter('externalPaymentSourceFeeAmountInteger') ?? null;
    }

    public function setExternalPaymentSourceFeeAmountInteger($value)
    {
        return $this->setParameter('externalPaymentSourceFeeAmountInteger', (int) $value);
    }

    public function getExternalPaymentSourceFeeCurrency()
    {
        return $this->getParameter('externalPaymentSourceFeeCurrency') ?? null;
    }

    public function setExternalPaymentSourceFeeCurrency(?string $currency)
    {
        return $this->setParameter('externalPaymentSourceFeeCurrency', $currency);
    }

    public function getAccessToken()
    {
        return $this->getParameter('accessToken') ?? null;
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
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
        $this->validate('amount', 'token', 'idempotencyKey');

        // handle addresses and email via omnipay card object per omnipay standard
        $card = $this->getCard();
        if ($card) {
            $cardData['billingAddress'] = array();
            $cardData['billingAddress']['lastName'] = $card->getBillingLastName();
            $cardData['billingAddress']['firstName'] = $card->getBillingFirstName();
            $cardData['billingAddress']['address1'] = $card->getBillingAddress1();
            $cardData['billingAddress']['address2'] = $card->getBillingAddress2();
            $cardData['billingAddress']['city'] = $card->getBillingCity();
            $cardData['billingAddress']['state'] = $card->getBillingState();
            $cardData['billingAddress']['countryCode'] = $card->getBillingCountry();
            $cardData['billingAddress']['postalCode'] = $card->getBillingPostcode();

            $cardData['deliveryAddress'] = array();
            $cardData['deliveryAddress']['lastName'] = $card->getShippingLastName();
            $cardData['deliveryAddress']['firstName'] = $card->getShippingFirstName();
            $cardData['deliveryAddress']['address1'] = $card->getShippingAddress1();
            $cardData['deliveryAddress']['address2'] = $card->getShippingAddress2();
            $cardData['deliveryAddress']['city'] = $card->getShippingCity();
            $cardData['deliveryAddress']['state'] = $card->getShippingState();
            $cardData['deliveryAddress']['countryCode'] = $card->getShippingCountry();
            $cardData['deliveryAddress']['postalCode'] = $card->getShippingPostcode();

            $cardData['buyerEmailAddress'] = $card->getEmail();
            if (!empty($cardData['buyerEmailAddress'])) {
                $this->setBuyerEmailAddress($cardData['buyerEmailAddress']);
            }

            if (!empty($cardData['billingAddress'])) {
                $billingAddress = new Address();
                $billingAddress->setAddressLine1($cardData['billingAddress']['address1']);
                $billingAddress->setAddressLine2($cardData['billingAddress']['address2']);
                $billingAddress->setLocality($cardData['billingAddress']['city']);
                $billingAddress->setAdministrativeDistrictLevel1($cardData['billingAddress']['state']);
                $billingAddress->setPostalCode($cardData['billingAddress']['postalCode']);
                $billingAddress->setCountry($cardData['billingAddress']['countryCode']);
                $billingAddress->setFirstName($cardData['billingAddress']['firstName']);
                $billingAddress->setLastName($cardData['billingAddress']['lastName']);
                $this->setBillingAddress($billingAddress);
            }
            if (!empty($cardData['deliveryAddress'])) {
                $deliveryAddress = new Address();
                $deliveryAddress->setAddressLine1($cardData['deliveryAddress']['address1']);
                $deliveryAddress->setAddressLine2($cardData['deliveryAddress']['address2']);
                $deliveryAddress->setLocality($cardData['deliveryAddress']['city']);
                $deliveryAddress->setAdministrativeDistrictLevel1($cardData['deliveryAddress']['state']);
                $deliveryAddress->setPostalCode($cardData['deliveryAddress']['postalCode']);
                $deliveryAddress->setCountry($cardData['deliveryAddress']['countryCode']);
                $deliveryAddress->setFirstName($cardData['deliveryAddress']['firstName']);
                $deliveryAddress->setLastName($cardData['deliveryAddress']['lastName']);
                $this->setShippingAddress($deliveryAddress);
            }
            unset($cardData);
        }

        // set up amount of money to accept for this payment, not including tip money.
        $amountMoney = new Money();
        $amountMoney->setAmount($this->getAmountInteger());
        $amountMoney->setCurrency($this->getCurrency());

        // set up Payment Request body
        $data = new \Square\Models\CreatePaymentRequest($this->getSourceId(), $this->getIdempotencyKey(), $amountMoney);

        // tip money
        if ($this->getTipAmountInteger()) {
            $tipMoney = new Money();
            $tipMoney->setAmount($this->getTipAmountInteger());
            $tipMoney->setCurrency($this->getTipCurrency());
            $data->setTipMoney($tipMoney);
        }

        // app fee money
        // To set this field, PAYMENTS_WRITE_ADDITIONAL_RECIPIENTS OAuth permission is required
        if ($this->getAppFeeAmountInteger()) {
            $appFeeMoney = new Money();
            $appFeeMoney->setAmount($this->getAppFeeAmountInteger());
            $appFeeMoney->setCurrency($this->getAppFeeCurrency());
            $data->setAppFeeMoney($appFeeMoney);
        }

        $data->setDelayDuration($this->getDelayDuration());
        $data->setAutocomplete($this->getAutocomplete());
        $data->setOrderId($this->getOrderId());
        $data->setCustomerId($this->getCustomerId());
        $data->setLocationId($this->getLocationId());
        $data->setTeamMemberId($this->getTeamMemberId());
        $data->setReferenceId($this->getReferenceId());
        $data->setVerificationToken($this->getVerificationToken());
        $data->setAcceptPartialAuthorization($this->getAcceptPartialAuthorization());
        $data->setBuyerEmailAddress($this->getBuyerEmailAddress());
        $data->setBillingAddress($this->getBillingAddress());
        $data->setShippingAddress($this->getShippingAddress());
        $data->setNote($this->getNote());
        $data->setStatementDescriptionIdentifier($this->getStatementDescriptionIdentifier());

        // set up details for cash payment (sourceId is CASH)
        if ($this->getCashAmountInteger() !== null) {
            $buyerSuppliedMoney = new Money();
            $buyerSuppliedMoney->setAmount($this->getCashAmountInteger());
            $buyerSuppliedMoney->setCurrency($this->getCashCurrency());
            $cashDetails = new \Square\Models\CashPaymentDetails($buyerSuppliedMoney);
            $data->setCashDetails($cashDetails);
        }

        // set up details for external payment (payment->sourceId is EXTERNAL)
        if ($this->getExternalPaymentType() !== null) {
            $externalDetails = new ExternalPaymentDetails($this->getExternalPaymentType(), $this->getExternalPaymentSource());
            // note this is a separate sourceId from the payment sourceId
            $externalDetails->setSourceId($this->getExternalPaymentSourceId());
            if ($this->getExternalPaymentSourceFeeAmountInteger() !== null) {
                $sourceFeeMoney = new Money();
                $sourceFeeMoney->setAmount($this->getExternalPaymentSourceFeeAmountInteger());
                $sourceFeeMoney->setCurrency($this->getExternalPaymentSourceFeeCurrency());
                $externalDetails->setSourceFeeMoney($sourceFeeMoney);
            }
            $data->setExternalDetails($externalDetails);
        }

        return $data;
    }

    public function sendData($data)
    {

        try {
            $api_instance = $this->getApiInstance();
            $result = $api_instance->createPayment($data);
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
