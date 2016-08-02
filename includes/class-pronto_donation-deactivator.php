<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 * @author     AlphaSys <danryl@alphasys.com.au>
 */

class Pronto_donation_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	
	public static function deactivate() {
		global $wpdb;
		//================ Get Post Page for Messages ==============//

		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
		$thank_you_page_message_page = (empty($pronto_donation_settings['ThankYouPageMessagePage'])) ? "" : $pronto_donation_settings['ThankYouPageMessagePage'];
			
		$cancel_page_message_page = (empty($pronto_donation_settings['CancelPageMessagePage'])) ? "" : $pronto_donation_settings['CancelPageMessagePage'];
	

		if($thank_you_page_message_page==""||empty($thank_you_page_message_page)){}
		else{
			wp_delete_post( $thank_you_page_message_page, true ) ;
		}

		if($cancel_page_message_page==""||empty($cancel_page_message_page)){}
		else{
			wp_delete_post( $cancel_page_message_page, true ) ;
		}
	    //================ Get Post Page for Messages ==============//

	}

}
