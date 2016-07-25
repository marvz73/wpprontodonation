<?php
/*
 * Name: Marvin Aya-ay
 * Email: marvin@alphasys.com.au
 * Desc: eWay payment gateway
 * Date: July 20, 2016
 */

class eway{

	//Payment Details
	var $payment = array(
		'logo'					=> 'logo.png',
		'payment_name' 			=> 'eWay',
		'payment_description' 	=> 'This is a payment description here.',
		'url'					=> ''
	);


	public function get_payment_name(){
		echo $this->payment['payment_name'];
	}

	public function get_payment_description(){
		echo $this->payment['payment_description'];
	}

	public function get_payment_logo(){
		echo plugins_url( $this->payment['logo'], __FILE__ );
	}

	function get_form_fields(){

		return $form = array(
			array(
				'type'  => 'checkbox',
				'value' => false,
				'name'	=> 'ewaysandboxmode',
				'label'	=> 'Eway Sanbox Mode'
			),
			array(
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'logo',
				'label'	=> 'Show Logo'
			),
			array(
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'enable',
				'label'	=> 'Enable Payment'
			),
			array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'ewayapikey',
				'label'	=> 'eWay API Key'
			),
			array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'ewayapipassword',
				'label'	=> 'eWay API Password'
			),

		);

	}



	public function payment_process($payment_params = array()){

		echo 123123123123;

		require_once('eWay.php');

		$request = new eWAY\CreateAccessCodesSharedRequest();
		// $request->Customer->TokenCustomerID = '911193411846';  -----> OPTIONAL

		// $request->Customer->FirstName = 'Junjie';  
		// $request->Customer->LastName = 'Canonio'; 
		// $request->Customer->Country = 'AU'; 
		// $request->Payment->TotalAmount = '69';

		// $self_url = 'http';
		// if (!empty($_SERVER['HTTPS'])) {
		//     $self_url .= "s";
		// }
		// $self_url .= "://" . $_SERVER["SERVER_NAME"];
		// if ($_SERVER["SERVER_PORT"] != "80") {
		// $self_url .= ":".$_SERVER["SERVER_PORT"];
		// }
		// $self_url .= $_SERVER["REQUEST_URI"];

		// $request->RedirectUrl = $self_url;
		// $request->CancelUrl   = $self_url;
		// $request->Method = 'TokenPayment';

		// $eway_params = array();
		// if ($EwaySanboxMode) $eway_params['sandbox'] = true;
		// $service = new eWAY\RapidAPI($EwayAPIKey,$EwayAPIPassword , $eway_params);
		// $result = $service->CreateAccessCodesShared($request);

		// header("Location: " . $result->SharedPaymentUrl);


	}

}