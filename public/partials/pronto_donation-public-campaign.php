
<form>

	<fieldset>
		<legend><h3>Donation Information</h3></legend>


		<?php if($this->class->has_payment_amount_level($attrs['campaign'])): ?>
			<p>
				<label>Donation Amount: </label>
				<?php
					foreach(explode(',', $pronto_donation_campaign['amount_level']) as $index=>$amount_level):
				?>
						<label for="pd_amount<?php echo $index ?>"><input id="pd_amount<?php echo $index ?>" class="pd_amount" type="radio" name="pd_amount" value="<?php echo $amount_level ?>" <?php echo ($index==0) ? 'checked="true"' : ''; ?> /><?php echo $amount_level ?></label>
				<?php
						endforeach;
					
				?>

				<label for="other_amount"><input id="other_amount" class="pd_amount" type="radio" name="pd_amount" value="0" />Other</label>
				
			</p>
		<?php endif; ?>

		<p>
			<label>Donation Custom Amount: </label>
			<?php if($this->class->has_payment_amount_level($attrs['campaign'])): ?>
				<input disabled="" type="number" id="pd_custom_amount" name="pd_custom_amount" />
			<?php else: ?>
				<input type="number" id="pd_custom_amount" name="pd_custom_amount" />
			<?php endif; ?>
		</p>

		<p>
			<label>Donation Type :</label>
			<span>
				<label>
					<input type="radio" name="donation_type" checked="true" /> Single
				</label>
				<label>
					<input type="radio" name="donation_type" /> Recurring
				</label>
			</span>
		</p>

	</fieldset>


	<fieldset>

		<legend><h3>Donor Information</h3></legend>

		<p>
			<label>Donor Type</label>
			<select>
				<option value="personal">Personal</option>
				<option value="business">Business</option>
			</select>
		</p>
		<p>
			<label>Email</label>
			<input type="email" />
		</p>
		<p>
			<label>First Name</label>
			<input type="text" />
		</p>
		<p>
			<label>Last Name</label>
			<input type="text" />
		</p>
		<p>
			<label>Phone</label>
			<input type="text" />
		</p>
		<p>
			<label>Address</label>
			<input type="text" />
		</p>
		<p>
			<label>State</label>
			<input type="text" />
		</p>
		<p>
			<label>Post Code</label>
			<input type="text" />
		</p>
		<p>
			<label>Suburb</label>
			<input type="text" />
		</p>
	</fieldset>


	<fieldset>
		<legend><h3>Payment</h3></legend>
		
			<?php
				foreach($payment_methods as $index=>$payment):
			?>
			<div>
				<label>
					<input type="radio" name="payment" value="<?php echo $payment->get_payment_name() ?>" /> <img src="<?php echo $payment->get_payment_logo() ?>" width="20%" alt="<?php echo $payment->get_payment_name() ?>" />
				</label>
			</div>
			<?php
				endforeach;
			?>

	

	</fieldset>
	<br>
	<p class="submit">
		<button class="button button-primary">Donate</button>
	</p>


</form>