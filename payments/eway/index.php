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

	public function payment_process($ppd = array()){

		$apiKey = $ppd['payment_info']->option['ewayapikey'];
		$apiPassword = $ppd['payment_info']->option['ewayapipassword'];
		$apiEndpoint = $ppd['payment_info']->option['ewaysandboxmode'];

		require_once('RapidAPI.php');

		$EwayAPIKey = 'F9802CyjrxE8qXwBAUKUvqqNrmjpNKxOUM981alJkFFePNWvVir8nDBcYfoJ5YEEg/uXVQ';
		$EwayAPIPassword = 'JfHGa0PY';
		$EwaySanboxMode = 1;

		$request = new eWAY\CreateAccessCodesSharedRequest();
		$request->Customer->TokenCustomerID = '911193411846';  // OPTIONAL

		$request->Customer->FirstName = 'Junjie';  // required
		$request->Customer->LastName = 'Canonio'; // required
		$request->Customer->Country = 'AU'; // required
		$request->Payment->TotalAmount = '69';

		$self_url = 'http';
		if (!empty($_SERVER['HTTPS'])) {
			$self_url .= "s";
		}
		$self_url .= "://" . $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_PORT"] != "80") {
		$self_url .= ":".$_SERVER["SERVER_PORT"];
		}
		$self_url .= $_SERVER["REQUEST_URI"];

		$request->RedirectUrl = $self_url;
		$request->CancelUrl   = $self_url;
		$request->Method = 'TokenPayment';

		$eway_params = array();
		if ($EwaySanboxMode) $eway_params['sandbox'] = true;
		$service = new eWAY\RapidAPI($EwayAPIKey,$EwayAPIPassword , $eway_params);
		$result = $service->CreateAccessCodesShared($request);
		
		
		require_once('tmpl/tmpl_payment_process.php');
		
		
		// require_once('RapidAPI.php');
		// $request = new eWAY\CreateAccessCodesSharedRequest();

		// $request->Customer->FirstName = $ppd['first_name'];  
		// $request->Customer->LastName = $ppd['last_name']; 
		// $request->Customer->Country = $ppd['country']; 
		// $request->Payment->TotalAmount = !empty($ppd['pd_custom_amount']) ? $ppd['pd_custom_amount'] : $ppd['pd_amount'];


		// $self_url = 'http';
		// if (!empty($_SERVER['HTTPS'])) {
		//     $self_url .= "s";
		// }
		// $self_url .= "://" . $_SERVER["SERVER_NAME"];
		// if ($_SERVER["SERVER_PORT"] != "80") {
		// 	$self_url .= ":".$_SERVER["SERVER_PORT"];
		// }
		// $self_url .= $_SERVER["REQUEST_URI"];


		// $RedirectUrl = $self_url . '/?p=' . $option['ThankYouPageMessagePage'];
		// $CancelUrl = get_home_url() . '/?p=' . $option['ThankYouPageMessagePage'] . '&PaymentReference=C';
		
		// $request->RedirectUrl = $RedirectUrl;
		// $request->CancelUrl   = $CancelUrl;
		// $request->Method = 'TokenPayment';


		// $service = new eWAY\RapidAPI($EwayAPIKey,$EwayAPIPassword , $EwaySanboxMode);
		// $result = $service->CreateAccessCodesShared($request);

		// header("Location: " . $result->SharedPaymentUrl);
		// require_once('tmpl/tmpl_payment_process.php');

	}

	// Payment process complete
	public function payment_complete($campaign, $response){
		global $wpdb;


		// $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($response['PaymentReference']));

	}

}