<?php

namespace App;

class PayPal
{
	private $_apiContext;
	private $shopping_cart;
	private $_ClientId = "AeRIHGqJfW-DpjTK3Cn8gVOwtd1onVrGHm3RHKjpRaR8kza_v4NN8UGndGOHImyRwoVjZ8QXg23uM6Yk";
	private $_ClientSecret = "ENE03d_BpWH9cUuNkDutv6ulE7cqu4Xb3mAocEpDlI_h2zfmtXUCHHOp2cb3VcM6EFxzDyswhSwjDQQ-";

	public function __construct($shopping_cart){

		$this->_apiContext = \PaypalPayment::ApiContext($this->_ClientId, $this->_ClientSecret);

		$config = config("paypal_payment");

		$flatConfig = array_dot($config);

		$this->_apiContext->setConfig($flatConfig);

		$this->shopping_cart = $shopping_cart;



	}

	public function generate() {
		$payment = \PaypalPayment::payment()->setIntent("sale")
							->setPayer($this->payer())
							->setTransactions([$this->transaction()])
							->setRedirectUrls($this->redirectUrls());

		try{
			$payment->create($this->_apiContext);
		} catch (\Exception $ex) {
			dd($ex);
			exit(1);
		}

		return $payment;
	}


	public function payer() {

		return \PaypalPayment::payer()
						->setPaymentMethod("paypal");

	}

		public function redirectUrls() {

		$baseURL = url('/');
		return \PaypalPayment::redirectUrls()
						->setReturnUrl("$baseURL/payments/store")
						->setCancelUrl("$baseURL/carrito");
	}

	public function transaction() {

		return \PaypalPayment::transaction()
					->setAmount($this->amount())
					->setItemList($this->items())
					->setDescription("Tu compra en Vekingo")
					->setInvoiceNumber(uniqid());
		
	}

	public function amount() {
		return \PaypalPayment::amount()->setCurrency("USD")
						->setTotal($this->shopping_cart->total());
	}
	public function items() {
		$items = [];

		$products = $this->shopping_cart->products()->get();

		foreach ($products as $product) {
			array_push($items, $product->paypalItem());
		}

		return \PaypalPayment::itemList()->setItems($items);
	}

	public function execute($paymentId,$payerId) {
		$payment = \PaypalPayment::getById($paymentId,$this->_apiContext);

		$execution = \PaypalPayment::PaymentExecution()
							->setPayerId($payerId);

		//Aqui el metodo execute viene del api de paypal  no es el metodo del proyecto

		return $payment->execute($execution,$this->_apiContext);
	}





}