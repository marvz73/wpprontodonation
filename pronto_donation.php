<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://alphasys.com.au/
 * @since             1.0.0
 * @package           Pronto_donation
 *
 * @wordpress-plugin
 * Plugin Name:       pronto_donation
 * Plugin URI:         http://alphasys.com.au/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            AlphaSys
 * Author URI:        http://alphasys.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pronto_donation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pronto_donation-activator.php
 */
function activate_pronto_donation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pronto_donation-activator.php';
	Pronto_donation_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pronto_donation-deactivator.php
 */
function deactivate_pronto_donation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pronto_donation-deactivator.php';
	Pronto_donation_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pronto_donation' );
register_deactivation_hook( __FILE__, 'deactivate_pronto_donation' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pronto_donation.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pronto_donation() {


	$plugin = new Pronto_donation();
	$plugin->run();

	/**
	* This required file will load the php soap api toolkit for salesforce
	*/
	
}



run_pronto_donation();
