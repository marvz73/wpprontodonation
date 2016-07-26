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

		//================ Get Post Page for Messages ==============//
		$thank_you_page_message_post_id = '';
		$thank_you_page_message_postTitle = 'pronto_donation_thank_you_page_message';
		if (get_page_by_title($thank_you_page_message_postTitle) == NULL) {}
		else {
	    	$page = get_page_by_title($thank_you_page_message_postTitle);
			wp_delete_post( $page->ID, true ) ;
	    }

	    $info_on_offline_payment_panel_post_id = '';
		$info_on_offline_payment_panel_postTitle = 'pronto_donation_info_on_offline_payment_panel';
		if (get_page_by_title($info_on_offline_payment_panel_postTitle) == NULL) {}
		else {
	    	$page = get_page_by_title($info_on_offline_payment_panel_postTitle);
			wp_delete_post( $page->ID, true ) ;
	    }

	   	$instructions_emailed_to_offline_donor_before_payment_post_id = '';
		$instructions_emailed_to_offline_donor_before_payment_postTitle = 'pronto_donation_instructions_emailed_to_offline_donor_before_payment';
		if (get_page_by_title($instructions_emailed_to_offline_donor_before_payment_postTitle) == NULL) {}
		else {
	    	$page = get_page_by_title($instructions_emailed_to_offline_donor_before_payment_postTitle);
			wp_delete_post( $page->ID, true ) ;
	    }
	    //================ Get Post Page for Messages ==============//

	}

}
