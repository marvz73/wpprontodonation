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

		//================ Create Post Page for Messages ==============//
		$thank_you_page_message_post_id = '';
		$thank_you_page_message_postTitle = 'pronto_donation_thank_you_page_message';

	    if (get_page_by_title($thank_you_page_message_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $thank_you_page_message_postTitle,
	            'post_content' => '',
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'page',
	            'post_category' => array(0)
	        );
			$thank_you_page_message_post_id = wp_insert_post($new_post);
	    	
	    } 



	    $info_on_offline_payment_panel_post_id = '';
		$info_on_offline_payment_panel_postTitle = 'pronto_donation_info_on_offline_payment_panel';

	    if (get_page_by_title($info_on_offline_payment_panel_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $info_on_offline_payment_panel_postTitle,
	            'post_content' => '',
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'page',
	            'post_category' => array(0)
	        );
			$info_on_offline_payment_panel_post_id = wp_insert_post($new_post);
	    	
	    } 



	   	$instructions_emailed_to_offline_donor_before_payment_post_id = '';
		$instructions_emailed_to_offline_donor_before_payment_postTitle = 'pronto_donation_instructions_emailed_to_offline_donor_before_payment';

	    if (get_page_by_title($instructions_emailed_to_offline_donor_before_payment_postTitle) == NULL) {
	    	$new_post = array(
	            'post_title' => $instructions_emailed_to_offline_donor_before_payment_postTitle,
	            'post_content' => '',
	            'post_status' => 'publish',
	            'post_date' => date('Y-m-d H:i:s'),
	            'post_author' => '',
	            'post_type' => 'page',
	            'post_category' => array(0)
	        );
			$instructions_emailed_to_offline_donor_before_payment_post_id = wp_insert_post($new_post);
	    	
	    } 

	    //================ Create Post Page for Messages ==============//
	}

}
