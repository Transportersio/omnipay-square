<?php

namespace Omnipay\Square\Message;

use Omnipay\Common\Message\AbstractRequest;
use SquareConnect;

/**
 * Square Purchase Request
 */
class ChargeRequest extends AbstractRequest
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

	public function getCheckoutId()
	{
		return $this->getParameter('checkoutId');
	}

	public function setCheckoutId($value)
	{
		return $this->setParameter('ReceiptId', $value);
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

	public function setMetaData($value) {
		return $this->setParameter('meta_data', $value);
	}

	public function getData()
	{
		$required_fields = array(
			'idempotency_key' => uniqid(),
			'amount_money' => new SquareConnect\Model\Money(array(
                'amount' => (int) $this->getParameter('amount'),
				'currency' => $this->getParameter('currency')
			)),
			'card_nonce' => $this->getParameter('card_nonce')
		);

		$data = array_merge(
			$required_fields,
			$this->getParameter('meta_data')
		);

		return $data;
	}

	public function sendData($data)
	{
		SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($this->getAccessToken());

		$api_instance = new SquareConnect\Api\TransactionsApi();

		try {
			$result = $api_instance->charge($this->getLocationId(), $data);

			$orders = array();

			$lineItems = $result->getTransaction()->getTenders();
			foreach ($lineItems as $key => $value) {
				$data = array();
				$data['quantity'] = 1;
				$data['amount'] = $value->getAmountMoney()->getAmount();
				$data['currency'] = $value->getAmountMoney()->getCurrency();

				if($value->getType() == 'CARD') {
					$card = $value->getCardDetails()->getCard();
					$data['card_details'] = [
						'last_4' => $card->getLast4(),
						'hash' => $card->getFingerprint(),
					];
				}

				array_push($orders, $data);
			}

			if ($error = $result->getErrors()) {
				$response = array(
					'status' => 'error',
					'code' => $error['code'],
					'detail' => $error['detail']
				);
			} else {
				$response = array(
					'status' => 'success',
					'transactionId' => $result->getTransaction()->getId(),
					'transaction' => $result->getTransaction()->getTenders()[0],
					'referenceId' => $result->getTransaction()->getReferenceId(),
					'orders' => $orders
				);
			}

			return $this->createResponse($response);
		} catch (SquareConnect\ApiException $e) {
			$response = array(
				'status' => false,
				'message' => '',
			);

			foreach ( $e->getResponseBody()->errors as $error ) {
				$response['message'] .= $error->detail . PHP_EOL;

                if(!$error->code == 'CARD_DECLINED')
                {
                    report($e);
                }
			}

			return $this->createResponse($response);
		} catch(\Exception $e) {

            report($e);

            return array(
				'status' => false,
				'message' => 'Something went wrong when trying to charge you card. Please try again.',
			);
		}
	}

	/**
	 * @param $response
	 *
	 * @return TransactionResponse
	 */
	public function createResponse($response)
	{
		return $this->response = new TransactionResponse($this, $response);
	}
}