<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/admin/partials
 */



//================= Donation Settings =================//
global $wpdb;
if ( isset($_GET['page']) ) {
	if($_GET['page']=='donation-settings'){

		//================ Options for Country and Currency ==============//
		$currency_symbols = array(
			'AED' => '&#1583;.&#1573;', // ?
			'AFN' => '&#65;&#102;',
			'ALL' => '&#76;&#101;&#107;',
			'AMD' => '',
			'ANG' => '&#402;',
			'AOA' => '&#75;&#122;', // ?
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => '&#402;',
			'AZN' => '&#1084;&#1072;&#1085;',
			'BAM' => '&#75;&#77;',
			'BBD' => '&#36;',
			'BDT' => '&#2547;', // ?
			'BGN' => '&#1083;&#1074;',
			'BHD' => '.&#1583;.&#1576;', // ?
			'BIF' => '&#70;&#66;&#117;', // ?
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => '&#36;&#98;',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTN' => '&#78;&#117;&#46;', // ?
			'BWP' => '&#80;',
			'BYR' => '&#112;&#46;',
			'BZD' => '&#66;&#90;&#36;',
			'CAD' => '&#36;',
			'CDF' => '&#70;&#67;',
			'CHF' => '&#67;&#72;&#70;',
			'CLF' => '', // ?
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUP' => '&#8396;',
			'CVE' => '&#36;', // ?
			'CZK' => '&#75;&#269;',
			'DJF' => '&#70;&#100;&#106;', // ?
			'DKK' => '&#107;&#114;',
			'DOP' => '&#82;&#68;&#36;',
			'DZD' => '&#1583;&#1580;', // ?
			'EGP' => '&#163;',
			'ETB' => '&#66;&#114;',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;', // ?
			'GHS' => '&#162;',
			'GIP' => '&#163;',
			'GMD' => '&#68;', // ?
			'GNF' => '&#70;&#71;', // ?
			'GTQ' => '&#81;',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => '&#76;',
			'HRK' => '&#107;&#110;',
			'HTG' => '&#71;', // ?
			'HUF' => '&#70;&#116;',
			'IDR' => '&#82;&#112;',
			'ILS' => '&#8362;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1583;', // ?
			'IRR' => '&#65020;',
			'ISK' => '&#107;&#114;',
			'JEP' => '&#163;',
			'JMD' => '&#74;&#36;',
			'JOD' => '&#74;&#68;', // ?
			'JPY' => '&#165;',
			'KES' => '&#75;&#83;&#104;', // ?
			'KGS' => '&#1083;&#1074;',
			'KHR' => '&#6107;',
			'KMF' => '&#67;&#70;', // ?
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1583;.&#1603;', // ?
			'KYD' => '&#36;',
			'KZT' => '&#1083;&#1074;',
			'LAK' => '&#8365;',
			'LBP' => '&#163;',
			'LKR' => '&#8360;',
			'LRD' => '&#36;',
			'LSL' => '&#76;', // ?
			'LTL' => '&#76;&#116;',
			'LVL' => '&#76;&#115;',
			'LYD' => '&#1604;.&#1583;', // ?
			'MAD' => '&#1583;.&#1605;.', //?
			'MDL' => '&#76;',
			'MGA' => '&#65;&#114;', // ?
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => '&#75;',
			'MNT' => '&#8366;',
			'MOP' => '&#77;&#79;&#80;&#36;', // ?
			'MRO' => '&#85;&#77;', // ?
			'MUR' => '&#8360;', // ?
			'MVR' => '.&#1923;', // ?
			'MWK' => '&#77;&#75;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => '&#77;&#84;',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => '&#67;&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#65020;',
			'PAB' => '&#66;&#47;&#46;',
			'PEN' => '&#83;&#47;&#46;',
			'PGK' => '&#75;', // ?
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#71;&#115;',
			'QAR' => '&#65020;',
			'RON' => '&#108;&#101;&#105;',
			'RSD' => '&#1044;&#1080;&#1085;&#46;',
			'RUB' => '&#1088;&#1091;&#1073;',
			'RWF' => '&#1585;.&#1587;',
			'SAR' => '&#65020;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#163;', // ?
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => '&#76;&#101;', // ?
			'SOS' => '&#83;',
			'SRD' => '&#36;',
			'STD' => '&#68;&#98;', // ?
			'SVC' => '&#36;',
			'SYP' => '&#163;',
			'SZL' => '&#76;', // ?
			'THB' => '&#3647;',
			'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
			'TMT' => '&#109;',
			'TND' => '&#1583;.&#1578;',
			'TOP' => '&#84;&#36;',
			'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => '',
			'UAH' => '&#8372;',
			'UGX' => '&#85;&#83;&#104;',
			'USD' => '&#36;',
			'UYU' => '&#36;&#85;',
			'UZS' => '&#1083;&#1074;',
			'VEF' => '&#66;&#115;',
			'VND' => '&#8363;',
			'VUV' => '&#86;&#84;',
			'WST' => '&#87;&#83;&#36;',
			'XAF' => '&#70;&#67;&#70;&#65;',
			'XCD' => '&#36;',
			'XDR' => '',
			'XOF' => '',
			'XPF' => '&#70;',
			'YER' => '&#65020;',
			'ZAR' => '&#82;',
			'ZMK' => '&#90;&#75;', // ?
			'ZWL' => '&#90;&#36;',
		);

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
		//================ Options for Country and Currency ==============//

		
		//================ Get Post Page for Messages ==============//
		$thank_you_page_message_post_id = '';
		$thank_you_page_message_postTitle = 'Thank You';
		$post_id_A = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_type = 'pronto_donation' AND post_title = '" . $thank_you_page_message_postTitle . "'" );

	    if(empty($post_id_A)||$post_id_A==null){}
	    else{
	    	$thank_you_page_message_post_id = $post_id_A;
	    }



	   	$cancel_page_message_post_id = '';
		$cancel_page_message_postTitle = 'Cancelled';
		$post_id_B = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_type = 'pronto_donation' AND post_title = '" . $cancel_page_message_postTitle . "'" );

	    if(empty($post_id_B)||$post_id_B==null){}
	    else{
	    	$cancel_page_message_post_id = $post_id_B;
	    }


	    $info_on_offline_payment_panel_post_id = '';

	   	$instructions_emailed_to_offline_donor_before_payment_post_id = '';

	    //================ Get Post Page for Messages ==============//



		if (isset($_POST['submit'])) {

			$from_style = (empty($_POST['from_style'])) ? "" : $_POST['from_style'];
			$form_class = (empty($_POST['from_class'])) ? "" : $_POST['from_class'];
			$button_class = (empty($_POST['button_class'])) ? "" : $_POST['button_class'];
			$input_field_class = (empty($_POST['input_field_class'])) ? "" : $_POST['input_field_class'];
			$edit_button_caption = (empty($_POST['edit_button_caption'])) ? "" : $_POST['edit_button_caption'];

			$set_currency = (empty($_POST['set_currency'])) ? "" : $_POST['set_currency'];
			$set_country = (empty($_POST['set_country'])) ? "" : $_POST['set_country'];
			$enable_address_validation = (empty($_POST['enable_address_validation'])) ? "" : $_POST['enable_address_validation'];
			$google_geocode_api_key ='';
			if($_POST['adress_validation_status']=='INVALID'||$_POST['adress_validation_status']=='EMPTY'){
				$google_geocode_api_key ='';
			}else{
				$google_geocode_api_key = (empty($_POST['google_geocode_api_key'])) ? "" : $_POST['google_geocode_api_key'];
			}
			

			$google_recaptcha_enable = (empty($_POST['google_recaptcha_enable'])) ? "" : $_POST['google_recaptcha_enable'];	
			$google_recaptcha_site_key = (empty($_POST['google_recaptcha_site_key'])) ? "" : $_POST['google_recaptcha_site_key'];	
			$google_recaptcha_secret_key = (empty($_POST['google_recaptcha_secret_key'])) ? "" : $_POST['google_recaptcha_secret_key'];	


			$email_to_be_notify = (empty($_POST['email_to_be_notify'])) ? "" : $_POST['email_to_be_notify'];
			$email_address = (empty($_POST['email_address'])) ? "" : $_POST['email_address'];
			$email_name = (empty($_POST['email_name'])) ? "" : $_POST['email_name'];	

			$client_id = (empty($_POST['client_id'])) ? "" : $_POST['client_id'];
			$client_secret = (empty($_POST['client_secret'])) ? "" : $_POST['client_secret'];	
			$redirect_uri = (empty($_POST['redirect_uri'])) ? "" : $_POST['redirect_uri'];
			$security_token	= (empty($_POST['security_token'])) ? "" : $_POST['security_token'];
			$login_uri = (empty($_POST['login_uri'])) ? "" : $_POST['login_uri'];	
			$salesforce_url = (empty($_POST['salesforce_url'])) ? "" : $_POST['salesforce_url'];
			$salesforce_username = (empty($_POST['salesforce_username'])) ? "" : $_POST['salesforce_username'];
			$salesforce_password = (empty($_POST['salesforce_password'])) ? "" : $_POST['salesforce_password'];

			$thank_you_page_message_page = (empty($thank_you_page_message_post_id)) ? "" : $thank_you_page_message_post_id;
			$thank_you_page_message = (empty($_POST['thank_you_page_message'])) ? "" : $_POST['thank_you_page_message'];

			$cancel_page_message_page = (empty($cancel_page_message_post_id)) ? "" : $cancel_page_message_post_id;
			$cancel_page_message = (empty($_POST['cancel_page_message'])) ? "" : $_POST['cancel_page_message'];

			$thank_you_email_message = (empty($_POST['thank_you_email_message'])) ? "" : $_POST['thank_you_email_message'];

			// $info_on_offline_payment_panel_page = (empty($info_on_offline_payment_panel_post_id)) ? "" : $info_on_offline_payment_panel_post_id;
			// $info_on_offline_payment_panel_enable_offline_payment = (empty($_POST['enable_offline_payment'])) ? "" : $_POST['enable_offline_payment'];
			// $info_on_offline_payment_panel = (empty($_POST['info_on_offline_payment_panel'])) ? "" : $_POST['info_on_offline_payment_panel'];

			// $instructions_emailed_to_offline_donor_before_payment_page = (empty($instructions_emailed_to_offline_donor_before_payment_post_id)) ? "" : $instructions_emailed_to_offline_donor_before_payment_post_id;
			// $instructions_emailed_to_offline_donor_before_payment = (empty($_POST['instructions_emailed_to_offline_donor_before_payment'])) ? "" : $_POST['instructions_emailed_to_offline_donor_before_payment'];				


			$pronto_donation_settings = array(
				'FormStyle'     => stripslashes($from_style),
				'FormClass'     => stripslashes($form_class),
				'ButtonClass'      => stripslashes($button_class),
				'InputFieldClass'   => stripslashes($input_field_class),
				'EditButtonCaption'   => stripslashes($edit_button_caption),

				'SetCurrencySymbol'   => $currency_symbols[$set_currency],
				'SetCurrencyCode'   => stripslashes($set_currency),
				'SetCountry' => stripslashes($set_country),
				'EnableAddressValidation' => stripslashes($enable_address_validation),
				'GoogleGeocodeAPIKey' => stripslashes($google_geocode_api_key),

				'GoogleReCaptchaEnable' => stripslashes($google_recaptcha_enable),
				'GoogleReCaptchaSiteKey' => stripslashes($google_recaptcha_site_key),
				'GoogleReCaptchaSecretKey' => stripslashes($google_recaptcha_secret_key),

				'EmailToBeNotify' => stripslashes(str_replace("/","",$email_to_be_notify)),
				'EmailAddress' => stripslashes(str_replace("/","",$email_address)),
				'EmailName' => stripslashes(str_replace("/","",$email_name)),

				'ClientId' => stripslashes($client_id),
				'ClientSecret' => stripslashes($client_secret),
				'RedirectURI' => stripslashes($redirect_uri),
				'SecurityToken' => sanitize_text_field(stripslashes($security_token)),
				'LoginURI' => stripslashes($login_uri),
				'SalesforceURL' => stripslashes($salesforce_url),
				'SalesforceUsername' => stripslashes($salesforce_username),
				'SalesforcePassword' => stripslashes($salesforce_password),

				'ThankYouPageMessagePage' => stripslashes($thank_you_page_message_page),
				'ThankYouPageMessage' => stripslashes($thank_you_page_message),

				'CancelPageMessagePage' => stripslashes($cancel_page_message_page),
				'CancelPageMessage' => stripslashes($cancel_page_message),

				'ThankYouMailMessage' => stripslashes($thank_you_email_message)

				// 'InfoOnOfflinePaymentPanelPage' => stripslashes($info_on_offline_payment_panel_page),
				// 'InfoOnOfflinePaymentPanelEnableOfflinePayment' => stripslashes($info_on_offline_payment_panel_enable_offline_payment),
				// 'InfoOnOfflinePaymentPanel' => stripslashes($info_on_offline_payment_panel),

				// 'InstructionsEmailedToOfflineDonorBeforePaymentPage' => stripslashes($instructions_emailed_to_offline_donor_before_payment_page),
				// 'InstructionsEmailedToOfflineDonorBeforePayment' => stripslashes($instructions_emailed_to_offline_donor_before_payment)

			); 
			update_option('pronto_donation_settings' , $pronto_donation_settings); //On form submit all value is stored on an array and then stored in option named 'pronto_donation_settings'

			

			if($thank_you_page_message_page==""||empty($thank_you_page_message_page)){}
			else{

				// Update content of page selected on 'instructions emailed to offline donor before payment'
				$my_post = array(
				  'ID'           => $thank_you_page_message_page,
				  'post_content' => stripslashes($thank_you_page_message)
				);
				// Update the post into the database
				wp_update_post( $my_post );
			}


			// if($info_on_offline_payment_panel_page==""||empty($info_on_offline_payment_panel_page)){}
			// else{

			// 	// Update content of page selected on 'instructions emailed to offline donor before payment'
			// 	$my_post = array(
			// 	  'ID'           => $info_on_offline_payment_panel_page,
			// 	  'post_content' => stripslashes($info_on_offline_payment_panel)
			// 	);
			// 	// Update the post into the database
			// 	wp_update_post( $my_post );
			// }


			// if($instructions_emailed_to_offline_donor_before_payment_page==""||empty($instructions_emailed_to_offline_donor_before_payment_page)){}
			// else{

			// 	// Update content of page selected on 'instructions emailed to offline donor before payment'
			// 	$my_post = array(
			// 	  'ID'           => $instructions_emailed_to_offline_donor_before_payment_page,
			// 	  'post_content' => stripslashes($instructions_emailed_to_offline_donor_before_payment)
			// 	);
			// 	// Update the post into the database
			// 	wp_update_post( $my_post );
			// }



		}	

		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');

		$from_style = (empty($pronto_donation_settings['FormStyle'])) ? "" : $pronto_donation_settings['FormStyle'];
		$form_class = (empty($pronto_donation_settings['FormClass'])) ? "" : $pronto_donation_settings['FormClass'];
		$button_class = (empty($pronto_donation_settings['ButtonClass'])) ? "" : $pronto_donation_settings['ButtonClass'];
		$input_field_class = (empty($pronto_donation_settings['InputFieldClass'])) ? "" : $pronto_donation_settings['InputFieldClass'];
		$edit_button_caption = (empty($pronto_donation_settings['EditButtonCaption'])) ? "" : $pronto_donation_settings['EditButtonCaption'];

		$set_currency = (empty($pronto_donation_settings['SetCurrencyCode'])) ? "" : $pronto_donation_settings['SetCurrencyCode']; 
		$set_country = (empty($pronto_donation_settings['SetCountry'])) ? "" : $pronto_donation_settings['SetCountry']; 	
		$enable_address_validation = (empty($pronto_donation_settings['EnableAddressValidation'])) ? "" : $pronto_donation_settings['EnableAddressValidation'];
		$google_geocode_api_key = (empty($pronto_donation_settings['GoogleGeocodeAPIKey'])) ? "" : $pronto_donation_settings['GoogleGeocodeAPIKey'];
		

		$google_recaptcha_enable = (empty($pronto_donation_settings['GoogleReCaptchaEnable'])) ? "" : $pronto_donation_settings['GoogleReCaptchaEnable']; 
		$google_recaptcha_site_key = (empty($pronto_donation_settings['GoogleReCaptchaSiteKey'])) ? "" : $pronto_donation_settings['GoogleReCaptchaSiteKey']; 	
		$google_recaptcha_secret_key = (empty($pronto_donation_settings['GoogleReCaptchaSecretKey'])) ? "" : $pronto_donation_settings['GoogleReCaptchaSecretKey'];

		$email_to_be_notify = (empty($pronto_donation_settings['EmailToBeNotify'])) ? "" : $pronto_donation_settings['EmailToBeNotify']; 
		$email_address = (empty($pronto_donation_settings['EmailAddress'])) ? "" : $pronto_donation_settings['EmailAddress']; 
		$email_name = (empty($pronto_donation_settings['EmailName'])) ? "" : $pronto_donation_settings['EmailName']; 	

		$client_id = (empty($pronto_donation_settings['ClientId'])) ? "" : $pronto_donation_settings['ClientId'];
		$client_secret = (empty($pronto_donation_settings['ClientSecret'])) ? "" : $pronto_donation_settings['ClientSecret'];	
		$redirect_uri = (empty($pronto_donation_settings['RedirectURI'])) ? "" : $pronto_donation_settings['RedirectURI'];
		$security_token = (empty($pronto_donation_settings['SecurityToken'])) ? "" : $pronto_donation_settings['SecurityToken'];	
		$login_uri = (empty($pronto_donation_settings['LoginURI'])) ? "" : $pronto_donation_settings['LoginURI'];	
		$salesforce_url = (empty($pronto_donation_settings['SalesforceURL'])) ? "" : $pronto_donation_settings['SalesforceURL'];
		$salesforce_username = (empty($pronto_donation_settings['SalesforceUsername'])) ? "" : $pronto_donation_settings['SalesforceUsername'];
		$salesforce_password = (empty($pronto_donation_settings['SalesforcePassword'])) ? "" : $pronto_donation_settings['SalesforcePassword'];

		$thank_you_page_message_page = (empty($pronto_donation_settings['ThankYouPageMessagePage'])) ? "" : $pronto_donation_settings['ThankYouPageMessagePage'];
		$thank_you_page_message = (empty($pronto_donation_settings['ThankYouPageMessage'])) ? "" : $pronto_donation_settings['ThankYouPageMessage'];

		$cancel_page_message_page = (empty($pronto_donation_settings['CancelPageMessagePage'])) ? "" : $pronto_donation_settings['CancelPageMessagePage'];
		$cancel_page_message = (empty($pronto_donation_settings['CancelPageMessage'])) ? "" : $pronto_donation_settings['CancelPageMessage'];	

		$thank_you_email_message = (empty($pronto_donation_settings['ThankYouMailMessage'])) ? "" : $pronto_donation_settings['ThankYouMailMessage'];

		// $info_on_offline_payment_panel_page = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanelPage'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanelPage'];
		// $info_on_offline_payment_panel_enable_offline_payment = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanelEnableOfflinePayment'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanelEnableOfflinePayment'];
		// $info_on_offline_payment_panel = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanel'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanel'];

		// $instructions_emailed_to_offline_donor_before_payment_page = (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'];	
		// $instructions_emailed_to_offline_donor_before_payment = (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'];


		?>

<div class="wrap">
	<h1>Pronto Settings</h1>
	<form method="post">
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Campaign From</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="from_style">Form Style</label>
						</th>
						<td> 
							<select name="from_style" id="from_style">
								<option value="1" <?php if($from_style=='1'){echo'selected';}?>>Style 1</option>
								<option value="2" <?php if($from_style=='2'){echo'selected';}?>>Style 2</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="from_class">Form Class</label>
						</th>
						<td>
							<input type="text" name="from_class" id="from_class" class="regular-text" value="<?php echo $form_class; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="button_class">Button Class</label>
						</th>
						<td>
							<input type="text" name="button_class" id="button_class" class="regular-text" value="<?php echo $button_class; ?>">
						</td>						    						
					</tr>
					<tr>
						<th scope="row">
							<label for="input_field_class">Input Field Class</label>
						</th>
						<td>
							<input type="text" name="input_field_class" id="input_field_class" class="regular-text" value="<?php echo $input_field_class; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="edit_button_caption">Edit Button Caption</label>
						</th>
						<td>
							<input type="text" name="edit_button_caption" id="edit_button_caption" class="regular-text" value="<?php echo $edit_button_caption; ?>">
						</td>
					</tr>					
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Country and Currency</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="set_currency">Set Currency</label>
						</th>
						<td>
						    <select id="set_currency" name="set_currency">
						   	<?php
						    foreach ($currency_symbols as $key => $value) {
						    	?>	
								<option value="<?php echo $key;?>"<?php if($set_currency==$key){echo'selected';}?>><?php echo $key;?></option>
								<?php
						   	}
						    	
						    ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="set_country">Set Country</label>
						</th>
						<td>
						    <select id="set_country" name="set_country">
						    <?php
						    foreach ($countries as $key => $value) {
						    	?>	
						    	<option value="<?php echo $key;?>"<?php if($set_country==$key){echo'selected';}?>><?php echo $value;?></option>
						    	<?php
						   	}
						    	
						    ?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Google Address Validation</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="enable_address_validation">Address Validation</label>
						</th>
						<td>
							<label for="enable_address_validation">
								<input name="enable_address_validation" type="checkbox" id="enable_address_validation" value="1" <?php if($enable_address_validation==1){echo'checked';}?>>
							Enable Validation
							</label>	
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="google_geocode_api_key">Google Api Key</label>
						</th>
						<td>
							<input name="google_geocode_api_key" placeholder="API Key Here" type="text" class="regular-text" id="google_geocode_api_key" value="<?php echo $google_geocode_api_key; ?>">
							<input id="adress_validation_status" name="adress_validation_status" style="border: none;" value="" class="regular-text" readonly>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Google reCaptcha</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="google_recaptcha_enable">Enable reCaptcha</label>
						</th>
						<td>
							<label for="google_recaptcha_enable">
								<input name="google_recaptcha_enable" type="checkbox" id="google_recaptcha_enable" value="1" <?php if($google_recaptcha_enable==1){echo'checked';}?>>
							Enable
							</label>	
						</td>
					</tr>	
					<tr>
						<th scope="row">
							<label for="google_recaptcha_site_key">Site Key</label>
						</th>
						<td>
						    <input name="google_recaptcha_site_key" type="text" id="google_recaptcha_site_key" class="regular-text" value="<?php echo $google_recaptcha_site_key; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="google_recaptcha_secret_key">Secret Key</label>
						</th>
						<td>
						    <input name="google_recaptcha_secret_key" type="text" id="google_recaptcha_secret_key" class="regular-text" value="<?php echo $google_recaptcha_secret_key; ?>">
						</td>
					</tr>
				</tbody>	
			</table>
		</div>	
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Notifications</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="email_to_be_notify">Email to be notify</label>
						</th>
						<td>
						    <input name="email_to_be_notify" type="email" id="email_to_be_notify" class="regular-text" value="<?php echo $email_to_be_notify; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="email_address">Email Address</label>
						</th>
						<td>
						    <input name="email_address" type="email" id="email_address" class="regular-text" value="<?php echo $email_address; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="email_name">Email Name</label>
						</th>
						<td>
						    <input name="email_name" type="text" id="email_name" class="regular-text" value="<?php echo $email_name; ?>">
						</td>
					</tr>
				</tbody>	
			</table>
		</div>
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Salesforce API Configuration  ( Optional )</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label for="client_id">Consumer Key</label></th>
						<td>
							<input type="text" name="client_id" id="client_id" class="regular-text" value="<?php echo $client_id; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="client_secret">Consumer Secret</label></th>
						<td>
							<input type="text" id="client_secret" name="client_secret" class="regular-text" value="<?php echo $client_secret; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="redirect_uri">Redirect URI</label></th>
						<td>
							<input type="url" id="redirect_uri" name="redirect_uri" class="regular-text" value="<?php echo $redirect_uri; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="security_token">Security Token</label></th>
						<td>
							<input type="text" id="security_token" name="security_token" class="regular-text" value="<?php echo $security_token; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="login_uri">Login URL</label></th>
						<td>	
							<input type="url" id="login_uri" name="login_uri" class="regular-text" value="<?php echo $login_uri; ?>">							
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="salesforce_url">Salesforce URL</label></th>
						<td>
							<input type="url" id="salesforce_url" name="salesforce_url" class="regular-text" value="<?php echo $salesforce_url; ?>">		
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="salesforce_url">Username</label></th>
						<td>
							<input type="text" id="salesforce_username" name="salesforce_username" class="regular-text" value="<?php echo $salesforce_username; ?>">		
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="salesforce_url">Password</label></th>
						<td>
							<input type="password" id="salesforce_password" name="salesforce_password" class="regular-text" value="<?php echo $salesforce_password; ?>">		
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Pronto Donation Thank You</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<p><span class="description">Note: Use this shorcode</span> [pronto-donation-TYPM] <span class="description">for front end use.</p></p>
						    <?php
								$content = $thank_you_page_message;
								$editor_id = 'thank_you_page_message';

								wp_editor( $content, $editor_id );
							?>
						</th>
					</tr>
				</tbody>
			</table>
		</div>

		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Pronto Donation Cancelled Page</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<p><span class="description">Note: Use this shorcode</span> [pronto-donation-CPM] <span class="description">for front end use.</p></p>
						    <?php
								$content = $cancel_page_message;
								$editor_id = 'cancel_page_message';

								wp_editor( $content, $editor_id );
							?>
						</th>
					</tr>
				</tbody>
			</table>
		</div>


		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Thank you Email Message</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<p><span class="description">Note: Insert this </span> [first-name] 
							<span class="description"> for firstname and </span> [last-name]  
							<span class="description">for lastname to automatically provide name of user base on email address.</span></p>
						    <?php
								$content = $thank_you_email_message;
								$editor_id = 'thank_you_email_message';

								wp_editor( $content, $editor_id );
							?>
							Test Email :
							<input type="email" name="email_for_test" id="email_for_test" class="regular-text" style="background-color: rgb(230, 230, 230);"/>
							<input type="submit" name="send_test_email" id="send_test_email" class="button button-primary" value="Send Test Email">
						</th>
					</tr>
				</tbody>
			</table>
		</div>
		<!--
		<br/>
		<br/>
		<div class="card" style="width: 100%;max-width: 96% !important">
			<h2 class="title">Info on Offline Payment Panel</h2>
			<label for="enable_offline_payment">
				<input name="enable_offline_payment" type="checkbox" id="enable_offline_payment" value="1" <?php //if($info_on_offline_payment_panel_enable_offline_payment==1){echo'checked';}?>>
			Enable Offline Payment
		<!--	</label>		
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<p><span class="description">Note: Use this shorcode</span> [pronto-donation-IOOPPP] <span class="description">for front end use.</p></p>
						    <?php
								// $content = $info_on_offline_payment_panel;
								// $editor_id = 'info_on_offline_payment_panel';

								// wp_editor( $content, $editor_id );
							?>
		<!--				</th>
					</tr>
				</tbody>
			</table>

			<h2 class="title">Instructions Emailed to Offline Donor Before Payment is Approved</h2>	
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<p><span class="description">Note: Use this shorcode</span> [pronto-donation-IETODBP] <span class="description">for front end use.</p></p>
							<?php
								// $content = $instructions_emailed_to_offline_donor_before_payment;
								// $editor_id = 'instructions_emailed_to_offline_donor_before_payment';

								// wp_editor( $content, $editor_id );
							?>
		<!--				</th>
					</tr>
				</tbody>
			</table>
		</div>
		-->
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">

		</p>
	</form>
</div>
	<?php

	//================= Test Email For thank you email =================//

	if(isset($_POST['send_test_email']))
	{
		if(empty($_POST['email_for_test'])||$_POST['email_for_test']==''){}
		else{
			$email_message = (empty($_POST['thank_you_email_message'])) ? "" : $_POST['thank_you_email_message'];	

			$to = sanitize_text_field((empty($_POST['email_for_test'])) ? "" : $_POST['email_for_test']);



			$SQL_String= "SELECT wp_usermeta.meta_value FROM wp_users,wp_usermeta 
						WHERE wp_users.ID = wp_usermeta.user_id 
						AND (wp_usermeta.meta_key = 'first_name' OR wp_usermeta.meta_key = 'last_name')
						AND wp_users.user_email = '".$to."'";

			$results= $GLOBALS['wpdb']->get_results($SQL_String , OBJECT );

			$firstname = $results[0]->meta_value;	
		 	$lastname = $results[1]->meta_value;	

			$subject = sanitize_text_field('Pronto Donation Test Email');
			$message = sanitize_text_field(str_replace("[last-name]",$lastname,str_replace("[first-name]",$firstname,$email_message)));


			wp_mail($to, $subject, $message);
		}
	}
	//================= Test Email For thank you email =================//




	}
}
//================= Donation Settings =================//







	?>
     






<!-- This file should primarily consist of HTML with a little bit of PHP. -->
