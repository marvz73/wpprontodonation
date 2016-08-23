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
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'enable_ajax_payment',
				'label' => 'Enable Self Payment'
			),
			array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'url',
				'label' => 'URL'
			),
			array(
				'type'  	=> 'text',
				'value' 	=> '',
				'name'		=> 'sf_gateway_id',
				'label'		=> 'Salesforce Gateway ID',
				'required' 	=> true
			),
		 	array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'endpoint',
				'label' => 'Ezidebit Endpoint'
			),
		 	array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'publickey',
				'label' => 'Ezidebit Public Key'
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
			'CompanyName'			=> (isset($ppd['companyName'])) ? $ppd['companyName'] : '',
			'FirstName'				=> $ppd['first_name'],
			'LastName'				=> $ppd['last_name'],
			'EmailAddress'			=> $ppd['email'],
			'MobilePhoneNumber'		=> (isset($ppd['phone'])) ? $ppd['phone'] : '',
			'PaymentAmount'			=> !empty($ppd['pd_custom_amount']) ? $ppd['pd_custom_amount'] : $ppd['pd_amount'],
			'ShowDisabledInputs'	=> 0,
			'RedirectMethod'		=> 'GET',
			'RedirectURL'			=> $ppd['redirectURL'],
			'PaymentReference'		=> $ppd['post_meta_id']
		);

		require_once('tmpl/tmpl_payment_process.php');

	}

	public function payment_self_payment( $ajax_campaign_id ) {
		require_once('tmpl/tml_self_payment_process.php');
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
				$ApproveTransaction = array('00', '08', '10', '11', '16', '77', '000', '003');

				if(in_array($response['ResultCode'], $ApproveTransaction)){
					$campaign['statusCode'] = 1;
				}else{
					$campaign['statusCode'] = 0;
				}

				$campaign['statusText'] = esc_html($response['ResultText']);

				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($response['PaymentReference']));

				$class->pronto_donation_user_notification($campaign);

				// add subscriber to alo newsletter plugin
				if( isset( $campaign['sign_newsletter'] ) && $campaign['sign_newsletter'] == 'on' ) {

					$fields['email'] = $campaign['email'];
					$fields['name'] = $campaign['first_name'] .' '. $campaign['last_name'];
					$lang = '';
					$unikey = substr(md5(uniqid(rand(), true)), 0,24);
					if ( alo_em_add_subscriber( $fields, 1, $lang ) == "OK" )
					{
					    $subscriber_id = alo_em_is_subscriber ( $campaign['email'] );
					    alo_em_add_subscriber_to_list ( $subscriber_id, $unikey );
					}
					
					// create a lead record if Newsletter sign-up request on SF Lead
					if( isset( get_option('pronto_donation_settings')['NewsLetterLead'] ) && get_option('pronto_donation_settings')['NewsLetterLead'] == 1 ) {
						$sf_data = array();


						$wpOptions = get_option('pronto_donation_settings', 0);

						if(isset($wpOptions['SalesforceUsername']) && $wpOptions['SalesforceUsername'] != '' &&
							isset($wpOptions['SalesforcePassword']) && $wpOptions['SalesforcePassword'] != '' &&
							isset($wpOptions['SecurityToken']) && $wpOptions['SecurityToken'] != '')
						{
							$query = "Select id FROM Lead WHERE email = '". $campaign['email'] ."'";
							$result = new QueryResult( $class->sf_get_record( $query ) );

							if( $result->size == 0 ) {
								$user_data = array(
									'Company' => ( isset( $campaign['companyName'] ) ) ? $campaign['companyName'] : $campaign['first_name'] .' '. $campaign['last_name'] ,
									'FirstName' => $campaign['first_name'],
									'LastName' => $campaign['last_name'],
									'Email' => $campaign['email'],
									'Status' => 'Newsletter sign-up request'
								);

								array_push( $sf_data, $user_data );
								$class->sf_create_record( $sf_data, 'Lead' );
							}
						}
					}
				}

			}
		} else if(!isset($response['PaymentReference']) && isset($response['DonationMetaID'])) {

			$donor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . esc_html($response['DonationMetaID']));
			$campaign = maybe_unserialize($donor[0]->meta_value);

			$payment_response = array();
			if(!isset($campaign['payment_response'])) {
				$restricted_keys = array( 'payment_gateway', 'DonationMetaID' );

 				foreach ($response as $key => $resp_val) {
 					if( !in_array( $key, $restricted_keys) ) {
 						$payment_response[$key] = esc_html( $resp_val );
 					}
 				}
			}

			$campaign['payment_response'] = $payment_response;

			//Approve status code
			$ApproveTransaction = array('00', '08', '10', '11', '16', '77', '000', '003');

			if( in_array($response['PaymentResultCode'], $ApproveTransaction)){
				$campaign['statusCode'] = 1;

				//Salesforce sync response
				$opportunity = $class->set_salesforceDonation($campaign);
				if(isset($opportunity['status_code']) && ($opportunity['status_code'] == '201' || $opportunity['status_code'] == '200')){
					if(isset($opportunity['oppResult']) && $opportunity['oppResult'] != ''){
						$campaign['opportunityId'] = $opportunity['oppResult']->Id;
					}
					else{
						$campaign['opportunityId'] = $opportunity['recResult']->Id;
					}
					$campaign['opportunityMessage'] = $opportunity['message'];
					$campaign['opportunityStatus'] = $opportunity['status_code'];
				}
			}else{
				$campaign['statusCode'] = 0;
			}

			$campaign['statusText'] = esc_html($response['PaymentResultText']);

			$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($response['DonationMetaID']));

			$class->pronto_donation_user_notification($campaign);

			
			// add subscriber to alo newsletter plugin
			if( isset( $campaign['sign_newsletter'] ) && $campaign['sign_newsletter'] == 'on' ) {

				$fields['email'] = $campaign['email'];
				$fields['name'] = $campaign['first_name'] .' '. $campaign['last_name'];
				$lang = '';
				$unikey = substr(md5(uniqid(rand(), true)), 0,24);
				if ( alo_em_add_subscriber( $fields, 1, $lang ) == "OK" )
				{
				    $subscriber_id = alo_em_is_subscriber ( $campaign['email'] );
				    alo_em_add_subscriber_to_list ( $subscriber_id, $unikey );
				}

				// create a lead record if Newsletter sign-up request on SF Lead
				if( isset( get_option('pronto_donation_settings')['NewsLetterLead'] ) && get_option('pronto_donation_settings')['NewsLetterLead'] == 1 ) {
					$sf_data = array();

					$wpOptions = get_option('pronto_donation_settings', 0);

					if(isset($wpOptions['SalesforceUsername']) && $wpOptions['SalesforceUsername'] != '' &&
						isset($wpOptions['SalesforcePassword']) && $wpOptions['SalesforcePassword'] != '' &&
						isset($wpOptions['SecurityToken']) && $wpOptions['SecurityToken'] != '')
					{
						$query = "Select id FROM Lead WHERE email = '". $campaign['email'] ."'";
						$result = new QueryResult( $class->sf_get_record( $query ) );

						if( $result->size == 0 ) {
							$user_data = array(
								'Company' => ( isset( $campaign['companyName'] ) ) ? $campaign['companyName'] : $campaign['first_name'] .' '. $campaign['last_name'] ,
								'FirstName' => $campaign['first_name'],
								'LastName' => $campaign['last_name'],
								'Email' => $campaign['email'],
								'Status' => 'Newsletter sign-up request'
							);

							array_push( $sf_data, $user_data );
							$class->sf_create_record( $sf_data, 'Lead' );
						}
					}
				}
			}

		}
	}
}





