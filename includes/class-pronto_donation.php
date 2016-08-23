<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 * @author     AlphaSys <danryl@alphasys.com.au>
 */
class Pronto_donation {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pronto_donation_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	protected $salesforceAPI;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'pronto_donation';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pronto_donation_Loader. Orchestrates the hooks of the plugin.
	 * - Pronto_donation_i18n. Defines internationalization functionality.
	 * - Pronto_donation_Admin. Defines all hooks for the admin area.
	 * - Pronto_donation_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pronto_donation-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pronto_donation-i18n.php';

	 	/**
		 *  This will load the donation list custom wp list table
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/pronto_donation-campaign-page.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pronto_donation-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pronto_donation-public.php';

		//Class for payment field renderer
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-builder.php';

		//php unsafe crypto
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/UnsafeCrypto.php';
		

		$this->loader = new Pronto_donation_Loader();

	}


	private function manual_loadDependencies(){

		//Salesforce php toolkit
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/salesforce-toolkit/class-salesforce.php';
		
		//Init salesforce SOAP
		$sForce = new salesforceSOAP();
		$this->salesforceAPI = $sForce->salesforce;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pronto_donation_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pronto_donation_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pronto_donation_Admin( $this->get_plugin_name(), $this->get_version(), $this );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pronto_donation_parent_menu');

		$this->loader->add_action( 'init', $plugin_admin, 'pronto_donation_campaign_posttype' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'pronto_donation_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'pronto_donation_campagin_save_post' );
		$this->loader->add_filter( 'manage_edit-campaign_columns', $plugin_admin, 'pronto_donation_post_column' );
		$this->loader->add_action( 'manage_campaign_posts_custom_column', $plugin_admin, 'pronto_donation_column_data', 10, 2 );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'pronto_donation_campaign_head_css' );

		$this->loader->add_filter( 'posts_join', $plugin_admin, 'pronto_donation_cf_search_join' );
		$this->loader->add_filter( 'posts_where', $plugin_admin, 'pronto_donation_cf_search_where' );
		$this->loader->add_filter( 'posts_distinct', $plugin_admin,'pronto_donation_cf_search_distinct' );

		
		$this->loader->add_action( 'admin_print_scripts', $plugin_admin, 'pronto_donation_wp_gear_manager_admin_scripts' );
		$this->loader->add_action( 'admin_print_styles', $plugin_admin, 'pronto_donation_wp_gear_manager_admin_styles' );

		$this->loader->add_filter('parent_file', $plugin_admin, 'pronto_donation_fix_admin_parent_file');

		$this->loader->add_action( 'init', $plugin_admin, 'pronto_donation_register_post_type' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'pronto_donation_remove_menu_items' );
		$this->loader->add_action( 'wp_ajax_change_donation_status', $plugin_admin, 'proto_donation_change_donation_status' );
		$this->loader->add_action( 'wp_ajax_remove_campaign_banner', $plugin_admin, 'proto_donation_remove_campaign_banner' );

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pronto_donation_Public( $this->get_plugin_name(), $this->get_version(), $this );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_shortcode( 'pronto-donation-TYPM', $plugin_public, 'pronto_donation_thank_you_page_message');
		$this->loader->add_shortcode( 'pronto-donation-CPM', $plugin_public, 'pronto_donation_cancel_page_message');
		$this->loader->add_shortcode( 'pronto-donation-IOOPPP', $plugin_public, 'pronto_donation_info_on_offline_payment_panel_page');
		$this->loader->add_shortcode( 'pronto-donation-IETODBP', $plugin_public, 'pronto_donation_instructions_emailed_to_offline_donor_before_payment');
		$this->loader->add_shortcode( 'pronto-donation-IETODBP', $plugin_public, 'pronto_donation_instructions_emailed_to_offline_donor_before_payment');
		$this->loader->add_shortcode( 'pronto-donation', $plugin_public, 'pronto_donation_campaign' );
		$this->loader->add_shortcode( 'pronto-donation-full', $plugin_public, 'pronto_donation_campaign_full' );
		$this->loader->add_shortcode( 'pronto-donation-campaign-list', $plugin_public, 'pronto_donation_campaign_list' );

		$this->loader->add_filter( 'single_template', $plugin_public, 'pronto_donation_override_template', 99 );

		$this->loader->add_action( 'wp_ajax_ezi_self_payment_proccess', $plugin_public, 'pronto_donation_campaign_full' );
		$this->loader->add_action( 'wp_ajax_nopriv_ezi_self_payment_proccess', $plugin_public, 'pronto_donation_campaign_full' );

		$this->loader->add_action( 'wp_ajax_verify_captcha', $plugin_public, 'pronto_donation_ajax_captcha_validate' );
		$this->loader->add_action( 'wp_ajax_nopriv_verify_captcha', $plugin_public, 'pronto_donation_ajax_captcha_validate' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pronto_donation_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	//----------------------------------------------------------------

	public function set_salesforceDonation($campaign){

		$this->manual_loadDependencies();

    	$wpOptions = get_option('pronto_donation_settings', 0);

		$data_transient = get_transient( 'donor_c_details' );
		$data_transient = utf8_decode( $data_transient );
		$data_card_details = maybe_unserialize( $this->pronto_donation_unsafe_decryp( $data_transient ) );
		delete_transient( 'donor_c_details' );


	    if(isset($wpOptions['SalesforceUsername']) && $wpOptions['SalesforceUsername'] != '' &&
	   	  isset($wpOptions['SalesforcePassword']) && $wpOptions['SalesforcePassword'] != '' &&
	   	  isset($wpOptions['SecurityToken']) && $wpOptions['SecurityToken'] != '')
	   		{

				$data = array();	
				if($campaign['donation_type'] == 'recurring'){
					//---------------MONTHLY---------------
					$data = array(
						'strDonation' => array(
								"emailReceipt"		=> 	 true,
								"FirstName" 		=>	 $campaign['first_name'],
								"LastName"  		=>	 $campaign['last_name'],
								"Email"  			=>	 $campaign['email'],
								"Amount"     		=>	 !empty($campaign['pd_custom_amount']) ? $campaign['pd_custom_amount'] : $campaign['pd_amount'],
								"GatewayId" 		=>	 isset($campaign['payment_info']->option['sf_gateway_id']) ? $campaign['payment_info']->option['sf_gateway_id'] : '',
								"donationType" 		=>	 "monthly",
								"PaymentSource" 	=>	 array(
								            "ccname" 	=>	 ( isset($data_card_details['nameOnCard']) ) ? $data_card_details['nameOnCard'] : '',
								            "ccno" 		=>	 ( isset($data_card_details['cardNumber']) ) ? $data_card_details['cardNumber'] : '',
								            "expmonth" 	=>	 ( isset($data_card_details['expiryMonth']) ) ? $data_card_details['expiryMonth'] : '',
								            "expyear" 	=>	 ( isset($data_card_details['expiryYear']) ) ? $data_card_details['expiryYear'] : '',
								            "ccv" 		=>	 ( isset($data_card_details['ccv']) ) ? $data_card_details['ccv'] : '',
								            "type" 		=>	 "cc"
									)
							)
						);
				}else{

					$data = array(
						'strDonation' => array(
								"emailReceipt"		=> 	 true,
								"FirstName" 		=>	 $campaign['first_name'],
								"LastName"  		=>	 $campaign['last_name'],
								"Email"  			=>	 $campaign['email'],
								"Amount"     		=>	 !empty($campaign['pd_custom_amount']) ? $campaign['pd_custom_amount'] : $campaign['pd_amount'],
								"donationType" 		=>	 "one"
							)
					);
				}

				//Dont include this field if value is empty
				if(isset($campaign['donation_gau']) && $campaign['donation_gau'] != ''){
					$data['strDonation']['GAUAlloc'] = $campaign['donation_gau'];
				}

				if(isset($data['strDonation'])){
					$opportunity = $this->salesforceAPI->restAPI('ASSFAPI/donation', $data, 'create');
					return $opportunity;
				}else{
					return array('error'=>1,'message'=>'strDonation is empty.');
				}
			}
	}

	public function get_salesforceGAU(){
		
		$this->manual_loadDependencies();
		return $this->salesforceAPI->restAPI('ASSFAPI/gau');

	}

	/*
	* Author : Danryl Carpio
	* @ 1st param : (array) $array_records, this will be the array of records 
	* ex : array( array( firstname => 'danryl', lastname=> 'carpio' ) ) 
	* @ 2nd param : (string) $sf_object, this will be the salesforce object
	* ex : "Contact"
	* @return : array( id, status ) if success 1
	*/
	public function sf_create_record( $array_records, $sf_object ) {
		$this->manual_loadDependencies();
		return $this->salesforceAPI->create( $array_records, $sf_object );
	}

