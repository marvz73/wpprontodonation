<?php if(isset($this->errors)): ?>
	
<div class="pronto_donation_error">
<?php
	foreach($this->errors as $key=>$error)
	{
		echo "<p style='color: red;'>* ".$error."</p>";
	}
?>
</div>
<?php endif; ?>

<div id="campaign_banner">
	<img src="<?php echo $pronto_donation_campaign['banner_image'] ?>">
</div>

<p><?php echo $pronto_donation_campaign['post']['post_content'] ?></p>

<!-- <form method="post" action="<?php echo home_url( '/wp-admin/admin-post.php' ) ?>"> -->
<form method="post" class="<?php echo $this->campaignOption->FormClass ?>" >


<!-- //===================== Address Validation ======================================//  -->
	<?php
	$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
	$enable_address_validation = (empty($pronto_donation_settings['EnableAddressValidation'])) ? "" : $pronto_donation_settings['EnableAddressValidation'];
	$google_geocode_api_key = (empty($pronto_donation_settings['GoogleGeocodeAPIKey'])) ? "" : $pronto_donation_settings['GoogleGeocodeAPIKey'];
	?>

	<input id="enable_address_validation" value="<?php echo $enable_address_validation;?>" hidden/>
	<input id="google_geocode_api_key" value="<?php echo $google_geocode_api_key;?>" hidden/>
