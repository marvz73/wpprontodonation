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
		$wpdb->get_results("DELETE FROM $wpdb->posts WHERE post_type = 'pronto_donation'");
	    //================ Get Post Page for Messages ==============//

	}

}