	/*
	* Author: Danryl Carpio
	* @ param: (string) salesforce query
	* @return: array query result
	*/
	public function sf_get_record( $query ) {
		$this->manual_loadDependencies();
		return $this->salesforceAPI->query( $query );
	}

	/*
	* Author: Danryl Carpio
	* @ param: data to be encrypted
	* @return: array encrypted data
	*/
	public function pronto_donation_unsafe_encryp($data) {
		$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
		return UnsafeCrypto::encrypt($data, $key);
	}

	/*
	* Author: Danryl Carpio
	* @ param: encrypted data
	* @return: decrypted string data
	*/
	public function pronto_donation_unsafe_decryp($encrypted) {
		$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
		return UnsafeCrypto::decrypt($encrypted, $key);
	}

	public function pronto_donation_payment_methods(){

		$base = __DIR__ . '/../payments/';
		$payments = array();
		$payment_dirs = scandir($base);
		foreach($payment_dirs as $dir)
		{
			if(!is_file($dir) && !is_dir($dir)){
				require_once($base . $dir . '/index.php');
				$payment_method = new $dir;
				if (class_exists((string)$dir))
				{

					$payment_method->option = get_option('payment_option_' . (string)$dir);
					if(isset($payment_method->option['enable']))
					{
						array_push($payments, $payment_method);
					}
				}
			}
		}

		return $payments;

	}

