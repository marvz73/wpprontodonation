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


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-form-builder.php';
		

		$this->loader = new Pronto_donation_Loader();

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

		$plugin_admin = new Pronto_donation_Admin( $this->get_plugin_name(), $this->get_version() );

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

		// $this->loader->add_shortcode( 'pronto-donation', $plugin_admin, 'pronto_donation_shortcode' );

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

		$this->loader->add_shortcode( 'pronto-donation', $plugin_public, 'pronto_donation_campaign' );
		$this->loader->add_shortcode( 'pronto-donation-TYPM', $plugin_public, 'pronto_donation_thank_you_page_message');
		$this->loader->add_shortcode( 'pronto-donation-IOOPPP', $plugin_public, 'pronto_donation_info_on_offline_payment_panel_page');
		$this->loader->add_shortcode( 'pronto-donation-IETODBP', $plugin_public, 'pronto_donation_instructions_emailed_to_offline_donor_before_payment');
	
		$this->loader->add_action( 'init', $plugin_public, 'shopello_feed_rewrites_init' );
		$this->loader->add_filter( 'page_template', $plugin_public, 'shopello_fee_page_template' );

	    

			    
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
					if($payment_method->option['enable'])
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
						$html .= '<label for="pd_amount'.$index.'"><input id="pd_amount'.$index.'" class="pd_amount" type="radio" name="pd_amount" value="'.$amount_level.'" checked="true" />' . $amount_level. '</label>';
					}
					else
					{
						$html .= '<label for="pd_amount'.$index.'"><input id="pd_amount'.$index.'" class="pd_amount" type="radio" name="pd_amount" value="'.$amount_level.'" />' . $amount_level. '</label>';
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


	public function pronto_donation_user_fields($campaign_id){

		if($campaign_id)
		{
			$pronto_donation_user_info = get_post_meta($campaign_id, 'pronto_donation_user_info', true);

// array ( 
// 'user_donor_type_option' => 'required',
// 'user_address_option' => show,
// 'user_email_option' => show,
// 'user_country_option' => show,
// 'user_firstname_option' => show,
// 'user_state_option' => show,
// 'user_lastname_option' => show,
// 'user_postcode_option' => show,
// 'user_phone_option' => show,
// 'user_suburb_option' => show
// )


			foreach($pronto_donation_user_info as $index=>$field)
			{

				if(in_array($index, array('user_donor_type_option')))
				{
					$html .= '<p>
								<label>Donor Type</label>
								<select name="'.$index.'">
									<option value="personal">Personal</option>
									<option value="business">Business</option>
								</select>
							</p>';
				}
				else
				{
				$html .= '<p>
							<label>Email</label>
							<input type="email" />
						</p>';
				}

			}
			

			echo $html;
		}
	}

}
