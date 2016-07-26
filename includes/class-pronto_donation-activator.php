<?php

/**
 * Fired during plugin activation
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 * @author     AlphaSys <danryl@alphasys.com.au>
 */
class Pronto_donation_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		//============================== Create Post Page for Messages ============================//

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
		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');


		$thank_you_page_message_post_id = '';
		$thank_you_page_message_postTitle = 'pronto donation thank you page message';

	    if (get_page_by_title($thank_you_page_message_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $thank_you_page_message_postTitle,
	            'post_content' => (empty($pronto_donation_settings['ThankYouPageMessage'])) ? "" : $pronto_donation_settings['ThankYouPageMessage'],
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'post',
	            'post_category' => array(0)
	        );
			$thank_you_page_message_post_id = wp_insert_post($new_post);
	    	
	    } 



	    $info_on_offline_payment_panel_post_id = '';
		$info_on_offline_payment_panel_postTitle = 'pronto donation info on offline payment panel';

	    if (get_page_by_title($info_on_offline_payment_panel_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $info_on_offline_payment_panel_postTitle,
	            'post_content' => (empty($pronto_donation_settings['InfoOnOfflinePaymentPanel'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanel'],
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'post',
	            'post_category' => array(0)
	        );
			$info_on_offline_payment_panel_post_id = wp_insert_post($new_post);
	    	
	    } 



	   	$instructions_emailed_to_offline_donor_before_payment_post_id = '';
		$instructions_emailed_to_offline_donor_before_payment_postTitle = 'pronto donation instructions emailed to offline donor before payment';

	    if (get_page_by_title($instructions_emailed_to_offline_donor_before_payment_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $instructions_emailed_to_offline_donor_before_payment_postTitle,
	            'post_content' => (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'],
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'post',
	            'post_category' => array(0)
	        );
			$instructions_emailed_to_offline_donor_before_payment_post_id = wp_insert_post($new_post);
	    	
	    } 


	    //================ Get All Data In Pronto Donation Settings Option ==============//
	    

		$form_class = (empty($pronto_donation_settings['FormClass'])) ? "" : $pronto_donation_settings['FormClass'];
		$button_class = (empty($pronto_donation_settings['ButtonClass'])) ? "" : $pronto_donation_settings['ButtonClass'];
		$input_field_class = (empty($pronto_donation_settings['InputFieldClass'])) ? "" : $pronto_donation_settings['InputFieldClass'];
		$edit_button_caption = (empty($pronto_donation_settings['EditButtonCaption'])) ? "" : $pronto_donation_settings['EditButtonCaption'];

		$set_currency = (empty($pronto_donation_settings['SetCurrencyCode'])) ? "" : $pronto_donation_settings['SetCurrencyCode']; 
		$set_country = (empty($pronto_donation_settings['SetCountry'])) ? "" : $pronto_donation_settings['SetCountry']; 	

		$email_to_be_notify = (empty($pronto_donation_settings['EmailToBeNotify'])) ? "" : $pronto_donation_settings['EmailToBeNotify']; 
		$email_address = (empty($pronto_donation_settings['EmailAddress'])) ? "" : $pronto_donation_settings['EmailAddress']; 
		$email_name = (empty($pronto_donation_settings['EmailName'])) ? "" : $pronto_donation_settings['EmailName']; 	

		$client_id = (empty($pronto_donation_settings['ClientId'])) ? "" : $pronto_donation_settings['ClientId'];
		$client_secret = (empty($pronto_donation_settings['ClientSecret'])) ? "" : $pronto_donation_settings['ClientSecret'];	
		$redirect_uri = (empty($pronto_donation_settings['RedirectURI'])) ? "" : $pronto_donation_settings['RedirectURI'];	
		$login_uri = (empty($pronto_donation_settings['LoginURI'])) ? "" : $pronto_donation_settings['LoginURI'];	
		$salesforce_url = (empty($pronto_donation_settings['SalesforceURL'])) ? "" : $pronto_donation_settings['SalesforceURL'];
		$salesforce_username = (empty($pronto_donation_settings['SalesforceUsername'])) ? "" : $pronto_donation_settings['SalesforceUsername'];
		$salesforce_password = (empty($pronto_donation_settings['SalesforcePassword'])) ? "" : $pronto_donation_settings['SalesforcePassword'];

		$thank_you_page_message_page = (empty($thank_you_page_message_post_id)) ? "" : $thank_you_page_message_post_id;
		$thank_you_page_message = (empty($pronto_donation_settings['ThankYouPageMessage'])) ? "" : $pronto_donation_settings['ThankYouPageMessage'];	

		$thank_you_email_message = (empty($pronto_donation_settings['ThankYouMailMessage'])) ? "" : $pronto_donation_settings['ThankYouMailMessage'];

		$info_on_offline_payment_panel_page = (empty($info_on_offline_payment_panel_post_id)) ? "" : $info_on_offline_payment_panel_post_id;
		$info_on_offline_payment_panel_enable_offline_payment = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanelEnableOfflinePayment'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanelEnableOfflinePayment'];
		$info_on_offline_payment_panel = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanel'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanel'];

		$instructions_emailed_to_offline_donor_before_payment_page = (empty($instructions_emailed_to_offline_donor_before_payment_post_id)) ? "" : $instructions_emailed_to_offline_donor_before_payment_post_id;	
		$instructions_emailed_to_offline_donor_before_payment = (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePayment'];
		//================ Get All Data In Pronto Donation Settings Option ==============//


		//================ Update Pronto Donation Settings Option ==============//
    	$pronto_donation_settings = array(
			'FormClass'     => stripslashes($form_class),
			'ButtonClass'      => stripslashes($button_class),
			'InputFieldClass'   => stripslashes($input_field_class),
			'EditButtonCaption'   => stripslashes($edit_button_caption),
			'SetCurrencySymbol'   => $currency_symbols[$set_currency],
			'SetCurrencyCode'   => stripslashes($set_currency),
			'SetCountry' => stripslashes($set_country),

			'EmailToBeNotify' => stripslashes(str_replace("/","",$email_to_be_notify)),
			'EmailAddress' => stripslashes(str_replace("/","",$email_address)),
			'EmailName' => stripslashes(str_replace("/","",$email_name)),

			'ClientId' => stripslashes($client_id),
			'ClientSecret' => stripslashes($client_secret),
			'RedirectURI' => stripslashes($redirect_uri),
			'LoginURI' => stripslashes($login_uri),
			'SalesforceURL' => stripslashes($salesforce_url),
			'SalesforceUsername' => stripslashes($salesforce_username),
			'SalesforcePassword' => stripslashes($salesforce_password),

			'ThankYouPageMessagePage' => stripslashes($thank_you_page_message_page),
			'ThankYouPageMessage' => stripslashes($thank_you_page_message),

			'ThankYouMailMessage' => stripslashes($thank_you_email_message),

			'InfoOnOfflinePaymentPanelPage' => stripslashes($info_on_offline_payment_panel_page),
			'InfoOnOfflinePaymentPanelEnableOfflinePayment' => stripslashes($info_on_offline_payment_panel_enable_offline_payment),
			'InfoOnOfflinePaymentPanel' => stripslashes($info_on_offline_payment_panel),

			'InstructionsEmailedToOfflineDonorBeforePaymentPage' => stripslashes($instructions_emailed_to_offline_donor_before_payment_page),
			'InstructionsEmailedToOfflineDonorBeforePayment' => stripslashes($instructions_emailed_to_offline_donor_before_payment)

		); 
		update_option('pronto_donation_settings' , $pronto_donation_settings);
		//================ Update Pronto Donation Settings Option ==============//

	    //============================== Create Post Page for Messages ============================//
	}

}