	public function pronto_donation_payment_amount_level($campaign_id){
		$html ='';
		if($campaign_id)
		{
			$pronto_donation_campaign = get_post_meta($campaign_id, 'pronto_donation_campaign', true);
			if(!empty($pronto_donation_campaign['amount_level']))
			{
				
				foreach(explode(',', $pronto_donation_campaign['amount_level']) as $index=>$amount_level):
					if($index==0)
					{
						$html .= '<input id="pd_amount'.$index.'" class="pd-level-amount" type="radio" name="pd_amount" value="'.$amount_level.'" checked="true" /><label class="pd-amount" for="pd_amount'.$index.'">' . $this->pronto_donation_currency() . $amount_level. '</label>';
					}
					else
					{
						$html .= '<input id="pd_amount'.$index.'" class="pd-level-amount" type="radio" name="pd_amount" value="'.$amount_level.'" /><label class="pd-amount" for="pd_amount'.$index.'">' . $this->pronto_donation_currency() . $amount_level. '</label>';
					}

				endforeach;
			}
			echo $html;
		}
	}

	public function pronto_donation_has_payment_amount_level($campaign_id){

		if($campaign_id)
		{
			$pronto_donation_campaign = get_post_meta($campaign_id, 'pronto_donation_campaign', true);
			if(!empty($pronto_donation_campaign['amount_level']))
			{
				return true;
			}
		}

		return false;
	}

	public function pronto_donation_is_required($field){
		echo ($field == 'required') ? 'required' : '';
	}

	public function pronto_donation_currency(){
		return get_option('pronto_donation_settings')['SetCurrencySymbol'];
	}

	/*
	* This function will return the total donation amount
	* and the total donator
	* @params $campaign_id (the campaign post id)
	* return value array(total_donation_amount, total_donator)
	*/
	public function pronto_donation_get_donation_details( $campaign_id ) {

		global $wpdb;

		$result_arr = array();

		if( !empty( $campaign_id ) ) {
			$result_donation = $wpdb->get_results("Select * FROM $wpdb->postmeta where meta_key='pronto_donation_donor' AND post_id=" . $campaign_id);

			$total_donation_amount = 0;
			$total_donator = 0;

			foreach ($result_donation as $key => $donor_value) {

				$donation_details = unserialize( $donor_value->meta_value );
			
				if( isset($donation_details['statusCode']) && $donation_details['statusCode'] === 1 ) {
					$total_donator++;
					if(array_key_exists('pd_amount', $donation_details)
						&& isset( $donation_details['pd_amount'] )
						&& (int) $donation_details['pd_amount'] > 0 )
					{
						$total_donation_amount += (int) $donation_details['pd_amount'];
					} else if( array_key_exists('pd_custom_amount', $donation_details) 
						&& isset( $donation_details['pd_custom_amount'] ) 
						&& (int) $donation_details['pd_custom_amount'] > 0 )
					{
						$total_donation_amount += (int) $donation_details['pd_custom_amount'];
					}
				}
			}

			$result_arr['total_donation_amount'] = $total_donation_amount;
			$result_arr['total_donator'] = $total_donator;
		}

		return $result_arr;
	}

