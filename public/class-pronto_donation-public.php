<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/public
 * @author     AlphaSys <danryl@alphasys.com.au>
 */
class Pronto_donation_Public {

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



	public $campaignOption;
	public $errors;
	private $class;





	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $class ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->class = $class;
		$this->errors = new stdClass();
		$this->campaignOption = $this->_array_to_object(get_option('pronto_donation_settings'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pronto_donation-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		//Google g-recaptcha
		wp_enqueue_script( 'grecaptcha', 'https://www.google.com/recaptcha/api.js', array( ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pronto_donation-public.js', array( 'jquery' ), $this->version, false );
			

		//========================= Google Maps Autocomplete =======================//
		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');

		$google_geocode_api_key = (empty($pronto_donation_settings['GoogleGeocodeAPIKey'])) ? "" : $pronto_donation_settings['GoogleGeocodeAPIKey'];
		if(empty($google_geocode_api_key)||$google_geocode_api_key==''){}else{
			wp_enqueue_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?key='.$google_geocode_api_key.'&language=en&libraries=places&callback=initAutocomplete', array(), $this->version, false );
		}
		//========================= Google Maps Autocomplete =======================//
	}

	//
	// Desc: Pronto Campaign
	// Author: Marvin Aya-ay
	public function pronto_donation_campaign( $campaign_id ){
		$this->_pronto_donation_campaign($campaign_id, 'short');
	}

	//
	// Desc: Pronto Campaign Full
	// Author: Marvin Aya-ay
	private $base = __DIR__ . '/../payments/';
	public function pronto_donation_campaign_full( $campaign_id){

		//Process the payment here...
	    if($_POST)
	    {

	    	$campaign_data = $_POST;

			$captcha = isset($campaign_data['g-recaptcha-response']) ? $campaign_data['g-recaptcha-response'] : "";

			if(empty($captcha) && $this->campaignOption->GoogleReCaptchaEnable)
			{
				$googleSecret = $this->campaignOption->GoogleReCaptchaSecretKey;
				$response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$googleSecret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
		        if($response['success'] == false){
		      		$this->errors->captcha = "You're a robot.";
		        }

		        $this->_pronto_donation_campaign($campaign_id, 'full');
			}
	    	else if($campaign_data['action'] == 'process_donate' && wp_verify_nonce( $campaign_data['nonce'], 'donation'))
	    	{
			 	// global $wpdb;
				// $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '123123123123' WHERE meta_id = 28");

	    		$campaign_data['status'] = 'pending';
	    		$campaign_data['CurrencyCode'] = $this->campaignOption->SetCurrencyCode;

	    		$payment_methods = $this->class->pronto_donation_payment_methods();

	    		foreach($payment_methods as $index=>$payment)
	    		{
	    			if(strtolower($campaign_data['payment']) == strtolower($payment->payment['payment_name']))
	    			{
	    				$campaign_data['payment_info'] = $payment;
	    			}
	    		}
	    		
	    		$campaign_data['timestamp'] = time();

	    		$campaign_data['redirectURL'] = get_home_url() . '/?p=' . $this->campaignOption->ThankYouPageMessagePage . '&payment_gateway=' . $campaign_data['payment'];

  				$post_meta_id = add_post_meta($campaign_data['donation_campaign'], 'pronto_donation_donor', $campaign_data);

	    		$campaign_data['CancelUrl']   = get_home_url() . '/?p=' . $this->campaignOption->CancelPageMessagePage. '&payment_status=C&ref=' . $post_meta_id;
  				
  				$campaign_data['post_meta_id'] = $post_meta_id;

	    		// Call the payment function to execute payment action
	    		$campaign_data['payment_info']->payment_process($campaign_data);

	    	}
	    }
	    else
	    {
			$this->_pronto_donation_campaign($campaign_id, 'full');
	    }
	}	

	public function pronto_donation_campaign_list() {
	  	global $wpdb;
	    $results = $wpdb->get_results("Select * FROM $wpdb->posts where post_type='campaign' AND post_status='publish'");
 		$campaign_list = new stdClass();
 		foreach($results as $key=>$campaign)
 		{
 			$campaign->post_meta = get_post_meta($campaign->ID, 'pronto_donation_campaign',TRUE);
 			$campaign_list->$key = $campaign;
 		}
		require_once('partials/pronto_donation-campaign-list.php');
	}

	public function pronto_donation_override_template($page_template){

		global $wpdb;
		global $post;

		if (isset($_GET['payment_gateway']) && get_the_ID() == get_option('pronto_donation_settings')['ThankYouPageMessagePage'])
		{
   
    		$payment_methods = $this->class->pronto_donation_payment_methods();

    		foreach($payment_methods as $index=>$payment)
    		{
    			
    			if(strtolower($_GET['payment_gateway']) == strtolower($payment->payment['payment_name']))
    			{
    				//call payment process complete
    				$payment->payment_complete($_GET);

					// SALESFORCE LOGIC SYNC HERE...
					// SALESFORCE LOGIC SYNC HERE...
					// SALESFORCE LOGIC SYNC HERE...


    			}
    		}
		} //Cancel Transaction
		else if (isset($_GET['ref']) && $_GET['payment_status'] == 'C' && get_the_ID() == get_option('pronto_donation_settings')['CancelPageMessagePage'])
		{
			$campaign_id = preg_replace("/[^A-Za-z0-9 ]/", '', $_GET['ref']);
			$campaignDonor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . $campaign_id);
			$campaign = maybe_unserialize($campaignDonor[0]->meta_value);
			if($campaign['status'] != 'CANCELLED')
			{
				$campaign['status'] = 'CANCELLED';
				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . $campaign_id);
			}
		}
		else if(get_post_type($post->ID) == 'campaign')
		{
		    // $page_template = dirname( __FILE__ ) . '/partials/pronto_donation-public-display.php';
			
			// add_filter('the_title', function($title){
			// 	$title = '';
			// 	return $title;
			// });

		    add_filter('the_content', function($content){
		    	global $post;
		    	$content = "[pronto-donation-full campaign=".$post->ID."]";
		    	return $content;
		    });
		}

		return $page_template;
	}

	function test($content){

		$content = 'test32';

		return $content;
	}

	public function pronto_donation_thank_you_page_message(){
		global $title;
		require_once('partials/pronto_donation-thank-you-page-message.php');
	}

	public function pronto_donation_cancel_page_message(){
		global $title;
		require_once('partials/pronto_donation-cancel-page-message.php');
	}

	public function pronto_donation_info_on_offline_payment_panel_page(){
		global $title;
		require_once('partials/pronto_donation-public-info-on-offline-payment-panel-page.php');
	}

	public function pronto_donation_instructions_emailed_to_offline_donor_before_payment(){
		global $title;
		require_once('partials/pronto_donation-public-instructions-emailed-to-offline-donor-before-payment.php');
	}

	/*
     * 
     * Desc: Helper and redundancy function
	 */

	function _pronto_donation_campaign($campaign, $type){

		if($type=='short')
		{
		    //Display the donation fields
			$attrs = shortcode_atts( array(
		        'campaign' 		=> 0,
		        'hidetitle' 	=> false,
		        'hidebanner' 	=> false,
		        'hidedesc' 		=> false
		    ), $campaign );
	
			//Campaign fields
		    $pronto_donation_campaign = get_post_meta($attrs['campaign'], 'pronto_donation_campaign', true);
		    $pronto_donation_campaign['currency'] = $this->class->pronto_donation_currency();
		    $pronto_donation_campaign['post'] = get_post($attrs['campaign'], true);



			require_once('partials/pronto_donation-public-campaign.php');
		}
		else
		{
		    //Display the donation fields
			$attrs = shortcode_atts( array(
		        'campaign' 		=> 0,
		        'hidetitle' 	=> false,
		        'hidebanner' 	=> false,
		        'hidedesc' 		=> false
		    ), $campaign );

			//Payment method
			$payment_methods = $this->class->pronto_donation_payment_methods();

			//Campaign fields
		    $pronto_donation_campaign = get_post_meta($attrs['campaign'], 'pronto_donation_campaign', true);

		    $pronto_donation_campaign['post'] = get_post($attrs['campaign'], true);

			//Donor user fields
		    $pronto_donation_user_info = get_post_meta($attrs['campaign'], 'pronto_donation_user_info', true);

		    require_once('partials/pronto_donation-public-campaign-full.php');
		}
	}

	function _array_to_object($option){
    	$options = new stdClass();
		foreach ($option as $key => $value)
		{
		    $options->$key = $value;
		}
		return $options;
	}

	function _check_field_value($post, $field){
		if(isset($post[$field])){
			echo $post[$field];
		}else{
			echo '';
		}
	}

}
