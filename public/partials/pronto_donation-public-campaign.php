
<div class="pronto_donation_error">
<?php
	foreach($errors as $key=>$error)
	{
		echo "<p style='color: red;'>* ".$error."</p>";
	}
?>
</div>


<!-- <form method="post" action="<?php echo home_url( '/wp-admin/admin-post.php' ) ?>"> -->
<form method="post" class="<?php echo $campaignOption->FormClass ?>" >

	<!-- Donor Information -->
	<fieldset>
		<legend><h3>Donation Information</h3></legend>

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
				<span id="currency" class="<?php echo $campaignOption->InputFieldClass ?>">
					<span><?php echo $this->class->pronto_donation_currency(); ?></span>
					<input  disabled="" type="number" id="pd_custom_amount" name="pd_custom_amount" placeholder="00" />
				</span>
			<?php else: ?>
				<span id="currency" class="<?php echo $campaignOption->InputFieldClass ?>">
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


	</fieldset>


	<!-- Donor Information -->
	<fieldset>
		<legend><h3>Donor Information</h3></legend>

		<?php if($pronto_donation_user_info['user_donor_type_option'] != 'hide'): ?>
			<p>
				<label>Donor Type</label>
				<select id="donorType" class="<?php echo $campaignOption->InputFieldClass ?>" name="donor_type" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_donor_type_option']) ?> >
					<option value="I">Individual</option>
					<option value="B">Business</option>
				</select>
			</p>

			<p id="companyName" style="display: none;">
				<label>Company Name</label> 
				<input class="<?php echo $campaignOption->InputFieldClass ?>" name="companyName" type="text" />	
			</p>

		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_email_option'] != 'hide'): ?>
		<p>
			<label>Email</label>
			<input name="email" class="<?php echo $campaignOption->InputFieldClass ?>" type="email" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_email_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_firstname_option'] != 'hide'): ?>
		<p>
			<label>First Name</label>
			<input name="first_name" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_firstname_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_lastname_option'] != 'hide'): ?>
		<p>
			<label>Last Name</label>
			<input name="last_name" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_lastname_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_phone_option'] != 'hide'): ?>
		<p>
			<label>Phone</label>
			<input name="phone" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_phone_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_address_option'] != 'hide'): ?>
		<p>
			<label>Address</label>
			<input name="address" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_address_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_country_option'] != 'hide'): ?>
		<p>
			<label>Country</label>
			<select id="country" class="<?php echo $campaignOption->InputFieldClass ?>" name="country" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_country_option']) ?>>
				<option>Select</option>
			</select>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_state_option'] != 'hide'): ?>
		<p>
			<label>State</label>
			<select id="state"  class="<?php echo $campaignOption->InputFieldClass ?>" name="state" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_state_option']) ?>>
				<option>Select</option>
			</select>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_postcode_option'] != 'hide'): ?>
		<p>
			<label>Post Code</label>
			<input name="post_code" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_postcode_option']) ?>/>
		</p>
		<?php endif; ?>

		<?php if($pronto_donation_user_info['user_suburb_option'] != 'hide'): ?>
		<p>
			<label>Suburb</label>
			<input name="suburb" class="<?php echo $campaignOption->InputFieldClass ?>" type="text" <?php $this->class->pronto_donation_is_required($pronto_donation_user_info['user_suburb_option']) ?>/>
		</p>
		<?php endif; ?>

	</fieldset>


	<!-- Payment Method -->
	<fieldset>
		<legend><h3>Payment</h3></legend>
		
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
	</fieldset>
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('donation') ?>" />
	<input type="hidden" name="donation_campaign" value="<?php echo $attrs['campaign'] ?>" />
	<input type="hidden" name="action" value="process_donate"/>
	
	<div class="g-recaptcha" data-sitekey="6LcSLSYTAAAAABtwqrE7X6SC8pmWuuygKXaQ2MlS"></div>

	<br>

	<p class="submit">
		<button class="button button-primary <?php echo $campaignOption->ButtonClass ?>"> <?php echo ($campaignOption->EditButtonCaption) ? $campaignOption->EditButtonCaption : 'Donate' ?> </button>
	</p>

</form>