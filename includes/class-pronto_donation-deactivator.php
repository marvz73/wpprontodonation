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
		$thank_you_page_message_postTitle = 'pronto donation thank you page message';
		$post_id_A = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $thank_you_page_message_postTitle . "'" );
	    if(empty($post_id_A)||$post_id_A==null){}
	    else{
	    	wp_delete_post( $post_id_A, true ) ;
	    }

		$info_on_offline_payment_panel_postTitle = 'pronto donation info on offline payment panel';
		$post_id_B = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $info_on_offline_payment_panel_postTitle . "'" );
		if (empty($post_id_B)||$post_id_B==null) {}
		else {
			wp_delete_post( $post_id_B, true ) ;
	    }

		$instructions_emailed_to_offline_donor_before_payment_postTitle = 'pronto donation instructions emailed to offline donor before payment';
		$post_id_C = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $instructions_emailed_to_offline_donor_before_payment_postTitle . "'" );

		if (empty($post_id_C)||$post_id_C==null) {}
		else {
			wp_delete_post( $post_id_C, true ) ;
	    }
	    //================ Get Post Page for Messages ==============//

	}

}
