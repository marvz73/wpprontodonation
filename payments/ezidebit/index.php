<?php
/*
 * Name: Marvin Aya-ay
 * Email: marvin@alphasys.com.au
 * Desc: eWay payment gateway
 * Date: July 20, 2016
 */

class ezidebit{

	//Payment Details
	var $payment = array(
		'logo'					=> 'logo.png',
		'payment_name' 			=> 'Ezidebit',
		'payment_description' 	=> 'This is a payment description here.',
		'url'					=> ''
	);


	function get_payment_name(){
		echo $this->payment['payment_name'];
	}

	function get_payment_description(){
		echo $this->payment['payment_description'];
	}

	function get_payment_logo(){
		echo plugins_url( $this->payment['logo'], __FILE__ );
	}

	function get_form_fields(){

		return $form = array(
			// array(
			// 	'type'  => 'checkbox',
			// 	'value' => false,
			// 	'name'	=> 'sandboxmode',
			// 	'label'	=> 'Sanbox Mode'
			// ),
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
				'name'	=> 'url',
				'label' => 'URL'
			),
		);

	}

	public function payment_process($ppd = array()){

		// ShowDisabledInputs	
		// RedirectMethod	
		// RedirectURL	
		// PaymentReference	

		$url = $ppd['payment_info']->option['url'];

		$fields = array(
			'Type'					=> $ppd['donor_type'],
			'CompanyName'			=> $ppd['companyName'],
			'FirstName'				=> $ppd['first_name'],
			'LastName'				=> $ppd['last_name'],
			'EmailAddress'			=> $ppd['email'],
			'MobilePhoneNumber'		=> $ppd['phone'],
			'PaymentAmount'			=> !empty($ppd['pd_custom_amount']) ? $ppd['pd_custom_amount'] : $ppd['pd_amount'],
			'ShowDisabledInputs'	=> 0,
			'RedirectMethod'		=> 'GET',
			'RedirectURL'			=> $ppd['redirectURL'],
			'PaymentReference'		=> $ppd['post_meta_id']
		);

		require_once('tmpl/tmpl_payment_process.php');

	}

	// Payment process complete
	public function payment_complete($response, $class){
		global $wpdb;
		
		if(!empty($response['PaymentReference']))
		{
			$donor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . esc_html($response['PaymentReference']));
			
			$campaign = maybe_unserialize($donor[0]->meta_value);
		
			if(empty($campaign['payment_response']) && !array_key_exists('payment_response', $campaign))
			{

				$payment_response = array(
					'PaymentReference'			=> esc_html($response['PaymentReference']),
					'BillerID'					=> esc_html($response['BillerID']),
					'TransactionID'				=> esc_html($response['TransactionID']),
					'PaymentAmount'				=> esc_html($response['PaymentAmount']),
					'ResultCode' 				=> esc_html($response['ResultCode']),
					'ResultText'				=> esc_html($response['ResultText']),
					'TransactionFeeCustomer'	=> esc_html($response['TransactionFeeCustomer'])
				);
				
				$campaign['payment_response'] = $payment_response;

				//Approve status code
				$ApproveTransaction = array('00', '08', '10', '11', '16', '77', '000','003');

				if(in_array($response['ResultCode'], $ApproveTransaction)){
					$campaign['statusCode'] = 1;
				}else{
					$campaign['statusCode'] = 0;
				}

				$campaign['statusText'] = esc_html($response['ResultText']);

				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($response['PaymentReference']));

				$class->pronto_donation_user_notification($campaign);
			}
		}

	}
}





