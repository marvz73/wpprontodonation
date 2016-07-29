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
	
		$EwayAPIKey = $ppd['payment_info']->option['ewayapikey'];
		$EwayAPIPassword = $ppd['payment_info']->option['ewayapipassword'];
		$EwaySanboxMode = $ppd['payment_info']->option['ewaysandboxmode'];

		$request = new eWAY\CreateAccessCodesSharedRequest();
			
		$request->Customer->Reference = (string)$ppd['post_meta_id'];
		$request->Customer->CompanyName = $ppd['companyName'];
		$request->Customer->Email = $ppd['email'];
		$request->Customer->FirstName = $ppd['first_name'];  
		$request->Customer->LastName = $ppd['last_name']; 
		$request->Customer->Phone = $ppd['phone'];
		$request->Customer->Street1 = $ppd['address'];
		$request->Customer->Country = $this->get_countrycode($ppd['country']); 
		$request->Customer->City = $ppd['suburb'];
		$request->Customer->State = $ppd['state'];
		$request->Customer->PostalCode = $ppd['post_code'];

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
	}

	// Payment process complete
	public function payment_complete($response){
		global $wpdb;

		$pm_settings = get_option( 'payment_option_'.strtolower($response['payment_gateway']));

		$EwayAPIKey = $pm_settings['ewayapikey'];
		$EwayAPIPassword = $pm_settings['ewayapipassword'];
		$EwaySanboxMode = $pm_settings['ewaysandboxmode'];
		$request = new eWAY\CreateAccessCodesSharedRequest();
		$eway_params = array();
		if ($EwaySanboxMode=='on') $eway_params['sandbox'] = true;
		$service = new eWAY\RapidAPI($EwayAPIKey, $EwayAPIPassword , $eway_params);
		// Query the transaction result.
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
			
			if(in_array($response['ResponseCode'], $ApproveTransaction))
			{
				$campaign['statusCode'] = 1;
			}
			else
			{
				$campaign['statusCode'] = 0;
			}

			$campaign['statusText'] = esc_html($service->getMessage($transactionsResponse->ResponseMessage));

			$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . esc_html($transactionsResponse->InvoiceReference));
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