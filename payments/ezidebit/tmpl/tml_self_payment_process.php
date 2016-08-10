<?php 



?>
<div class="wrap">
	<div class="payment-details pd-container-padding clearfix">
		<div class="pd-col s6">

			<div>								
				<label for="paymentReference">Bill/Invoice Reference :</label>
				<label>D1233</label>
				<input type="hidden" id="paymentReference" value="D1233"/>
			</div>
			<div>
				<label for="amount">Amount</label>
				<input type="number" id="amount" value=""/>	
			</div>
		</div>
	</div>

	<div class="credit-card-detals pd-container-padding clearfix">
		<div class="pd-col s6">

			<div>
				<label for="cardNumber">Card Number</label>
				<input type="text" id="cardNumber" name="cardNumber" maxlength="19"/>
			</div>
			<div>
				<label for="nameOnCard">Name on Card</label>
				<input type="text" id="nameOnCard" name="nameOnCard" maxlength="50"/>
			</div>
		</div>

		<div class="pd-col s6">
			<label for="expiryMonth">Expiry Date</label>
			<div class="pd-container-padding">
				<div class="pd-col s6">
					<select id="expiryMonth" name="expiryMonth">
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

				<div class="pd-col s6">
					<select id="expiryYear" name="expiryYear">
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

		<div class="pd-col s6">
			<label for="ccv">CCV</label>
			<div>
				<div>
					<input id="ccv" name="ccv" placeholder="" type="text" value="">
				</div>
			</div>
		</div>

	</div>
</div>