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
	<div class="payment-details pronto-donation-group clearfix">
		<div class="pd-col s12">
			<h4>Ezidebit Card Details</h4>
		</div>
		<div class="pd-col s6">
			<div>
				<input type="hidden" id="paymentReference" value="<?php echo substr(md5(uniqid(rand(), true)), 0,24) ?>"/>
			</div>
			<div>
				<input type="hidden" id="amount" value=""/>	
			</div>
		</div>
	</div>

	<div class="credit-card-detals pronto-donation-group clearfix">
		<div class="pd-col s12">
			<div class="clearfix pronto-donation-group">
				<div class="pd-col s5">
					<label for="cardNumber">Card Number</label>
					<input type="text" id="cardNumber" name="cardNumber" maxlength="19"/>
				</div>
				<div class="pd-col s6" style="padding-left: 10px;">	
					<label for="expiryMonth">Expiry Date</label>
					<div class="pronto-donation-group">
						<div class="pd-col s5">
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

						<div class="pd-col s6" style="padding-left: 10px;">
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
			</div>
		</div>
		<div class="pd-col s12">
			<div class="clearfix pronto-donation-group">
				<div class="pd-col s5">
					<label for="nameOnCard">Name on Card</label>
					<input type="text" id="nameOnCard" name="nameOnCard" maxlength="50"/>
				</div>
				<div class="pd-col s6" style="padding-left: 10px;">
					<label for="ccv">CCV</label>
					<input id="ccv" name="ccv" placeholder="" type="text" value="">
				</div>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function($) {

		// payment process function for ezidebit 

		var your_a_robot = 0;
		var captcha_id = null;
		var captcha_response = null;

		var verifyCallback = function(response) {
			captcha_response = response;
		}

		function process_payment_ezidebit(e) {
			e.preventDefault();
			if(e.originalEvent !== undefined) {
				// console.log('rebinding')
				$('#payNowButton').removeAttr('onclick');
				$('.ezi-lazy-loading').show();
				$('.self-payment-msg').empty();
				$('.ezidebit-error').remove();

				var card_details = [];
				var cptcha_response = '';

				var formData = $('.pronto-donation-form').serializeArray();

				for(var i = 0; i < formData.length; i++ ) {

					if( formData[i].name == 'comment' ) {
						if( $('textarea[name='+ formData[i].name +']').prop('required') == true 
							&& ( formData[i].value == null || formData[i].value == '' ) ) {

							$('textarea[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Comment is required.</p>');
							$('.ezi-lazy-loading').hide();
							$('textarea[name='+ formData[i].name +']').focus();
							return;
						}

					} else {
						if( $('input[name='+ formData[i].name +']').prop('required') == true 
							&& ( formData[i].value == null || formData[i].value == '' ) ) {

							if( formData[i].name == 'email' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Email is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'first_name' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> First Name is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'last_name' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Last Name is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'phone' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Phone is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'address' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Address is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'country' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Country is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'post_code' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Post Code is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'suburb' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> Suburb is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							} else if( formData[i].name == 'state' ) {
								$('input[name='+ formData[i].name +']').after('<p class="ezidebit-error"> State is required.</p>');
								$('.ezi-lazy-loading').hide();
								$('input[name='+ formData[i].name +']').focus();
								return;
							}
						}
					}
 				}


				$('.self-payment-style :input').each(function() {
					card_details.push({
						'key' : $(this).attr('id'),
						'value' : $(this).val()
					});
				});
				cptcha_response = captcha_response;

			 	// verify_captcha
				$.ajax({
					type: 'POST',
					url:  ajax_frontend.ajax_url,
					data: { 'action':'verify_captcha', 'cptcha_response' : cptcha_response },
					success: function(response) {
						//console.log( response )
						$('.self-payment-msg').empty();

						if( response.success === false && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('#payNowButton').removeAttr('disabled');
							$('.ezi-lazy-loading').hide();
							your_a_robot++;
							grecaptcha.reset(captcha_id);
							cptcha_response = '';
						} else if( response.data.success == false && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('#payNowButton').removeAttr('disabled');
							$('.ezi-lazy-loading').hide();
							your_a_robot++;
							grecaptcha.reset(captcha_id);
							cptcha_response = '';

						} else if( response.success == true || captcha_enable == 0 ) {
							// console.log('executed')
							// this function will be the success callback for ezidebit client side
							your_a_robot = 0;
							var displaySubmitCallback = function(data) {
								// console.log('EZI success', data)
								setTimeout(function(){
									if(your_a_robot == 0) {

										var campaign_id = '<?php echo $ajax_campaign_id ?>';
										var selected_donation_type = $('input[name=donation_type]:checked').val();
										
										// this ajax request will be the captcha validation
										$.ajax({
											type: 'POST',
											url:  ajax_frontend.ajax_url,
											data: { 'action':'self_payment_proccess', 'data' : formData, 'campaign_id' : campaign_id, 'ezidebit_api_response' : data },
											success: function(response){
												// console.log( response )

												if( response.success ) {
													window.location.href = response.data.redirect_url;
												}
												your_a_robot = 0;
											},
											error: function(xhr, textStatus, errorThrown) {
												// console.log('process error', textStatus)

												$('.self-payment-msg').append('<p class="ezidebit-error"> Something went wrong, Please try again </p>');
												$('.ezi-lazy-loading').hide();
											}
										});
									}
								}, 3000);
							};

							// this function will be the error callback for ezidebit client side
						 	var displaySubmitError = function (data) {
							//	console.log("ezi error", data)
								setTimeout(function(){
									if(your_a_robot == 0) {
										if(data == 'An error has occurred attempting to contact the API. Please contact Ezidebit support.') {
											$('.self-payment-msg').append('<p class="ezidebit-error">Something went wrong, Please try again</p>');
										}
										$('.self-payment-msg').append('<p class="ezidebit-error">'+data+'</p>');
										$('.ezi-lazy-loading').hide();
								 		grecaptcha.reset(captcha_id);
								 		cptcha_response = '';
									}
								}, 3000);
							};

							// this is the initialization of the ezidebit client side library
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

							// this will trigger the ezidebit self payment process.. via jquery programmatically
	 						$('#payNowButton').trigger( "click" );
						}
					},
					error: function(xhr, textStatus, errorThrown) {
		 				// console.log("captcha error", textStatus)
		 				
		 				$('.self-payment-msg').append('<p class="ezidebit-error">Something went wrong, Please try again</p>');
		 				$('.ezi-lazy-loading').hide();
			        }
			    });
			}
		}

		// the payment change event handler jquery
		$('input[name=payment]').change(
			function() {
				// when user select ezidebit
				if( $(this).val() == 'Ezidebit' ) {
					if( ajax_request_enable == 'on' ) {
						$('#payNowButton').bind('click', process_payment_ezidebit);

						$('.self-payment-style').show();
						$('.g-recaptcha').hide();

						if(captcha_enable == 1) {
							var captchaWidgetId = grecaptcha.render( 'client-side-recaptcha', {
								  'sitekey' : captchakey,  // required
								  'callback' : verifyCallback,
								  'theme' : 'light'
							});
							captcha_id = captchaWidgetId;
						}

					 	var selected_donation_amount = $('input[name=pd_amount]:checked').val();
						$('#amount').val( selected_donation_amount );

						$('input[name=pd_amount]').change(function() {
							var selected_donation_amount = $('input[name=pd_amount]:checked').val();
							$('#amount').val( selected_donation_amount );
						});
					}

	 			} else if( $(this).val() == 'eWay' ) { // when user select eway

	 				$('.self-payment-style').hide();
	 				$('.self-payment-msg').empty();
	 				$('.g-recaptcha').show();
	 				$('.ezidebit-error').remove();

	 				$('#payNowButton').removeAttr('disabled');
	 				$('#payNowButton').unbind('click', process_payment_ezidebit);
	 			}
			}
		)
 		
		setTimeout(function(){
			if( $('input[name=payment]').length == 1 ) {
				$('input[name=payment]').trigger('change');
			}
		}, 2000);


	});

</script>