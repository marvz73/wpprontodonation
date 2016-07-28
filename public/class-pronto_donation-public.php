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
		//================================ Country And States Library =============================//
		wp_enqueue_script('countries' , plugin_dir_url( __FILE__ ) . 'js/countries.js', array( 'jquery' ), $this->version, false );
		//================================ Country And States Library =============================//
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pronto_donation-public.js', array( 'jquery' ), $this->version, false );

	
	}


	//
	// Desc: Pronto Campaign
	// Author: Marvin Aya-ay
	private $base = __DIR__ . '/../payments/';
	public function pronto_donation_campaign( $campaign_id ) {
		$option = get_option('pronto_donation_settings');
		//Process the payment here...
	    if($_POST)
	    {
	    	$campaign_data = $_POST;
	    	if($campaign_data['action'] == 'process_donate' && wp_verify_nonce( $campaign_data['nonce'], 'donation'))
	    	{
			
			 	// global $wpdb;
				// $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '123123123123' WHERE meta_id = 28");
	    	
	    		$campaign_data['status'] = 'pending';
	    		$campaign_data['CurrencyCode'] = $option['CurrencyCode'];

	    		$payment_methods = $this->class->pronto_donation_payment_methods();

	    		foreach($payment_methods as $index=>$payment)
	    		{
	    			if(strtolower($campaign_data['payment']) == strtolower($payment->payment['payment_name']))
	    			{
	    				$campaign_data['payment_info'] = $payment;
	    			}
	    		}

	    		$campaign_data['redirectURL'] = get_home_url() . '/?p=' . $option['ThankYouPageMessagePage'] . '&payment_gateway=' . $campaign_data['payment'];

	    		$campaign_data['CancelUrl']   = get_home_url() . '/?p=' . $option['CancelPageMessagePage']. '&payment_status=C&ref=' . $campaign_data['post_meta_id'];

  				$post_meta_id = add_post_meta($campaign_data['donation_campaign'], 'pronto_donation_donor', $campaign_data);

  				$campaign_data['post_meta_id'] = $post_meta_id;

	    		// Call the payment function to execute payment action
	    		$campaign_data['payment_info']->payment_process($campaign_data);

	    	}
	    }
	    else
	    {

	    	$campaignOption = new stdClass();
			foreach ($option as $key => $value)
			{
			    $campaignOption->$key = $value;
			}

		    //Display the donation fields
			$attrs = shortcode_atts( array(
		        'campaign' => 0,
		    ), $campaign_id );

			//Payment method
			$payment_methods = $this->class->pronto_donation_payment_methods();

			//Campaign fields
		    $pronto_donation_campaign = get_post_meta($attrs['campaign'], 'pronto_donation_campaign', true);

			//Donor user fields
		    $pronto_donation_user_info = get_post_meta($attrs['campaign'], 'pronto_donation_user_info', true);

		    require_once('partials/pronto_donation-public-campaign.php');

	    }
	}

	public function pronto_donation_override_template($page_template){

		if (isset($_GET['payment_gateway']) && get_the_ID() == get_option('pronto_donation_settings')['ThankYouPageMessagePage'])
		{
    		global $wpdb;

    		$payment_methods = $this->class->pronto_donation_payment_methods();

    		foreach($payment_methods as $index=>$payment)
    		{
    			
    			if(strtolower($_GET['payment_gateway']) == strtolower($payment->payment['payment_name']))
    			{
    				//call payment process complete
    				$payment->payment_complete($_GET);

					// SALESFORCE LOGIC SYNC HERE...

    			}
    		}
		} //Cancel Transaction
		else if ($_GET['payment_status'] == 'C' && get_the_ID() == get_option('pronto_donation_settings')['CancelPageMessagePage'])
		{	

			$campaign_id = esc_html($_GET['ref']);

			$campaignDonor = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_id = " . $campaign_id);

			$campaign = maybe_unserialize($campaignDonor[0]->meta_value);
			if($campaign['status'] != 'CANCELLED')
			{
				$campaign['status'] = 'CANCELLED';
				$wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '".(maybe_serialize($campaign))."' WHERE meta_id = " . $campaign_id);
			}
		}

	}

	public function pronto_donation_thank_you_page_message(){
		global $title;
		require_once('partials/pronto_donation-thank-you-page-message.php');
	}

	public function pronto_donation_info_on_offline_payment_panel_page(){
		global $title;
		require_once('partials/pronto_donation-public-info-on-offline-payment-panel-page.php');
	}

	public function pronto_donation_instructions_emailed_to_offline_donor_before_payment(){
		global $title;
		require_once('partials/pronto_donation-public-instructions-emailed-to-offline-donor-before-payment.php');
	}

}
