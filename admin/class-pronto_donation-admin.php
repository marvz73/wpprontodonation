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
	 * The variable containing all the custom function
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $class    The variable container of all the custom functions
	 */

	private $class;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $class ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->class = $class;
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

	 	//Campaign
		$donation_menu_campaign = add_submenu_page(
			'donation_page',
	        'Pronto Donation',
	        'Pronto Campaign',
	        'administrator',
 			'edit.php?post_type=campaign'
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
	        array( $this, 'pronto_donation_settings_menu_page' ),    // The name of the function to call when rendering the menu for this page
	       	'dashicons-money',						        // The icon for this menu.
	        '83.7'                                          // The position in the menu order this menu should appear
	    );
	}


	//
	// Payments Settings
	// Author: Marvin B. Aya-ay
	public function pronto_donation_payment_page(){

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


	// BOF Pronto Donation Campaign 
	// Author: Danryl T. Carpio

	/*
	* This 2 function pronto_donation_wp_gear_manager_admin_scripts, pronto_donation_wp_gear_manager_admin_styles
	* will load the wordpress media uploader script and styles
	*/
	public function pronto_donation_wp_gear_manager_admin_scripts() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
	}

	public function pronto_donation_wp_gear_manager_admin_styles() {
		wp_enqueue_style('thickbox');
	}

	/*
	* This function is used to create a custom postype
	* 'campaign'
	*/
	public function pronto_donation_campaign_posttype() {
		register_post_type( 'campaign',
		array(
			'labels' => array(
				'name' => __( 'Campaign' ),
				'singular_name' => __( 'Campaigns' ),
				'add_name' => 'Add New',
				'add_new_item' => 'Add New Campaign',
				'edit' => 'Edit',
				'edit_item' => 'Edit Campaign',
				'new_item' => 'New Campaign',
				'view' => 'View Campaign',
				'view_item' => 'Niew Campaign',
				'search_items' => 'Search Campaigns',
				'parent' => 'Parent Campaign',
				'not_found' => 'No Campaigns found',
				'no_found_in_trash' => 'No Campaigns in Trash'
				),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'Campaigns'),
			'show_in_menu' => false,
			'supports' => array('title')
			)
		);
	}

	/*
	* This function will add a custom meta box for
	* custom postype 'campaign'
	*/
	public function pronto_donation_meta_box(){
		add_meta_box(
			'pronto_donation_campagin_meta',
			'Campaign',
			array( $this, 'pronto_donation_meta_box_callback' ),
			'campaign',
			'normal',
			'core'
		);
	}

	/*
	* This function will act as the display of the
	* custom posttype meta box
	*/
	public function pronto_donation_meta_box_callback($post) {

		$donation_data = $this->class->pronto_donation_get_donation_details( $post );

		print_r($donation_data);
		exit();

		wp_nonce_field(basename( __FILE__ ), 'pronto_donation_campaign_nonce' );
		$campaigns = get_post_meta( $post->ID );

		$pronto_donation_settings = get_option('pronto_donation_settings', '');
 		$currency_val = $pronto_donation_settings['SetCurrencySymbol'];

		if( array_key_exists( 'pronto_donation_campaign', $campaigns ) && array_key_exists( 'pronto_donation_user_info', $campaigns ) ) {

			$campaign_info = unserialize( $campaigns['pronto_donation_campaign'][0] );
			$user_information = unserialize( $campaigns['pronto_donation_user_info'][0] );

			$sizeofaray = 0;

			if( !empty( $campaign_info['amount_level'] ) ) {

	 			$explode_amount_level = explode( ",", $campaign_info['amount_level'] );

	 			$amount_l = '';
	 			$sizeofaray = sizeof($explode_amount_level);

	 			if($sizeofaray !== 0) {
	 				for ($i=0; $i < $sizeofaray; $i++) {
	 					if($i > 0) {
	 						$amount_l .= " " . $explode_amount_level[$i];
	 					} else {
	 						$amount_l .= $explode_amount_level[$i];
	 					}
	 				}
	 			}
			}

		} else {

			$amount_l = "10,20,30,40";
	 		$explode_amount_level = explode( ",", $amount_l );

		 	$sizeofaray = sizeof($explode_amount_level);
		 	if($sizeofaray !== 0) {
		 		$amount_l = "";
		 		for ($i=0; $i < $sizeofaray; $i++) {
		 			if($i > 0) {
		 				$amount_l .= " " . $explode_amount_level[$i];
		 			} else {
		 				$amount_l .= $explode_amount_level[$i];
		 			}
		 		}
		 	}
		}

		?>

		<div class="wrap">
 			<form id="campagin-form" method="POST" action="" >
				<table class="form-table">
					<tbody>
						<tr>
							<td>
								<h3>Campaign</h3>
							</td>
						</tr>
 
 						<tr>
							<th scope="row"><label for="donation_target">Donation Target</label></th>
							<td>
								<input placeholder="0.00" type="text" name="donation_target" id="donation_target" class="regular-text" value="<?php if( !empty( $campaign_info['donation_target'] ) ) echo esc_attr( $campaign_info['donation_target'] ); ?>">
								<p class="description">Your campaign donation target</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="banner_image_btn">Banner Image</label></th>
							<td>
								<p><img id="banner_image_img" src="<?php if( !empty( $campaign_info['banner_image'] ) ) echo esc_attr( $campaign_info['banner_image'] ); ?>" width="100" height="100" alt=""></p>
								<div style="display: inline-flex;">
									<input style="font-size: 12px;" readonly id="banner_image" type="text" size="36" name="banner_image" value="<?php if( !empty( $campaign_info['banner_image'] ) ) echo esc_attr( $campaign_info['banner_image'] ); ?>" />
									&nbsp;<input class="button button-primary" id="upload_image_button" type="button" value="Upload Image" />
								</div>
								<p class="description">Select banner image on wordpress media</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="hide_custom_amount">Hide Custom Amount</label></th>
							<td>
								<label class="" for="hide_custom_amount"><input name="hide_custom_amount" type="checkbox" id="hide_custom_amount" <?php if ( !empty( $campaign_info['hide_custom_amount'] ) && $campaign_info['hide_custom_amount'] !== 0 ) echo "checked='checked'" ?>  > Hide custom amount on form </label>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="amount_level">Amount Levels</label></th>
							<td>
								<input type="text" name="amount_level" id="amount_level">
								<input type="hidden" name="amount_level_data" id="amount_level_data" value="<?php if( !empty( $amount_l ) ) echo esc_attr( $amount_l ) ?>">
								<a class="button button-primary" id="add_amount_btn" href=''>Add</a>

								<div id="amount_level_display">
									<?php

									if( $sizeofaray > 0 ) {
										for ($i=0; $i < $sizeofaray; $i++) {
											?>
											<p id="amount-level<?php echo $explode_amount_level[$i] ?>"><a class="dashicons-before dashicons-trash" style=" color: #666; cursor: pointer;" 
												data="<?php echo $explode_amount_level[$i] ?>" id="amount-remove<?php echo $explode_amount_level[$i] ?>"> </a> <?php echo $currency_val.$explode_amount_level[$i] ?> 
											</p>
											<?php
										}
									}
									?>
								</div>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="donation_type">Donation Type</label></th>
							<td>
								<select name="donation_type" id="donation_type">
									<option value="single" <?php if( !empty( $campaign_info['donation_type'] ) && esc_attr($campaign_info['donation_type']) == 'single' ) echo "selected='selected'"; ?> >Single</option>
									<option value="recurring" <?php if( !empty( $campaign_info['donation_type'] ) && esc_attr($campaign_info['donation_type']) == 'recurring' ) echo "selected='selected'"; ?> >Recurring</option>
									<option value="both" <?php if( !empty( $campaign_info['donation_type'] ) && esc_attr($campaign_info['donation_type']) == 'both' ) echo "selected='selected'"; ?> >Both</option>
								</select>
								<p class="description">Select Donation Type</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="donation_campaign_filter">Campaign Filter</label></th>
							<td>
								<input type="text" name="donation_campaign_filter" id="donation_campaign_filter" class="regular-text" value="<?php if( !empty( $campaign_info['donation_campaign_filter'] ) ) echo esc_attr( $campaign_info['donation_campaign_filter'] ); ?>">
								<p class="description">This field use to filter campaign's donations</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="show_gift_field">Show Gift Field</label></th>
							<td>
								<label class="" for="show_gift_field"><input name="show_gift_field" type="checkbox" id="show_gift_field" <?php if ( !empty( $campaign_info['show_gift_field'] ) && $campaign_info['show_gift_field'] !== 0 ) echo "checked='checked'" ?>  >  </label>
							</td>
						</tr>

					</tbody>
					<tbody>

						<tr>
							<td>
								<h3>User Information</h3>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_donor_type_option">Donor Type</label></th>
							<td>
								<select name="user_donor_type_option" id="user_donor_type_option">
									<option value="show" <?php if( !empty( $user_information['user_donor_type_option'] ) && esc_attr($user_information['user_donor_type_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_donor_type_option'] ) && esc_attr($user_information['user_donor_type_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_donor_type_option'] ) && esc_attr($user_information['user_donor_type_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for donor type</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_email_option">Email</label></th>
							<td>
								<select name="user_email_option" id="user_email_option" disabled>
									<option value="show">Show</option>
									<option value="hide">Hide</option>
									<option value="required" selected="selected">Required</option>
								</select>
								<p class="description">Select an option for user email</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_firstname_option">First Name</label></th>
							<td>
								<select name="user_firstname_option" id="user_firstname_option" disabled>
									<option value="show">Show</option>
									<option value="hide">Hide</option>
									<option value="required" selected="selected">Required</option>
								</select>
								<p class="description">Select an option for user first name</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_lastname_option">Last Name</label></th>
							<td>
								<select name="user_lastname_option" id="user_lastname_option" disabled>
									<option value="show">Show</option>
									<option value="hide">Hide</option>
									<option value="required" selected="selected">Required</option>
								</select>
								<p class="description">Select an option for user last name</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_phone_option">Phone</label></th>
							<td>
								<select name="user_phone_option" id="user_phone_option">
									<option value="show" <?php if( !empty( $user_information['user_phone_option'] ) && esc_attr($user_information['user_phone_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_phone_option'] ) && esc_attr($user_information['user_phone_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_phone_option'] ) && esc_attr($user_information['user_phone_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user phone</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_address_option">Address</label></th>
							<td>
								<select name="user_address_option" id="user_address_option">
									<option value="show" <?php if( !empty( $user_information['user_address_option'] ) && esc_attr($user_information['user_address_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_address_option'] ) && esc_attr($user_information['user_address_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_address_option'] ) && esc_attr($user_information['user_address_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user address</p>
							</td>	
						</tr>

						<tr>
							<th scope="row"><label for="user_country_option">Country</label></th>
							<td>
								<select name="user_country_option" id="user_country_option">
									<option value="show" <?php if( !empty( $user_information['user_country_option'] ) && esc_attr($user_information['user_country_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_country_option'] ) && esc_attr($user_information['user_country_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_country_option'] ) && esc_attr($user_information['user_country_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user country</p>
							</td>	
						</tr>

						<tr>
							<th scope="row"><label for="user_state_option">State</label></th>
							<td>
								<select name="user_state_option" id="user_state_option">
									<option value="show" <?php if( !empty( $user_information['user_state_option'] ) && esc_attr($user_information['user_state_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_state_option'] ) && esc_attr($user_information['user_state_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_state_option'] ) && esc_attr($user_information['user_state_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user state</p>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="user_postcode_option">Post Code</label></th>
							<td>
								<select name="user_postcode_option" id="user_postcode_option">
									<option value="show" <?php if( !empty( $user_information['user_postcode_option'] ) && esc_attr($user_information['user_postcode_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_postcode_option'] ) && esc_attr($user_information['user_postcode_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_postcode_option'] ) && esc_attr($user_information['user_postcode_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user post code</p>
							</td>	
						</tr>

						<tr>
							<th scope="row"><label for="user_suburb_option">Suburb</label></th>
							<td>
								<select name="user_suburb_option" id="user_suburb_option">
									<option value="show" <?php if( !empty( $user_information['user_suburb_option'] ) && esc_attr($user_information['user_suburb_option']) == 'single' ) echo "selected='selected'"; ?> >Show</option>
									<option value="hide" <?php if( !empty( $user_information['user_suburb_option'] ) && esc_attr($user_information['user_suburb_option']) == 'hide' ) echo "selected='selected'"; ?> >Hide</option>
									<option value="required" <?php if( !empty( $user_information['user_suburb_option'] ) && esc_attr($user_information['user_suburb_option']) == 'required' ) echo "selected='selected'"; ?> >Required</option>
								</select>
								<p class="description">Select an option for user suburb</p>
							</td>
						</tr>

					</tbody>
				</table>
			</form>

		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($) {

				if( $('#amount_level_data').val() == null || $('#amount_level_data').val() == '' ) {
					$('#hide_custom_amount').attr('disabled', true);
				}
 				
 				/*
 				* This jquery keypress event function for text field
 				* is to restrict field to input only numbers from [0-9]
 				*/
				$('#donation_target, #amount_level').keypress(function(e) {
				    var a = [];
				    var k = e.which;
				    
				    for (i = 48; i < 58; i++)
				        a.push(i);
				    
				    if (!(a.indexOf(k)>=0))
				        e.preventDefault();
				});

				/*
 				* This native javascript function will remove
 				*  items on existing array
 				*/
				Array.prototype.remove = function() {
					var what, a = arguments, L = a.length, ax;
					while (L && this.length) {
						what = a[--L];
						while ((ax = this.indexOf(what)) !== -1) {
							this.splice(ax, 1);
						}
					}
					return this;
				};

				/*
 				* This function will create a click event
 				* for dynamic php amount level
 				*/
				function bindOnclick(data) {
					for (var i = 0; i < data.length; i++) {
						var data_now = data[i];
						$('#amount-remove'+data[i]).click(function(){

							var removeddata = $(this).attr('data');
							data.remove(removeddata);
							var newtextdata = '';
							for(var a = 0; a < data.length; a++) {
								if(a > 0) {
									newtextdata += " " + data[a];
								} else {
									newtextdata += data[a];
								}
							}

							$('#amount_level_data').val(newtextdata);
							$('#amount-level'+$(this).attr('data')).hide();

							var datavalue = $('#amount_level_data').val().split(' ');
							console.log(datavalue)

							if(datavalue.length === 1 && datavalue[0] === '') {
								$('#hide_custom_amount').prop('checked', false);
								$('#hide_custom_amount').attr('disabled', true);
							}

						});
					}
				}

				var datavalue = $('#amount_level_data').val().split(' ');
				bindOnclick(datavalue);

				/*
 				* This event click function is for the button add amount level,
 				* the items will add into hidden textfield 
 				*/
				$('#add_amount_btn').click(function(e){

					var data = $('#amount_level').val();
					var currency_val = "<?php echo $currency_val ?>";
				 
					if(data != null && data != '') {

						var datavalue = $('#amount_level_data').val().split(' ');
 						var datanumber = parseInt(data);

						if(datanumber < 1) {
							return false;
						}

						if( $.inArray( data, datavalue ) == -1 ) {
							if($('#amount_level_data').val() == '' || $('#amount_level_data').val == null) {
								$('#amount_level_data').val( $('#amount_level_data').val() + data );
							} else {
								$('#amount_level_data').val( $('#amount_level_data').val() +" "+ data );
							}
							$('#amount_level_display').append('<p id="amount-level'+data+'"><a class="dashicons-before dashicons-trash" style=" color: #666; cursor: pointer;" data="'+data+'"id="amount-remove'+data+'"></a>'+' '+currency_val+data+'</p>');
						}

						var datavalue = $('#amount_level_data').val().split(' ');
						bindOnclick(datavalue);
						$('#amount_level').val('');

						if( $('#amount_level_data').val() == null || $('#amount_level_data').val() == '' ) {
							$('#hide_custom_amount').attr('disabled', true);
						} else {
							$('#hide_custom_amount').prop('disabled', false);
						}

					}
					e.preventDefault();
				});

				/*
 				* This event click function is for the upload button,
 				* when button click upload it will show the wordpress media uploader
 				*/
				$('#upload_image_button').click(function() {
					formfield = $('#banner_image').attr('name');
					tb_show('', 'media-upload.php?type=image&TB_iframe=true');
					return false;
				});

			 	/*
 				* This function will place the link for
 				*  the selected wordpress media image
 				*/
				window.send_to_editor = function(html) {
					imgurl = $('img',html).attr('src');
					$('#banner_image').val(imgurl);
					$('#banner_image_img').attr("src", imgurl);
					tb_remove();
				}
			});
		</script>

		<?php
	}

	/*
	* This function will execute when publishing campaign or editing campaign,
	* this will add a post_meta called pronto_donation_campaign, pronto_donation_user_info
	* for every campaign created
	*/
	public function pronto_donation_campagin_save_post( $post_id ) {

		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset($_POST['pronto_donation_campaign_nonce'] ) && wp_verify_nonce( $_POST['pronto_donation_campaign_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';

		if( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}

		if( isset( $_POST['publish'] ) || isset( $_POST['save'] ) ) {

  			$amount_level_data = array();

			if( isset( $_POST['amount_level_data'] ) && !empty( $_POST['amount_level_data'] ) ) {
				$amount_level_data = explode( " ", $_POST['amount_level_data'] );
			}
 	 		
 	 		$amountdata = "";
 	 		if( !empty($amount_level_data) ) {
 	 			$amountdata = implode(",", $amount_level_data);
 	 		}

 	 		date_default_timezone_set('Australia/Melbourne');
			$date = date('M d, Y h:i:s a', time());

			$data = ( isset( $_POST['hide_custom_amount'] ) ) ? 1 : 0 ;
			$data1 = ( isset( $_POST['show_gift_field'] ) ) ? 1 : 0 ;

			$campaign_data = array();
			$campaign_data['donation_target'] = sanitize_text_field( $_POST['donation_target'], 2 );
			$campaign_data['banner_image'] = sanitize_text_field( $_POST['banner_image'] );
			$campaign_data['hide_custom_amount'] = $data;
			$campaign_data['show_gift_field'] = $data1;
			$campaign_data['amount_level'] = $amountdata;
			$campaign_data['donation_type'] = sanitize_text_field( $_POST['donation_type'] );
			$campaign_data['donation_campaign_filter'] = sanitize_text_field( $_POST['donation_campaign_filter'] );
			$campaign_data['campaign_shortcode'] = '[pronto-donation campaign=' . $post_id .']';
			$campaign_data['date_updated'] = $date;
			update_post_meta( $post_id, 'pronto_donation_campaign', $campaign_data );

			$user_information = array();
			$user_information['user_donor_type_option'] = sanitize_text_field( $_POST['user_donor_type_option'] );
			$user_information['user_address_option'] = sanitize_text_field( $_POST['user_address_option'] );
			$user_information['user_email_option'] = 'required';
			$user_information['user_country_option'] = sanitize_text_field( $_POST['user_country_option'] );
			$user_information['user_firstname_option'] = 'required';
			$user_information['user_state_option'] = sanitize_text_field( $_POST['user_state_option'] );
			$user_information['user_lastname_option'] ='required';
			$user_information['user_postcode_option'] = sanitize_text_field( $_POST['user_postcode_option'] );
			$user_information['user_phone_option'] = sanitize_text_field( $_POST['user_phone_option'] );
			$user_information['user_suburb_option'] = sanitize_text_field( $_POST['user_suburb_option'] );
			update_post_meta( $post_id, 'pronto_donation_user_info', $user_information );
		}
	}

	/*
	* This function will modify the table header for custom posttype
	*/
	public function pronto_donation_post_column( $columns ) {

		$columns = array(
			'cb'	 	=> '<input type="checkbox" />',
			'banner_image' => __( 'Banner Image' ),
			'title' => __( 'Donation Name' ) ,
			'donation_target' => __( 'Donation Target' ),
			'donation_type' => __( 'Donation Type' ),
			'campaign_shortcode' => __( 'Shortcode' ),
			'date_updated' => __('Last Updated Date'),
			'date' => __('Date Created')
		);

		return $columns;
	}

	/*
	* This will display all the campaign data 
	* into the table
	*/
	public function pronto_donation_column_data( $column, $post_id){
		global $post;

		$campaigns = get_post_meta( $post_id );

		$campaign_info = unserialize( $campaigns['pronto_donation_campaign'][0] );
		$user_information = unserialize( $campaigns['pronto_donation_user_info'][0] );

		$pronto_donation_settings = get_option('pronto_donation_settings', '');
 
        $currency_val = $pronto_donation_settings['SetCurrencySymbol'];

		switch( $column ) {
			
			case 'banner_image' :
 				$data_banner = $campaign_info['banner_image'];
 				echo '<img id="banner_image_img" src="'. $data_banner .'" width="50" height="50" alt="">';
			break;
			
			case 'title' :

			break;
			
			case 'campaign_shortcode' :
 				$data_shortcode = $campaign_info['campaign_shortcode'];
 				echo $data_shortcode;
			break;
			
			case 'donation_type' :
 				$data_donation_type = $campaign_info['donation_type'];
 				if($data_donation_type == 'recurring') {
 					echo 'Recurring';
 				} elseif($data_donation_type == 'single') {
 					echo 'Single';
 				} elseif($data_donation_type == 'both') {
 					echo 'Both';
 				}
			break;

			case 'donation_target' :
 				$data_donation_target = $campaign_info['donation_target'];
 				echo $currency_val .''. number_format( (int) $data_donation_target, 2, '.', ',');
			break;

			case 'date_updated' :
				$date_date_created = $campaign_info['date_updated'];
 				echo $date_date_created;
			break;
 
		}

	}

	/*
	* This will resize the table header size
	* of the custom posttype 
	*/
	public function pronto_donation_campaign_head_css() {
		echo '<style>
			.column-banner_image {width: 10%}
			.column-donation_name {width: 25%}
			.column-donation_target {width: 12%}
			.column-donation_type {width: 11%}
			.column-campaign_shortcode {width: 12%}
			.column-date_updated {width: 12%}
			.column-date {width: 15%}
		</style>';
	}

	/**
	 * Join posts and postmeta tables
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
	 */
 	public function pronto_donation_cf_search_join( $join ) {
	    global $wpdb;

	    if ( is_search() ) {    
	        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
	    }
	    
	    return $join;
	}

	/**
	 * Modify the search query with posts_where
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
	 */
	public function pronto_donation_cf_search_where( $where ) {
	    global $pagenow, $wpdb;
	   
	    if ( is_search() ) {
	        $where = preg_replace(
	            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
	            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
	    }

	    return $where;
	}

	/**
	 * Prevent duplicates
	 *
	 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
	 */
	public function pronto_donation_cf_search_distinct( $where ) {
	    global $wpdb;

	    if ( is_search() ) {
	        return "DISTINCT";
	    }

	    return $where;
	}
  	
  	/**
	 * Make parent menu of the custom post type 'Campaign' visible 
	 * When campaign post type is active
	 */
	public function pronto_donation_fix_admin_parent_file($parent_file){
	    global $submenu_file, $current_screen;
	    if($current_screen->post_type == 'campaign') {
	        $submenu_file = 'edit.php?post_type=campaign';
	        $parent_file = 'donation_page';
	    }
	    return $parent_file;
	}
	// EOF campaign 
	public function pronto_donation_register_post_type(){

		$args = array(
	      'public' => true,
	      'label'  => 'pronto_donation'
	    );
	    register_post_type( 'pronto_donation', $args );	
	}

	public function pronto_donation_remove_menu_items() {
	   
	    remove_menu_page( 'edit.php?post_type=pronto_donation' );

	}
	
	public function pronto_donation_settings_menu_page(){
		global $title;
		require_once('partials/pronto_donation-admin-display.php');
	}

}