	function pronto_donation_user_notification($campaign) {

		$site_name = get_bloginfo('name');
		$option = get_option('pronto_donation_settings');

		if(isset($option['ThankYouMailMessageEnable']) && $option['ThankYouMailMessageEnable'] != '')
		{
		    //Build admin notification email
		    $message  = sprintf(__('New donation on your site %s:'), $site_name) . "\r\n\r\n";
		    $email_value =  (isset($campaign['email'])) ? $campaign['email'] : '';
		    $message .= sprintf(__('Email: %s'), $email_value) . "\r\n";

		    $first_name_value =  (isset($campaign['first_name'])) ? $campaign['first_name'] : '';
		    $message .= sprintf(__('First Name: %s'), $first_name_value) . "\r\n";

		    $last_name_value =  (isset($campaign['last_name'])) ? $campaign['last_name'] : '';
		    $message .= sprintf(__('Last Name: %s'), $last_name_value) . "\r\n";

		    $address_value =  (isset($campaign['address'])) ? $campaign['address'] : '';
		    $message .= sprintf(__('Address: %s'), $address_value) . "\r\n";

		    $country_value =  (isset($campaign['country'])) ? $campaign['country'] : '';
		    $message .= sprintf(__('Country: %s'), $country_value) . "\r\n";

		    $state_value =  (isset($campaign['state'])) ? $campaign['state'] : '';
		    $message .= sprintf(__('State: %s'), $state_value) . "\r\n";

		    $post_code_value =  (isset($campaign['post_code'])) ? $campaign['post_code'] : '';
		    $message .= sprintf(__('Post Code: %s'), $post_code_value) . "\r\n";

		    $suburb_value =  (isset($campaign['suburb'])) ? $campaign['suburb'] : '';
		    $message .= sprintf(__('Suburb: %s'), $suburb_value) . "\r\n\r\n";
		    
		    $message .= sprintf(__('Payment Method: %s'), $campaign['payment']) . "\r\n";
		    $message .= sprintf(__('Currency: %s'), $campaign['CurrencyCode']) . "\r\n";
		    $message .= sprintf(__('Payment Response: %s'), $campaign['statusText']) . "\r\n";
		    $message .= sprintf(__('Donation Amount: %s'), $campaign['pd_amount']) . "\r\n";

		    //Send admin notification email
		    @wp_mail($option['EmailToBeNotify'], sprintf(__('[%s] New Donation'), $site_name), $message);

		    //BUILD USER NOTIFICATION EMAIL
		    $message_template = $option['ThankYouMailMessage'];

		    $email_value =  (isset($campaign['email'])) ? $campaign['email'] : '';
		    $message = str_ireplace('[email]', $email_value, $message_template);

		    $first_name_value =  (isset($campaign['first_name'])) ? $campaign['first_name'] : '';
		    $message = str_ireplace('[first-name]', $first_name_value, $message);

		    $last_name_value =  (isset($campaign['last_name'])) ? $campaign['last_name'] : '';
		    $message = str_ireplace('[last-name]', $last_name_value, $message);

		    $address_value =  (isset($campaign['address'])) ? $campaign['address'] : '';
		    $message = str_ireplace('[address]', $address_value, $message);

		    $country_value =  (isset($campaign['country'])) ? $campaign['country'] : '';
		    $message = str_ireplace('[country]', $country_value, $message);

		    $state_value =  (isset($campaign['state'])) ? $campaign['state'] : '';
		    $message = str_ireplace('[state]', $state_value, $message);

		    $post_code_value =  (isset($campaign['post_code'])) ? $campaign['post_code'] : '';
		    $message = str_ireplace('[post-code]', $post_code_value, $message);

		    $suburb_value =  (isset($campaign['suburb'])) ? $campaign['suburb'] : '';
		    $message = str_ireplace('[city]', $suburb_value, $message);
		    $message = str_ireplace('[suburb]', $suburb_value, $message);

		    $SetCurrencySymbol_value =  (isset($option['SetCurrencySymbol'])) ? $option['SetCurrencySymbol'] : '';
		    $pd_amount_value =  (isset($campaign['pd_amount'])) ? $campaign['pd_amount'] : '';
		    $message = str_ireplace('[amount]', $SetCurrencySymbol_value . $pd_amount_value, $message);

		    $donation_campaign_value =  (isset($campaign['donation_campaign'])) ? $campaign['donation_campaign'] : '';
		    $message = str_ireplace('[campaign-name]', get_the_title($donation_campaign_value), $message);

		    $donation_gift_message_value =  (isset($campaign['donation_gift_message'])) ? $campaign['donation_gift_message'] : '';
		    $message = str_ireplace('[gift-message]', $donation_gift_message_value, $message);

		    $SetCurrencyCode_value =  (isset($option['SetCurrencyCode'])) ? $option['SetCurrencyCode'] : '';
		    $message = str_ireplace('[currency]', $SetCurrencyCode_value, $message);

		    //Prepare headers for HTML
		    $email_headers[]  = 'MIME-Version: 1.0' . "\r\n";
		    $email_headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		    $email_headers[] = 'From: '.$option['EmailName'].' <'.$option['EmailAddress'].'>';

		    //Send user notification email
		    @wp_mail($campaign['email'], sprintf(__('Thank for %s Donation'), get_the_title($campaign['donation_campaign'])), nl2br($message), $email_headers);
		}
		
	}
}