<!-- //===================== Address Validation ======================================//  -->	



	<!-- Donor Information -->
	
	<h3>Donation Information</h3>
	<hr>
		<?php if($this->class->pronto_donation_has_payment_amount_level($attrs['campaign'])): ?>
			<p>
				<label>Donation Amount </label>

				<?php
					$this->class->pronto_donation_payment_amount_level($attrs['campaign']);
				?>
				<?php if(!$pronto_donation_campaign['hide_custom_amount']): ?>
				<label for="other_amount"><input id="other_amount" class="pd_amount" type="radio" name="pd_amount" value="0" />Other</label>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php 

		if(!$pronto_donation_campaign['hide_custom_amount']): ?>
		<p>
			<label>Donation Custom Amount </label>
			<?php if($this->class->pronto_donation_has_payment_amount_level($attrs['campaign'])): ?>
				<span id="currency" class="<?php echo $this->campaignOption->InputFieldClass ?>">
					<span><?php echo $this->class->pronto_donation_currency(); ?></span>
					<input  disabled="" type="number" id="pd_custom_amount" name="pd_custom_amount" placeholder="00" />
				</span>
			<?php else: ?>
				<span id="currency" class="<?php echo $this->campaignOption->InputFieldClass ?>">
					<span><?php echo $this->class->pronto_donation_currency(); ?></span>
					<input type="number" id="pd_custom_amount" name="pd_custom_amount" placeholder="00"  />
				</span>
			<?php endif; ?>
		</p>
		<?php endif; ?>
		
		<p>
			<label>Donation Type</label>
			<?php if($pronto_donation_campaign['donation_type'] == 'both'): ?>	
					<label>
						<input type="radio" name="donation_type" value="single" checked="true" /> Single
					</label>
					<label>
						<input type="radio" name="donation_type" value="recurring" /> Recurring
					</label>
			<?php elseif($pronto_donation_campaign['donation_type'] == 'single'): ?>
				<label>
					<input type="radio" name="donation_type" value="single" /> Single
				</label>
			<?php elseif($pronto_donation_campaign['donation_type'] == 'recurring'): ?>	
				<label>
					<input type="radio" name="donation_type" value="recurring" /> Recurring
				</label>
			<?php endif; ?>
		</p>

		<?php if($pronto_donation_campaign['show_gift_field']): ?>
			<p>
				Is this a Gift <input type="checkbox" name="donation_gift" /> 
			</p>
		<?php endif; ?>



	<!-- Donor Information -->
	<h3>Donor Information</h3>
	<hr>
		<?php if($pronto_donation_user_info['user_donor_type_option'] != 'hide'): ?>
			<p>
				<label>Donor Type</label>
				<select id="donorType" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="donor_type" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_donor_type_option']) ?> >
					<option value="I">Individual</option>
					<option value="B">Business</option>
				</select>
			</p>

			<p id="companyName" style="display: none;">
				<label>Company Name</label> 
				<input class="<?php echo $this->campaignOption->InputFieldClass ?>" name="companyName" type="text" />	
			</p>

		<?php endif; ?>



		<?php if($pronto_donation_user_info['user_email_option'] != 'hide'): ?>
		<p>
			<label>Email</label>
			<input name="email" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'email') ?>" type="email" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_email_option']) ?>/>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_firstname_option'] != 'hide'): ?>
		<p>
			<label>First Name</label>
			<input name="first_name" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'first_name') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_firstname_option']) ?>/>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_lastname_option'] != 'hide'): ?>
		<p>
			<label>Last Name</label>
			<input name="last_name" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'last_name') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_lastname_option']) ?>/>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_phone_option'] != 'hide'): ?>
		<p>
			<label>Phone</label>
			<input name="phone" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'phone') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_phone_option']) ?>/>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_address_option'] != 'hide'): ?>
		<p>
			<label>Address</label>
			<input id="address" name="address" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'address') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_address_option']) ?>/>
			<span id="adress_validation"></span>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_country_option'] != 'hide'): ?>
		<p>
			<label>Country</label>
			<select id="country" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="country" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_country_option']) ?>>
			</select>
			<span id="country_validation"></span>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_state_option'] != 'hide'): ?>
		<p>
			<label>State</label>
			<select id="state"  class="<?php echo $this->campaignOption->InputFieldClass ?>" name="state" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_state_option']) ?>>
			</select>
			<span id="state_validation"></span>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_postcode_option'] != 'hide'): ?>
		<p>
			<label>Post Code</label>
			<input name="post_code" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'post_code') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_postcode_option']) ?>/>
		</p>
		<?php endif; ?>




		<?php if($pronto_donation_user_info['user_suburb_option'] != 'hide'): ?>
		<p>
			<label>Suburb</label>
			<input id="suburb" name="suburb" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'suburb') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_suburb_option']) ?>/>
			<span id="suburb_validation"></span>
		</p>
		<?php endif; ?>



		<!-- Payment Method -->

		<h3>Payment</h3>
		<hr>
			<?php
				if(!empty($payment_methods)):
				foreach($payment_methods as $index=>$payment):
			?>
						<div>
							<label>
								<input <?php echo ($index==0) ? 'checked="true"' : '' ?> type="radio" name="payment" value="<?php echo $payment->get_payment_name() ?>" />
								
								<?php if($payment->option['logo']): ?>
									<img src="<?php echo $payment->get_payment_logo() ?>" width="20%" alt="<?php echo $payment->get_payment_name() ?>" />
								<?php else: 
										echo $payment->get_payment_name();
								      endif; ?>
							</label>
						</div>
			<?php
				endforeach;
				else:
					echo '<h1>No Payment avaiable</h1>';
				endif;
			?>


	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('donation') ?>" />
	<input type="hidden" name="donation_campaign" value="<?php echo $attrs['campaign'] ?>" />
	<input type="hidden" name="action" value="process_donate"/>
	
	<?php if($this->campaignOption->GoogleReCaptchaEnable && $this->campaignOption->GoogleReCaptchaSiteKey && $this->campaignOption->GoogleReCaptchaSecretKey): ?>
		<br>
		<div class="g-recaptcha" data-sitekey="<?php echo $this->campaignOption->GoogleReCaptchaSiteKey; ?>"></div>
	<?php endif; ?>
	<br>

	<p class="submit">
		<button class="button button-primary <?php echo $this->campaignOption->ButtonClass ?>"> <?php echo ($this->campaignOption->EditButtonCaption) ? $this->campaignOption->EditButtonCaption : 'Donate' ?> </button>
	</p>

</form>