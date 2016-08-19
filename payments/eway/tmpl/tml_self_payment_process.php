<?php 



?>
<div class="wrap">
	<div class="payment-details pronto-donation-group clearfix">
		<div class="pd-col s12">
			<h4>eWay Card Details</h4>
		</div>
		<div class="pd-col s6">
			<div>				
			</div>
			<div>				
			</div>
		</div>
	</div>

	<div class="credit-card-detals pronto-donation-group clearfix">
		<div class="pd-col s5">
			<div class="clearfix pronto-donation-group">
				<label for="eway_card_number">Card Number</label>
				<input type="text" id="eway_card_number" name="eway_card_number" maxlength="19" required/>
			</div>
			<div class="clearfix pronto-donation-group">
				<label for="eway_name_on_card">Name on Card</label>
				<input type="text" id="eway_name_on_card" name="eway_name_on_card" maxlength="50" required/>
			</div>
		</div>
		<div class="pd-col s6" style="padding-left: 10px;">
			<div class="clearfix pronto-donation-group">
				<label for="eway_expiry_month">Expiry Date</label>
				<div class="pronto-donation-group">
					<div class="pd-col s5">
						<select id="eway_expiry_month" name="eway_expiry_month" required>
							<option disabled selected>MM</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
					</div>

					<div class="pd-col s6" style="padding-left: 10px;">
						<select id="eway_expiry_year" name="eway_expiry_year" required>
							<option disabled selected>YYYY</option>
							<?php

							$i = date("Y");
							$j = $i+11;
							for ($i; $i <= $j; $i++) {
								?>
								<option value="<?php echo $i ?>"><?php echo $i ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="clearfix pronto-donation-group">
				<label for="eway_ccv">CCV</label>
				<input id="eway_ccv" name="eway_ccv" placeholder="" type="text" required>				
			</div>
		</div>
	</div>
</div>