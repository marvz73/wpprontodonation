<?php 
 


?>

<style type="text/css">
	.ezi-lazy-loading{
		display: none; 
		position: absolute;
		left: 0;
		right: 0;
		width: 263px;
		height: 100px;
		margin: 0 auto;
		text-align: center;
	}
	.ezi-lazy-loading p {
		font-size: 13px;
		color: #de5b5b;
	}
	.ezidebit-error {
		font-size: 13px;
		color: #de5b5b;
		font-weight: bold;
		margin-top: 10px;
	}
	.self-payment-style {
		display: none;
	}
</style>

<div class="ezi-lazy-loading">
	
	<img src="<?php echo plugins_url( '../inc/default.gif', __FILE__ ) ?>" alt="payment processing...">
	<p>Processing Payment...</p>
</div>

<div class="wrap">
	<div class="payment-details pd-container-padding clearfix">
		<div class="pd-col s6">
			<h4>Ezidebit Card Details</h4>
		</div>
	</div>
	<div class="payment-details pd-container-padding clearfix">
		<div class="pd-col s6">
			<div>
				<input type="hidden" id="paymentReference" value="<?php echo substr(md5(uniqid(rand(), true)), 0,24) ?>"/>
			</div>
			<div>
				<input type="hidden" id="amount" value=""/>	
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

<script type="text/javascript">

	jQuery(document).ready(function($) {

		function process_payment_ezidebit(e) {
			e.preventDefault();
			if(e.originalEvent !== undefined) {
				// console.log('rebinding')
				$('.ezi-lazy-loading').show();
				$('.self-payment-msg').empty();

				var card_details = [];
				var cptcha_response = '';

				$('.self-payment-style :input').each(function() {
					card_details.push({
						'key' : $(this).attr('id'),
						'value' : $(this).val()
					});

				});

				for(var i = 0; i < card_details.length; i++ ) {
					if(card_details[i].key == 'g-recaptcha-response-1') {
						cptcha_response = card_details[i].value;
					}
				}

			 	// verify_captcha
				$.ajax({
					type: 'POST',
					url:  ajax_frontend.ajax_url,
					data: { 'action':'verify_captcha', 'cptcha_response' : cptcha_response },
					success: function(response) {
						// console.log( response )

						if( response.success === false && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('#payNowButton').removeAttr('disabled');
							$('.ezi-lazy-loading').hide();

						} else if( response.data.success != true && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('#payNowButton').removeAttr('disabled');
							$('.ezi-lazy-loading').hide();

						} else if( response.success == true || captcha_enable == 0 ) {
							// console.log('captcha valid')

							var displaySubmitCallback = function(data) {
								// console.log('EZI success', data)

								var formData = $('.pronto-donation-form').serializeArray();
								var campaign_id = '<?php echo $ajax_campaign_id ?>';
								var selected_donation_type = $('input[name=donation_type]:checked').val();
								
								$.ajax({
									type: 'POST',
									url:  ajax_frontend.ajax_url,
									data: { 'action':'self_payment_proccess', 'data' : formData, 'campaign_id' : campaign_id, 'ezidebit_api_response' : data },
									success: function(response){
										// console.log( response )

										if( response.success ) {
											window.location.href = response.data.redirect_url;
										}
									},
									error: function(xhr, textStatus, errorThrown) {
										// console.log('process error', textStatus)

										$('.self-payment-msg').append('<p class="ezidebit-error">'+textStatus+', Please contact the administrator </p>');
										$('.ezi-lazy-loading').hide();
									}
								});
							};

						 	var displaySubmitError = function (data) {
								// console.log("ezi error", data)

								$('.self-payment-msg').append('<p class="ezidebit-error">'+data+'</p>');
								$('.ezi-lazy-loading').hide();
							};

							eziDebit.init(publicKey, {
								submitAction: "ChargeCard",
								submitButton: "payNowButton",
								submitCallback: displaySubmitCallback,
								submitError: displaySubmitError,
								nameOnCard: "nameOnCard",
								cardNumber: "cardNumber",
								cardExpiryMonth: "expiryMonth",
								cardExpiryYear: "expiryYear",
								cardCCV: "ccv",
								paymentAmount: "amount",
								paymentReference: "paymentReference"
							}, endpoint)

	 						$('#payNowButton').trigger( "click" );
							// end of ezidebit client side payment
						}
					},
					error: function(xhr, textStatus, errorThrown) {
		 				// console.log("captcha error", textStatus)

		 				$('.self-payment-msg').append('<p class="ezidebit-error">'+textStatus+', Please contact the administrator </p>');
		 				$('.ezi-lazy-loading').hide();
			        }
			    });
			}
		}


		$('input[name=payment]').change(
			function() {
				if( $(this).val() == 'Ezidebit' ) {
					if( ajax_request_enable == 'on' ) {

						$('#payNowButton').bind('click', process_payment_ezidebit);

						$('.self-payment-style').show();
						$('.g-recaptcha').hide();

						if(captcha_enable == 1) {
							var captchaWidgetId = grecaptcha.render( 'client-side-recaptcha', {
								  'sitekey' : captchakey,  // required
								  'theme' : 'light'
							});
						}

					 	var selected_donation_amount = $('input[name=pd_amount]:checked').val();
						$('#amount').val( selected_donation_amount );

						$('input[name=pd_amount]').change(function() {
							var selected_donation_amount = $('input[name=pd_amount]:checked').val();
							$('#amount').val( selected_donation_amount );
						});
					}
	 			} else if( $(this).val() == 'eWay' ) {

	 				$('.self-payment-style').hide();
	 				$('.self-payment-msg').empty();
	 				$('.g-recaptcha').show();	

	 				$('#payNowButton').removeAttr('disabled');
	 				$('#payNowButton').unbind('click', process_payment_ezidebit);
	 			}
			}
		)

	});

</script>