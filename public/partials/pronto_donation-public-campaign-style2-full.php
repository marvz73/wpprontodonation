<?php

/*
 * Title: Style 2 Themes
 * Desc: Style 2 of the Pronto Donation Plugin which form fields are in two columns
 * Date: Aug. 3, 2016
 */

?>

<div id="pronto-donation-wrapper" class="<?php echo $this->campaignOption->FormClass ?>">
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

	<div id="pronto-donation-banner">
		<img src="<?php echo $pronto_donation_campaign['banner_image'] ?>">
	</div>

	<p id="pronto-donation-desc"><?php echo $pronto_donation_campaign['post']['post_content'] ?></p>

	<form method="post" class="pronto-donation-form" >

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
				<div class="pd-container-padding clearfix">
					<div class="pd-col s12">

					<div class="clearfix pronto-donation-group pronto-donation-amount-level">
						<label>Donation Amount </label>

						<div class="clearfix">
							<?php
								$this->class->pronto_donation_payment_amount_level($attrs['campaign']);
							?>
							<?php if(!$pronto_donation_campaign['hide_custom_amount']): ?>
							<input id="other_amount" class="pd-level-amount" type="radio" name="pd_amount" value="0" />
							<label class="pd-amount" for="other_amount">Other</label>
							<?php endif; ?>
						</div>
					</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if(!$pronto_donation_campaign['hide_custom_amount']): ?>
				<div class="pd-container-padding clearfix">
					<div class="pd-col s12">
						<div class="clearfix pronto-donation-group">
							<label>Other Amount </label>
							<?php if($this->class->pronto_donation_has_payment_amount_level($attrs['campaign'])): ?>
								<div id="currency">
									<span><?php echo $this->class->pronto_donation_currency(); ?></span>
									<input class="<?php echo $this->campaignOption->InputFieldClass ?>" disabled="" type="number" id="pd_custom_amount" name="pd_custom_amount" placeholder="00" />
								</div>
							<?php else: ?>
								<span id="currency" >
									<span><?php echo $this->class->pronto_donation_currency(); ?></span>
									<input class="<?php echo $this->campaignOption->InputFieldClass ?>" type="number" id="pd_custom_amount" name="pd_custom_amount" placeholder="00"  />
								</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
					
			<div class="pd-container-padding clearfix">
				<div class="pd-col s12">
					<div class="pronto-donation-group clearfix">
						<label>Donation Type</label>
						<div class="pronto-donation-type clearfix">
							<?php if($pronto_donation_campaign['donation_type'] == 'both'): ?>	
								
								<div class="pd-container-paddding">
									<div class="pd-col s6">
										<input id="pronto-donation-type-single" type="radio" name="donation_type" value="single" checked="true" />
										<label for="pronto-donation-type-single" >One-off</label>
									</div>

									<div class="pd-col s6">
										<input  id="pronto-donation-type-recurring" type="radio" name="donation_type" value="recurring" />
										<label for="pronto-donation-type-recurring" >Monthly</label>
									</div>
								</div>

							<?php elseif($pronto_donation_campaign['donation_type'] == 'single'): ?>
							<div class="pd-container-padding">
								<div class="pd-col s6">
								<input  id="pronto-donation-type-single" type="radio" name="donation_type" value="single" checked="true"/> 
								<label for="pronto-donation-type-single" >One-off</label>
								</div>
							</div>
							<?php elseif($pronto_donation_campaign['donation_type'] == 'recurring'): ?>	
							<div class="pd-container-padding">
								<div class="pd-col s6">&nbsp;</div>
								<div class="pd-col s6">
								<input  id="pronto-donation-type-recurring" type="radio" name="donation_type" value="recurring" checked="true" /> 
								<label for="pronto-donation-type-recurring" >Monthly</label>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

	<div class="pd-container-padding clearfix">
		<div class="pd-col s12">
			<?php if($pronto_donation_campaign['show_gift_field']): ?>
				<div class="pronto-donation-group pronto-donation-gift clearfix">
					<input class="<?php echo $this->campaignOption->InputFieldClass ?>" id="donation_gift" type="checkbox" name="donation_gift">
					<label for="donation_gift">Is this a Gift</label>
				</div>
				<textarea id="gift_message" style="display:none" name="donation_gift_message" class="<?php echo $this->campaignOption->InputFieldClass ?>" rows="5" placeholder="Gift message..."></textarea>
			<?php endif; ?>
		</div>
	</div>

		<!-- Donor Information -->
		<h3>Donor Information</h3>
		<hr>



			<?php if($pronto_donation_user_info['user_donor_type_option'] != 'hide'): ?>

				<div class="pd-container-padding clearfix">
					<div class="pd-col s4">
						<div class="pronto-donation-group">
							<select placeholder="Donor Type" id="donorType" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="donor_type" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_donor_type_option']) ?> >
								<option>Donor Type</option>
								<option value="I">Individual</option>
								<option value="B">Business</option>
							</select>
						</div>
					</div>
					<div class="pd-col s8">
						<div class="pronto-donation-group" >
							<input placeholder="Company Name" disabled="disabled" id="companyName" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="companyName" type="text" />	
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if($pronto_donation_user_info['user_email_option'] != 'hide'): ?>
				<div class="pd-container-padding clearfix">
					<div class="pd-col s12">
						<div class="pronto-donation-group">
							
							<input placeholder="Email" name="email" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'email') ?>" type="email" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_email_option']) ?>/>
						</div>
					</div>
				</div>
			<?php endif; ?>


			<div class="pd-container-padding clearfix">
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_firstname_option'] != 'hide'): ?>
					<div class="pronto-donation-group">
						
						<input placeholder="First Name" name="first_name" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'first_name') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_firstname_option']) ?>/>
					</div>
					<?php endif; ?>
				</div>
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_lastname_option'] != 'hide'): ?>
						<div class="pronto-donation-group">
							
							<input placeholder="Last Name" name="last_name" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'last_name') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_lastname_option']) ?>/>
						</div>
					<?php endif; ?>
				</div>
			</div>



			<div class="pd-container-padding clearfix">
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_phone_option'] != 'hide'): ?>
					<div class="pronto-donation-group">
						
						<input placeholder="Phone" name="phone" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'phone') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_phone_option']) ?>/>
					</div>
					<?php endif; ?>
				</div>
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_address_option'] != 'hide'): ?>
						<div class="pronto-donation-group">
							
							<input placeholder="Address" type="text" id="<?php if($enable_address_validation==1){ echo 'autocomplete';}else{}?>" name="address" placeholder=""
				             onFocus="" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'address') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_address_option']) ?>/>
							<span id="adress_validation" class="invalid-address"></span>
						</div>
					<?php endif; ?>

					<!-- Addittional Autocomplete Values on Field ( Hidden )-->
					<table id="address" hidden>
				      <tr>	

				        <td class="label">Street address</td>
				        <td class="slimField">
				        	<input class="field" type="text"  id="street_number"></input>
				        </td>
				        <td class="wideField" colspan="2">
				        	<input class="field" type="text" id="route"></input>
				        </td>
				      </tr>
				    </table>
				    <!-- Addittional Autocomplete Values on Field ( Hidden )-->
				</div>
			</div>



			<div class="pd-container-padding clearfix">
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_country_option'] != 'hide'): ?>
					<div class="pronto-donation-group">
						
						<input placeholder="Country" type="text" id="country" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="country" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_country_option']) ?>/>
						<span id="country_validation"></span>
					</div>
					<?php endif; ?>
				</div>
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_state_option'] != 'hide'): ?>
					<div class="pronto-donation-group">
						
						<input placeholder="State" type="text" id="administrative_area_level_1" class="<?php echo $this->campaignOption->InputFieldClass ?>" name="state" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_state_option']) ?>/>
						<span id="state_validation"></span>
					</div>

					<?php elseif($pronto_donation_user_info['user_state_option'] == 'hide'): ?>
					<input id="administrative_area_level_1" hidden/>
					<?php endif; ?>
				</div>

			</div>


			<div class="pd-container-padding clearfix">
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_postcode_option'] != 'hide'): ?>
						<div class="pronto-donation-group">
							
							<input placeholder="Post Code" id="postal_code" name="post_code" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'post_code') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_postcode_option']) ?>/>
						</div>

					<?php elseif($pronto_donation_user_info['user_postcode_option'] == 'hide'): ?>
					<input id="postal_code" hidden/>
					<?php endif; ?>
				</div>
				<div class="pd-col s6">
					<?php if($pronto_donation_user_info['user_suburb_option'] != 'hide'): ?>
					<div class="pronto-donation-group">
					
						<input placeholder="Suburb" id="locality" name="suburb" class="<?php echo $this->campaignOption->InputFieldClass ?>" value="<?php $this->_check_field_value($_POST, 'suburb') ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_suburb_option']) ?>/>
						<span id="suburb_validation"></span>
					</div>

					<?php elseif($pronto_donation_user_info['user_suburb_option'] == 'hide'): ?>
					<input id="locality" hidden/>
					<?php endif; ?>
				</div>
			</div>




			<!-- Payment Method -->

			<h3>Payment</h3>
			<hr>
			<div class="payments clearfix pd-container-padding">
				<?php
					if(!empty($payment_methods)):
					foreach($payment_methods as $index=>$payment):
				?>
					<div class="pd-col s6">
						<input class="payment-input" id="payment<?php echo $index ?>" <?php echo ($index==0) ? 'checked="true"' : '' ?> type="radio" name="payment" value="<?php echo $payment->get_payment_name() ?>" />
						<label class="payment-method" for="payment<?php echo $index ?>">
							
							<?php if(isset($payment->option['logo']) && $payment->option['logo']): ?>
								<img src="<?php echo $payment->get_payment_logo() ?>" width="100%" alt="<?php echo $payment->get_payment_name() ?>" />
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

			</div>

			<?php

			$ezidebit_option = get_option( 'payment_option_ezidebit', 0 );

			if( $ezidebit_option["enable_ajax_payment"] == 'on' ) {
				?>
				<div class="self-payment-style">
					<?php
					$ezidebit_payment = new ezidebit();
					$ezidebit_payment->payment_self_payment( $attrs['campaign'] );
					?>
				</div>
				<div class="self-payment-msg"></div>
				<?php
			}

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

	<script type="text/javascript">
		var ajax_request_enable = '<?php echo $ezidebit_option["enable_ajax_payment"]; ?>';
		var endpoint = '<?php echo $ezidebit_option["endpoint"] ?>';
		var publicKey = '<?php echo $ezidebit_option["publickey"] ?>';
	</script>

</div>