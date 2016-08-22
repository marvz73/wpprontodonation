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

	public function __construct(){
		require_once('RapidAPI.php');//load the eway class

	}

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
				'label'	=> 'Eway Sandbox Mode'
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
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'enable_self_payment',
				'label'	=> 'Enable Self Payment'
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
				'name'	=> 'ewayapikey',
				'label'	=> 'eWay API Key',
				'required' 	=> true
			),
			array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'ewayapipassword',
				'label'	=> 'eWay API Password',
				'required' 	=> true
			),
		);

	}

	public function payment_process($ppd = array(),$campaign_data = array(), $class){

		global $wpdb;
		//------------------- Eway Self Payment -----------------------------//
		$payment_option_eway = (empty(get_option('payment_option_eway'))) ? "" : get_option('payment_option_eway');
		$enable_self_payment_value =  (isset($payment_option_eway['enable_self_payment'])) ? $payment_option_eway['enable_self_payment'] : '';
		//------------------- Eway Self Payment -----------------------------//
		
		if($enable_self_payment_value!='on'){
			$EwayAPIKey = $ppd['payment_info']->option['ewayapikey'];
			$EwayAPIPassword = $ppd['payment_info']->option['ewayapipassword'];
			$EwaySanboxMode = $ppd['payment_info']->option['ewaysandboxmode'];

			$request = new eWAY\CreateAccessCodesSharedRequest();
			$request->Customer->Reference = (string)$ppd['post_meta_id'];
			$request->Customer->CompanyName = (isset($ppd['companyName'])) ? $ppd['companyName'] : '';
			$request->Customer->Email = $ppd['email'];
			$request->Customer->FirstName = $ppd['first_name'];  
			$request->Customer->LastName = $ppd['last_name']; 
			$request->Customer->Phone = $ppd['phone'];
			$request->Customer->Street1 = $ppd['address'];
			$request->Customer->Country = $this->get_countrycode($ppd['country']); 
			$suburb_value =  (isset($ppd['suburb'])) ? $ppd['suburb'] : '';
			$request->Customer->City = $suburb_value;
			$state_value =  (isset($ppd['state'])) ? $ppd['state'] : '';
			$request->Customer->State = $state_value;
			$post_code_value =  (isset($ppd['post_code'])) ? $ppd['post_code'] : '';
			$request->Customer->PostalCode = $post_code_value;
			$TotalAmount = !empty($ppd['pd_custom_amount']) ? $ppd['pd_custom_amount'] : $ppd['pd_amount'];
			$request->Payment->TotalAmount = $TotalAmount .'00';
			$request->Payment->InvoiceReference = (string)$ppd['post_meta_id'];
			$request->CustomerReadOnly = true;
			$request->RedirectUrl = $ppd['redirectURL'];
			$request->CancelUrl   = $ppd['CancelUrl'];
			$request->Method = 'ProcessPayment';
			$eway_params = array();
			if ($EwaySanboxMode=='on') $eway_params['sandbox'] = true;
			$service = new eWAY\RapidAPI($EwayAPIKey, $EwayAPIPassword , $eway_params);
			$result = $service->CreateAccessCodesShared($request);

			require_once('tmpl/tmpl_payment_process.php');

	
		}else{
			//------------------- Eway Self Payment -----------------------------//
			$EwayAPIKey = $ppd['payment_info']->option['ewayapikey'];
			$EwayAPIPassword = $ppd['payment_info']->option['ewayapipassword'];
			$EwaySanboxMode = $ppd['payment_info']->option['ewaysandboxmode'];

			$request = new eWAY\CreateDirectPaymentRequest();
			$request->Customer->Reference = (string)$ppd['post_meta_id'];
			$request->Customer->CompanyName = (isset($ppd['companyName'])) ? $ppd['companyName'] : '';
			$request->Customer->Email = $ppd['email'];
			$request->Customer->FirstName = $ppd['first_name'];  
			$request->Customer->LastName = $ppd['last_name']; 
			$request->Customer->Phone = $ppd['phone'];
			$request->Customer->Street1 = $ppd['address'];
			$request->Customer->Country = $this->get_countrycode($ppd['country']);
			$suburb_value =  (isset($ppd['suburb'])) ? $ppd['suburb'] : '';
			$request->Customer->City = $suburb_value;
			$state_value =  (isset($ppd['state'])) ? $ppd['state'] : '';
			$request->Customer->State = $state_value;
			$post_code_value =  (isset($ppd['post_code'])) ? $ppd['post_code'] : '';
			$request->Customer->PostalCode = $post_code_value;
			$TotalAmount = !empty($ppd['pd_custom_amount']) ? $ppd['pd_custom_amount'] : $ppd['pd_amount'];
			$request->Payment->TotalAmount = $TotalAmount .'00';
			$request->Payment->InvoiceReference = (string)$ppd['post_meta_id'];
			
			$request->Method = 'ProcessPayment';
			$request->TransactionType = 'Purchase';


			$eway_name_on_card_value =  (isset($ppd['eway_name_on_card'])) ? $ppd['eway_name_on_card'] : '';
			$request->Customer->CardDetails->Name = $eway_name_on_card_value;
			$eway_card_number_value =  (isset($ppd['eway_card_number'])) ? $ppd['eway_card_number'] : '';
		    $request->Customer->CardDetails->Number = $eway_card_number_value;
		    $eway_expiry_month_value =  (isset($ppd['eway_expiry_month'])) ? $ppd['eway_expiry_month'] : '';
		    $request->Customer->CardDetails->ExpiryMonth = $eway_expiry_month_value;
		    $eway_expiry_year_value =  (isset($ppd['eway_expiry_year'])) ? $ppd['eway_expiry_year'] : '';
		    $request->Customer->CardDetails->ExpiryYear = $eway_expiry_year_value;
		    $eway_ccv_value =  (isset($ppd['eway_ccv'])) ? $ppd['eway_ccv'] : '';
		    $request->Customer->CardDetails->CVN = $eway_ccv_value ;

			$eway_params = array();
			if ($EwaySanboxMode=='on') $eway_params['sandbox'] = true;
		    $service = new eWAY\RapidAPI($EwayAPIKey,$EwayAPIPassword , $eway_params);
		    $result = $service->DirectPayment($request);
		    
		    $result->TypeOfPayment = 'SelfPayment';


		    if (!empty($result->Errors)) {

		    	$result->SharedPaymentUrl = $campaign_data['redirectErrorURL'].'&SP_Status='.$result->Errors;
		    	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_id = " .$ppd['post_meta_id']);
		    	require_once('tmpl/tmpl_payment_process.php');
		    	
		    } else {
		    	$result->SharedPaymentUrl = $ppd['redirectURL'].'&SP_Eway='.(string)$ppd['post_meta_id'];

		    	$campaign_data['statusCode'] = 1;

	    		$campaign_data['statusText'] = 'Transaction Approved';
	    		$card_details = array(
					'cardNumber'			=> $ppd['eway_card_number'],
					'nameOnCard'			=> $ppd['eway_name_on_card'],
					'expiryMonth'			=> $ppd['eway_expiry_month'],
					'expiryYear'			=> $ppd['eway_expiry_year'],
					'ccv'					=> $ppd['eway_ccv']
				);
	    		$payment_response = array(
					'AuthorisationCode'		=> $result->AuthorisationCode,
					'ResponseCode'			=> $result->ResponseCode,
					'ResponseMessage'		=> $result->ResponseMessage,
					'InvoiceNumber'			=> $result->Payment->InvoiceNumber,
					'InvoiceReference'		=> $result->Payment->InvoiceReference,
					'TotalAmount'			=> $result->Payment->TotalAmount,
					'TransactionID'			=> $result->TransactionID,
					'TransactionStatus'		=> $result->TransactionStatus,
					'TokenCustomerID'		=> $result->Customer->TokenCustomerID
				);
	    		$campaign_data['card_details'] = $card_details;
				$campaign_data['payment_response'] = $payment_response;
				
				//Salesforce sync response
				$opportunity = $class->set_salesforceDonation($campaign_data);

				if(isset($opportunity['status_code']) && ($opportunity['status_code'] == '201' || $opportunity['status_code'] == '200')){
					if(isset($opportunity['oppResult']) && $opportunity['oppResult'] != ''){
						$campaign_data['opportunityId'] = $opportunity['oppResult']->Id;
					}
					else{
						$campaign_data['opportunityId'] = $opportunity['recResult']->Id;
					}
					$campaign_data['opportunityMessage'] = $opportunity['message'];
					$campaign_data['opportunityStatus'] = $opportunity['status_code'];
				}
				
		

				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign_data))."' WHERE meta_id = " .$ppd['post_meta_id']);

		        require_once('tmpl/tmpl_payment_process.php');       
		    }
		    //------------------- Eway Self Payment -----------------------------//
		}

	}
	public function payment_self_payment_error_messages() {
		require_once('tmpl/tml_self_payment_process_error_messages.php');
	}
	public function payment_self_payment() {
		require_once('tmpl/tml_self_payment_process.php');
	}

	// Payment process complete
	public function payment_complete($response, $class, $SP_Eway){
		global $wpdb;
		
		if(empty($response)){
			return false;
		}

		$pm_settings = get_option( 'payment_option_'.strtolower($response['payment_gateway']));
		$EwayAPIKey = $pm_settings['ewayapikey'];
		$EwayAPIPassword = $pm_settings['ewayapipassword'];
		$EwaySanboxMode = $pm_settings['ewaysandboxmode'];
		$request = new eWAY\CreateAccessCodesSharedRequest();
		$eway_params = array();
		if ($EwaySanboxMode=='on') $eway_params['sandbox'] = true;
		$service = new eWAY\RapidAPI($EwayAPIKey, $EwayAPIPassword , $eway_params);

		// Query the transaction result.

		if(isset($response['AccessCode'])){
			$response = $service->TransactionQuery($response['AccessCode']);
			$transactionsResponse = $response->Transactions[0];
			$donor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . esc_html($transactionsResponse->InvoiceReference));

			$campaign = maybe_unserialize($donor[0]->meta_value);

			if(empty($campaign['payment_response']) && !array_key_exists('payment_response', $campaign))
			{
				$payment_response = array(
					'AuthorisationCode'		=> $transactionsResponse->AuthorisationCode,
					'ResponseCode'			=> $transactionsResponse->ResponseCode,
					'ResponseMessage'		=> $transactionsResponse->ResponseMessage,
					'InvoiceNumber'			=> $transactionsResponse->InvoiceNumber,
					'InvoiceReference'		=> $transactionsResponse->InvoiceReference,
					'TotalAmount'			=> $transactionsResponse->TotalAmount,
					'TransactionID'			=> $transactionsResponse->TransactionID,
					'TransactionStatus'		=> $transactionsResponse->TransactionStatus,
					'TokenCustomerID'		=> $transactionsResponse->TokenCustomerID
				);

				$campaign['payment_response'] = $payment_response;

				//Approve status code
				$ApproveTransaction = array('A2000', 'A2008', 'A2010', 'A2011', 'A2016');
				if(in_array($transactionsResponse->ResponseMessage, $ApproveTransaction)){
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

				//--
				}else{
					$campaign['statusCode'] = 0;
				}

				$campaign['statusText'] = esc_html($service->getMessage($transactionsResponse->ResponseMessage));
				
				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($transactionsResponse->InvoiceReference));

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
						$query = "Select id FROM Lead WHERE email = '". $campaign['email'] ."'";
						$result = new QueryResult( $this->class->sf_get_record( $query ) );

						if( $result['size'] == 0 ) {
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
		}else{
				//------------------- Eway Self Payment -----------------------------//
				$donor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . $SP_Eway);

				$campaign = maybe_unserialize($donor[0]->meta_value);
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
						$query = "Select id FROM Lead WHERE email = '". $campaign['email'] ."'";
						$result = new QueryResult( $this->class->sf_get_record( $query ) );

						if( $result['size'] == 0 ) {
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

				//------------------- Eway Self Payment -----------------------------//

			}
		
		
	}


	/*
	* This function will return the country code of the gevin country
	* @params : Philippines (String)
	* @return : PH (String) if exist 
	* @return : empty string '' if not exist
	*/
	function get_countrycode( $country ) {

		$countries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

		return array_search( $country, $countries );
	}

}