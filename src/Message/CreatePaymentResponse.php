<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\ResponseInterface;
use Square\Models\Payment;

/**
 * Square Create Payment Response
 */
class CreatePaymentResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return ($this->data['successful'] && in_array($this->getPayment()->getStatus(), array('COMPLETED', 'APPROVED'), true));
    }

    /**
     * Does the response require a redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * Is the transaction cancelled by the user?
     *
     * @return boolean
     */
    public function isCancelled()
    {
        return (in_array($this->getPayment()->getStatus(), array('FAILED', 'CANCELED'), true));
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        $message = '' . ($this->data['result']->getReasonPhrase() ?? '');
        if (isset($this->data['error'])) {
            $message .= ' ' . $this->data['error']['code'] . ': ';
            $message .= $this->data['error']['detail'];
            $message = trim($message);
        }
        return $message;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->data['result']->getStatusCode() ?? null;
    }

    /**
     * Gateway Reference
     * Get Square's unique ID for the payment.
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return $this->data['result']->getPayment()->getId() ?? null;
    }

    /**
     * Merchant’s Reference
     * Get the merchant’s reference to the transaction
     *
     * @return null|string A reference provided by the merchant to represent this transaction
     */
    public function getTransactionId()
    {
        return $this->data['result']->getPayment()->getReferenceId() ?? null;
    }

    /**
     * Get the Square payment object from the response.
     * The Payment object contains all the information about the payment via "getPayment()->get{PaymentAttribute}()".
     *
     * @example getPayment()->getAmountMoney()->getAmount() will return the amount of the payment in cents (for USD).
     * @example getPayment()->getSourceType() will return the source type for the payment.
     * @see https://developer.squareup.com/reference/square/payments-api/create-payment#response__property-payment
     * @return Payment|null
     */
    public function getPayment() :?Payment
    {
        return $this->data['result']->getPayment() ?? null;
    }

    // The following methods are provided for convenience, but all can be done with getPayment()->get{PaymentAttribute}()

    public function getOrderId()
    {
        return $this->data['result']->getPayment()->getOrderId() ?? null;
    }

    public function getCreatedAt()
    {
        return $this->data['result']->getPayment()->getCreatedAt() ?? null;
    }

    /**
     * Caution: Square payment object uses `reference_id` for the merchant's assigned reference ID.
     * For the Square generated payment ID, use getTransactionReference() or payment object `id`.
     *
     * @return string|null
     */
    public function getReferenceId()
    {
        return $this->getTransactionId();
    }

    /**
     * Indicates whether the payment is APPROVED, PENDING, COMPLETED, CANCELED, or FAILED.
     * @see https://developer.squareup.com/docs/payments-api/take-payments#payment-status
     * @return null|string
     */
    public function getPaymentStatus()
    {
        return $this->data['result']->getPayment()->getStatus() ?? null;
    }
}