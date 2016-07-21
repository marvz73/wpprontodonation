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

	private $base = __DIR__ . '/../payments/';
	private $payments = array();
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
	        array( $this, 'pronto_donation_payment_page' ),    // The name of the function to call when rendering the menu for this page
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

	//
	// Payments Settings
	// Author: Marvin B. Aya-ay
	public function pronto_donation_payment_page(){
		global $title;

		$payment_dirs = scandir($this->base);

		foreach($payment_dirs as $dir)
		{
			if(!is_file($dir) && !is_dir($dir)){
				require_once($this->base . $dir . '/index.php');
				$payment_method = new $dir;
				if (class_exists((string)$dir))
				{	
					$payment_method->className = $dir;
					$payment_method->form_builder = new form_builder();
					array_push($this->payments, $payment_method);
				}
			}
		}

		$post_data = $_POST;
		$payment_type = $_GET['payment'];

		if($_GET['action']!=1)
		{
			require_once('partials/pronto_donation-payment-display.php');
		}
		else if($post_data['action'] == 'save_settings' &&  wp_verify_nonce( $post_data['nonce'], 'payment_'.$post_data['payment_type']))
		{

			//payment option exist, update
			if ( in_array( 
			   	'payment_option_'.$post_data['payment_type']
			      ,array_keys( wp_load_alloptions() )
			  ) ) 
			{
				update_option( 'payment_option_'.$post_data['payment_type'], $post_data);
			}
			else //Create payment option
			{
				update_option( 'payment_option_'.$post_data['payment_type'], $post_data);
			}
			
			$payment_settings = $this->get_payment_settings($payment_type);

			$form_builder = new form_builder();

			$forms = $form_builder->generate_fields($this->set_payment_settings($payment_settings->get_form_fields(), $post_data));

			require_once('partials/pronto_donation-payment-settings.php');

		}
		else
		{
			$payment_settings = array();

			$form_builder = new form_builder();

			$payment_settings = $this->get_payment_settings($payment_type);

			$pm_settings = get_option( 'payment_option_'.$payment_type);

			$forms = $form_builder->generate_fields($this->set_payment_settings($payment_settings->get_form_fields(), $pm_settings));

			require_once('partials/pronto_donation-payment-settings.php');
		}

	} 

	function set_payment_settings($forms, $form_data){

		if(!empty($forms))
		{
			foreach($forms as $form_key=>$form_field)
			{	
				if(!empty($form_data))
				{
					foreach($form_data as $pm_key=>$pm_value)
					{
						if($pm_key == $form_field['name'])
						{
							$forms[$form_key]['value'] = $pm_value;
						}
					}
				}
			}

			return $forms;
		}
		else
		{
			return array();
		}

	}

	//get data of the current payment
	function get_payment_settings($payment_type){

			foreach($this->payments as $key=>$payment)
			{
				if($payment->className == $payment_type)
				{
					return $payment;
				}
			}

	}
	// EOF Pronto Payments

	
	public function pronto_donation_menu_page() {
		global $title;

		require_once('partials/pronto_donation-admin-display.php');
	}

	public function pronto_donation_sub_ezidebit() {

		$ezidebit_menu = add_submenu_page( 
 				'donation_page', 
 				'Ezidebit', 
 				'Ezidebit', 
 				'administrator', 
 				'ezidebit',  
 				'tedx_inatural_sync_contact_callback' 
 		);
	}

	public function pronto_donation_ezidebit_page() {
		global $title;
		require_once('partials/pronto_donation-admin-display.php');
	}

}
