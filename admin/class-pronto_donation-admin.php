<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/admin
 * @author     AlphaSys <danryl@alphasys.com.au>
 */
class Pronto_donation_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pronto_donation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pronto_donation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pronto_donation-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pronto_donation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pronto_donation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pronto_donation-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function pronto_donation_parent_menu() {

		$donation_menu = add_menu_page(
	        'Pronto Donation',                              // The title to be displayed on the corresponding page for this menu
	        'Pronto Donation',                              // The text to be displayed for this actual menu item
	        'administrator',                                // Which type of users can see this menu
	        'donation_page',                                // The unique ID - that is, the slug - for this menu item
	        array( $this, 'pronto_donation_menu_page' ),    // The name of the function to call when rendering the menu for this page
	       	'dashicons-money',						        // The icon for this menu.
	        '83.7'                                          // The position in the menu order this menu should appear
	    );

		//Campaign
		$donation_menu = add_submenu_page(
			'donation_page',
	        'Pronto Donation',                              // The title to be displayed on the corresponding page for this menu
	        'Pronto Campaign',                              // The text to be displayed for this actual menu item
	        'administrator',                                // Which type of users can see this menu
	        'donation-campaign',                                // The unique ID - that is, the slug - for this menu item
	        array( $this, 'pronto_donation_menu_page' ),    // The name of the function to call when rendering the menu for this page
	       	'dashicons-money',						        // The icon for this menu.
	        '83.7'                                          // The position in the menu order this menu should appear
	    );

		//Payment
		$donation_menu = add_submenu_page(
			'donation_page',
	        'Pronto Donation',                              // The title to be displayed on the corresponding page for this menu
	        'Pronto Payment',                              // The text to be displayed for this actual menu item
	        'administrator',                                // Which type of users can see this menu
	        'donation-payment',                                // The unique ID - that is, the slug - for this menu item
	        array( $this, 'pronto_donation_menu_page' ),    // The name of the function to call when rendering the menu for this page
	       	'dashicons-money',						        // The icon for this menu.
	        '83.7'                                          // The position in the menu order this menu should appear
	    );

		//General Settings
		$donation_menu = add_submenu_page(
			'donation_page',
	        'Pronto Donation',                              // The title to be displayed on the corresponding page for this menu
	        'Pronto Settings',                              // The text to be displayed for this actual menu item
	        'administrator',                                // Which type of users can see this menu
	        'donation-settings',                                // The unique ID - that is, the slug - for this menu item
	        array( $this, 'pronto_donation_menu_page' ),    // The name of the function to call when rendering the menu for this page
	       	'dashicons-money',						        // The icon for this menu.
	        '83.7'                                          // The position in the menu order this menu should appear
	    );


	}
	
	public function pronto_donation_menu_page() {
		global $title;
		require_once('partials/pronto_donation-admin-display.php');
	}
}
