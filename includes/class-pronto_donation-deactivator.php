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


	   	$cancel_page_message_postTitle = 'pronto donation cancel page message';
		$post_id_B = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $cancel_page_message_postTitle . "'" );
	    if(empty($post_id_B)||$post_id_B==null){}
	    else{
	    	wp_delete_post( $post_id_B, true ) ;
	    }
	    //================ Get Post Page for Messages ==============//

	}

}
