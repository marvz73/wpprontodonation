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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pronto_donation-public.js', array( 'jquery' ), $this->version, false );

	}

	//
	// Desc: Pronto Campaign
	// Author: Marvin Aya-ay
	private $base = __DIR__ . '/../payments/';
	public function pronto_donation_campaign( $campaign_id ) {
		
		//Process the payment here...
	    if($_POST)
	    {
	    	print_r($_POST);
	    	$campaign_data = $_POST;

	    	update_post_meta($campaign_data['campaign'], $campaign_data);

	    	// wp_redirect('/wordpress');
	    }
	    //Display the donation fields
	    else
	    {

			$attrs = shortcode_atts( array(
		        'campaign' => 0,
		    ), $campaign_id );

			//Payment method
			$payment_methods = $this->class->pronto_donation_payment_methods();

			//Donor user fields
		    $pronto_donation_user_info = get_post_meta($attrs['campaign'], 'pronto_donation_user_info', true);
			
		    // $pronto_donation_campaign = get_post_meta($attrs['campaign'], 'pronto_donation_campaign', true);

		    require_once('partials/pronto_donation-public-campaign.php');
	    }
	}


	public function pronto_donation_thank_you_page_message(){
		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');

		$thank_you_page_message_page = (empty($pronto_donation_settings['ThankYouPageMessagePage'])) ? "" : $pronto_donation_settings['ThankYouPageMessagePage'];

		$my_postid = $thank_you_page_message_page;//This is page id or post id
		$content_post = get_post($my_postid);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
	}
	public function pronto_donation_info_on_offline_payment_panel_page(){
		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');

		$info_on_offline_payment_panel_page = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanelPage'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanelPage'];

		$my_postid = $info_on_offline_payment_panel_page;//This is page id or post id
		$content_post = get_post($my_postid);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
	}
	public function pronto_donation_instructions_emailed_to_offline_donor_before_payment(){
		$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');

		$instructions_emailed_to_offline_donor_before_payment_page = (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'];	

		$my_postid = $instructions_emailed_to_offline_donor_before_payment_page;//This is page id or post id
		$content_post = get_post($my_postid);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
	}

}
