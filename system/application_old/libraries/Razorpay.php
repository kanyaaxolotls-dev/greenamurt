<?php
defined('BASEPATH') or exit('No direct script access allowed');
include(APPPATH.'libraries/razorpay-php/Razorpay.php');
session_start();
// Create the Razorpay Order
use Razorpay\Api\Api;
class Razorpay
{
	 private $CI;

	 public function __construct()
	 {
		$this->CI =& get_instance();
		 
	 }

	 public function processPayment($orderDataReceived)
	 {
	 	
		$this->CI->config->load('pg');
		//echo config_item('RAZOR_KEY_ID');exit;
		$keyId = config_item('RAZOR_KEY_ID');
        $keySecret = config_item('RAZOR_KEY_SECRET'); //RAZOR_KEY_SECRET;
		$displayCurrency = 'INR';
		
		$api = new Api($keyId, $keySecret);

		//
		// We create an razorpay order using orders api
		// Docs: https://docs.razorpay.com/docs/orders
		//
		$orderData = [
			'receipt'         => $orderDataReceived['receipt'],
			'amount'          => $orderDataReceived['amount'] * 100, // 2000 rupees in paise
			'currency'        => 'INR',
			'payment_capture' => 1 // auto capture
		];

		$razorpayOrder = $api->order->create($orderData);

		$razorpayOrderId = $razorpayOrder['id'];

		$_SESSION['razorpay_order_id'] = $razorpayOrderId;

		$displayAmount = $amount = $orderDataReceived['amount'];

		if ($displayCurrency !== 'INR')
		{
			$url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
			$exchange = json_decode(file_get_contents($url), true);

			$displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
		}

		$checkout = 'automatic';
		/*
		if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
		{
			$checkout = $_GET['checkout'];
		}*/


		$data = [
			"key"               => $keyId,
			"amount"            => $amount,
			"name"              => config_item('company_name'),
			"description"       => "True success is all about working towards meaningful goals",
			"image"             => base_url('uploads/logo.png'),
			"prefill"           => [
				"name"              => $orderDataReceived['prefill_name'],
				"email"             => $orderDataReceived['prefill_email'],
				"contact"           => $orderDataReceived['prefill_contact'],
			],
			"notes"             => [
				"address"           => $orderDataReceived['notes_address'],
				"merchant_order_id" => $orderDataReceived['receipt']
			],
			"theme"             => [
				"color"             => "#F37004"
			],
			"order_id"          => $razorpayOrderId,
		];

		if ($displayCurrency !== 'INR')
		{
			$data['display_currency']  = $displayCurrency;
			$data['display_amount']    = $displayAmount;
		}
        $data['shopping_order_id']    = $orderDataReceived['shopping_order_id'];
		$json = json_encode($data);

		require("{$checkout}.php");
	 }


}