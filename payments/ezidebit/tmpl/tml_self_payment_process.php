<?php 
$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
$InputFieldClass = (empty($pronto_donation_settings['InputFieldClass'])) ? "" : $pronto_donation_settings['InputFieldClass'];
?>

<style type="text/css">

	.self-payment-style {
		display: none;
	}

	.ezidebit-error {
		font-size: 13px;
		color: #de5b5b;
		font-weight: bold;
		margin-top: 10px;
	}

	.ezi-lazy-loading{
		font-size: 11px;
		text-align: center;
		color: #de5b5b;
		margin-left: 130px;
	}

</style>

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
			<div class="pronto-donation-group clearfix">
				<div class="pd-col s6">
					<label for="cardNumber">Card Number</label>
					<input class="<?php echo $InputFieldClass; ?>" type="text" id="cardNumber" name="cardNumber" maxlength="19"/>
				</div>
				<div class="pd-col s6 padding_Input">	
					<label for="expiryMonth">Expiry Date</label>
					<div class="pronto-donation-group clearfix">
						<div class="pd-col s6">
							<select class="<?php echo $InputFieldClass; ?>" id="expiryMonth" name="expiryMonth">
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

						<div class="pd-col s6 padding_Input">
							<select class="<?php echo $InputFieldClass; ?>" id="expiryYear" name="expiryYear">
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
			<div class="pronto-donation-group clearfix">
				<div class="pd-col s6">
					<label for="nameOnCard">Name on Card</label>
					<input class="<?php echo $InputFieldClass; ?>" type="text" id="nameOnCard" name="nameOnCard" maxlength="50"/>
				</div>
				<div class="pd-col s6 padding_Input">
					<label for="ccv">CCV</label>
					<input class="<?php echo $InputFieldClass; ?>" id="ccv" name="ccv" placeholder="" type="text" value="">
				</div>
			</div>	
		</div>
	</div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function($) {

		// payment process function for ezidebit 

		function get_ezidebit_response( errcode ) {
			var responses = {
				'01' : "Refer to Card Issuer",
				'02' : "Refer to Issuer’s Special Conditions",
				'03' : "Invalid Merchant",
				'04' : "Pick Up Card",
				'05' : "Do Not Honour",
				'06' : "Error",
				'07' : "Pick Up Card, Special Conditions",
				'09' : "Request in Progress",
				'12' : "Invalid Transaction",
				'13' : "Invalid Amount",
				'14' : "Invalid Card Number",
				'15' : "No Such Issuer",
				'17' : "Customer Cancellation",
				'18' : "Customer Dispute",
				'19' : "Re-enter Transaction",
				'20' : "Invalid Response",
				'21' : "No Action Taken",
				'22' : "Suspected Malfunction",
				'23' : "Unacceptable Transaction Fee",
				'24' : "File Update not Supported by Receiver",
				'25' : "Unable to Locate Record on File",
				'26' : "Duplicate File Update Record",
				'27' : "File Update Field Edit Error",
				'28' : "File Update File Locked Out",
				'29' : "File Update not Successful",
				'30' : "Format Error",
				'31' : "Bank not Supported by Switch",
				'32' : "Completed Partially",
				'33' : "Expired Card-Pick Up",
				'34' : "Suspected Fraud-Pick Up",
				'35' : "Contact Acquirer-Pick Up",
				'36' : "Restricted Card-Pick Up",
				'37' : "Call Acquirer Security-Pick Up",
				'38' : "Allowable PIN Tries Exceeded",
				'39' : "No CREDIT Account",
				'40' : "Requested Function not Supported",
				'41' : "Lost Card-Pick Up",
				'42' : "No Universal Amount",
				'43' : "Stolen Card-Pick Up",
				'44' : "No Investment Account",
				'51' : "Insufficient Funds",
				'52' : "No Cheque Account",
				'53' : "No Savings Account",
				'54' : "Expired Card",
				'55' : "Incorrect PIN",
				'56' : "No Card Record",
				'57' : "Trans. not Permitted to Cardholder",
				'58' : "Transaction not Permitted to Terminal",
				'59' : "Suspected Fraud",
				'60' : "Card Acceptor Contact Acquirer",
				'61' : "Exceeds Withdrawal Amount Limits",
				'62' : "Restricted Card",
				'63' : "Security Violation",
				'64' : "Original Amount Incorrect",
				'65' : "Exceeds Withdrawal Frequency Limit",
				'66' : "Card Acceptor Call Acquirer Security",
				'67' : "Hard Capture-Pick Up Card at ATM",
				'68' : "Response Received Too Late",
				'75' : "Allowable PIN Tries Exceeded",
				'86' : "ATM Malfunction",
				'87' : "No Envelope Inserted",
				'88' : "Unable to Dispense",
				'89' : "Administration Error",
				'90' : "Cut-off in Progress",
				'91' : "Issuer or Switch is Inoperative",
				'92' : "Financial Institution not Found",
				'93' : "Trans Cannot be Completed",
				'94' : "Duplicate Transmission",
				'95' : "Reconcile Error",
				'96' : "System Malfunction",
				'97' : "Reconciliation Totals Reset",
				'98' : "MAC Error",
				'99' : "Reserved for National Use"
			};

			if( errcode in responses ) {
				return responses[errcode];
			} else {
				return null;
			}
		}

		var your_a_robot = 0;
		var captcha_id = null;
		var captcha_response = null;

		var verifyCallback = function(response) {
			captcha_response = response;
		}

		function process_payment_ezidebit(e) {
			e.preventDefault();
			if(e.originalEvent !== undefined) {

				var spinner_ui = '<div class="ezi-lazy-loading"><img src="<?php echo plugins_url( "../inc/default.gif", __FILE__ ) ?>" alt="payment processing..."><p>Processing Payment, Please wait...</p></div>';

				$('#payNowButton').removeAttr('onclick');
				$('.submit').append( spinner_ui );
				$('#payNowButton').hide();
				
				$('.self-payment-msg').empty();
				$('.ezidebit-error').remove();
				$('.form-error').remove();
				$('#payNowButton').attr('disabled', 'disabled');

				var card_details = [];
				var cptcha_response = '';

				var formData = $('.pronto-donation-form').serializeArray();
				// console.log(formData)

				for(var i = 0; i < formData.length; i++ ) {

					

					if( formData[i].name == 'comment' ) {
						if( $('textarea[name='+ formData[i].name +']').prop('required') == true 
							&& ( formData[i].value == null || formData[i].value == '' ) ) {

							$('textarea[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Comment is required.</small>');
							$('textarea[name='+ formData[i].name +']').focus();
							$('.ezi-lazy-loading').hide();
							$('#payNowButton').removeAttr('disabled');
							$('#payNowButton').show();
							return;
						}

					} else {
						if( $('input[name='+ formData[i].name +']').prop('required') == true 
							&& ( formData[i].value == null || formData[i].value == '' ) ) {

							if( formData[i].name == 'companyName' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Comapny Name is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'email' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Email is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'first_name' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> First Name is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'last_name' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Last Name is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'phone' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Phone is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'address' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Address is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'country' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Country is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'post_code' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Post Code is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'suburb' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Suburb is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
								return;
							} else if( formData[i].name == 'state' ) {
								$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> State is required.</small>');
								$('input[name='+ formData[i].name +']').focus();
								$('.ezi-lazy-loading').hide();
								$('#payNowButton').removeAttr('disabled');
								$('#payNowButton').show();
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
 						// console.log('a', typeof response.success)
 						// console.log('b', typeof response.data.success)
						$('.self-payment-msg').empty();

						if( typeof response.success != 'undefined' && response.success == false && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('.ezi-lazy-loading').hide();
							$('#payNowButton').removeAttr('disabled');
							$('#payNowButton').show();

							your_a_robot++;
							if(captcha_enable == 1) {
								grecaptcha.reset(captcha_id);
								cptcha_response = '';
							}

						} else if( typeof response.success != 'undefined' && response.data.success == false && captcha_enable == 1 ) {

							$('.self-payment-msg').append('<p class="ezidebit-error">You are a robot</p>');
							$('.ezi-lazy-loading').hide();
							$('#payNowButton').removeAttr('disabled');
							$('#payNowButton').show();

							your_a_robot++;
							if(captcha_enable == 1) {
								grecaptcha.reset(captcha_id);
								cptcha_response = '';
							}

						} else if( typeof response.success != 'undefined' && ( response.success == true || captcha_enable == 0 ) ) {
 
							// this function will be the success callback for ezidebit client side
							your_a_robot = 0;
							var displaySubmitCallback = function(data) {
 								// console.log('card details', card_details)
 								var success_response = ['00', '08', '10', '11', '16', '77', '000', '003'];
 								if( $.inArray( data.PaymentResultCode, success_response ) != -1 ) {
 									// console.log('hahahaah success')
									setTimeout(function(){
										if(your_a_robot == 0) {

											var campaign_id = '<?php echo $ajax_campaign_id ?>';
											var selected_donation_type = $('input[name=donation_type]:checked').val();
											
											// this ajax request will be the captcha validation
											$.ajax({
												type: 'POST',
												url:  ajax_frontend.ajax_url,
												data: { 'action':'ezi_self_payment_proccess', 'data' : formData, 'campaign_id' : campaign_id, 'ezidebit_api_response' : data, 'c_details' : card_details },
												success: function(response){

													// console.log(response)
													// console.log(data)

													var additional_url = '';

													for(var k in data) {
														additional_url += '&'+ k + '=' + data[k];
													}

													if( typeof response.success != 'undefined' && response.success ) {
														window.location.href = response.data.redirect_url + $.trim(additional_url) + '&DonationMetaID=' + response.data.donation_meta_id;
													}
													your_a_robot = 0;
												},
												error: function(xhr, textStatus, errorThrown) {

													$('.self-payment-msg').append('<p class="ezidebit-error"> Something went wrong, Please try again </p>');
													$('.ezi-lazy-loading').hide();
													$('#payNowButton').removeAttr('disabled');
													$('#payNowButton').show();
													return;
												}
											});
										}
									}, 3000);
 								} else {
 									var response_txt = get_ezidebit_response( data.PaymentResultCode );
 									if( response_txt != null ) {
 										$('.self-payment-msg').append('<p class="ezidebit-error"> '+response_txt+' </p>');
 									} else {
 										$('.self-payment-msg').append('<p class="ezidebit-error"> '+data.PaymentResultText+' </p>');
 									}
									
									$('.ezi-lazy-loading').hide();
									$('#payNowButton').removeAttr('disabled');
									$('#payNowButton').show();

									if(captcha_enable == 1) {
										grecaptcha.reset(captcha_id);
										cptcha_response = '';
									}
 									return;
 								}
							};

							// this function will be the error callback for ezidebit client side
						 	var displaySubmitError = function (data) {
 
								setTimeout(function(){
									if(your_a_robot == 0) {
										if(data == 'An error has occurred attempting to contact the API. Please contact Ezidebit support.') {
											$('.self-payment-msg').append('<p class="ezidebit-error">Something went wrong, Please try again</p>');
											console.log(data)
										} else {
											$('.self-payment-msg').append('<p class="ezidebit-error">'+data+'</p>');
										}

										$('.ezi-lazy-loading').hide();
										$('#payNowButton').removeAttr('disabled');
										$('#payNowButton').show();

										if(captcha_enable == 1) {
											grecaptcha.reset(captcha_id);
											cptcha_response = '';
										}
								 		return;
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
		 				
		 				$('.self-payment-msg').append('<p class="ezidebit-error">Something went wrong, Please try again</p>');
		 				$('.ezi-lazy-loading').hide();
		 				$('#payNowButton').removeAttr('disabled');
		 				$('#payNowButton').show();
		 				return;
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

						setTimeout(function(){

							if(captcha_enable == 1) {
								var captchaWidgetId = grecaptcha.render( 'client-side-recaptcha', {
									  'sitekey' : captchakey,  // required
									  'callback' : verifyCallback,
									  'theme' : 'light'
								});
								captcha_id = captchaWidgetId;
							}

						}, 1000);

					 	var selected_donation_amount = $('input[name=pd_amount]:checked').val();
						$('#amount').val( selected_donation_amount );

						$('input[name=pd_amount]').change(function() {
							var selected_donation_amount = $('input[name=pd_amount]:checked').val();
							$('#amount').val( selected_donation_amount );
						});

						$('#pd_custom_amount').keyup(function(){
							$('#amount').val( $(this).val() );
						})

						if( selected_donation_amount == 0 ) {
							$('#amount').val( $('#pd_custom_amount').val() );
						}
					}

	 			} else if( $(this).val() == 'eWay' ) { // when user select eway

	 				$('.self-payment-style').hide();
	 				$('.self-payment-msg').empty();
	 				$('.g-recaptcha').show();
	 				$('.ezidebit-error').remove();
	 				$('.form-error').remove();

	 				$('#payNowButton').unbind('click', process_payment_ezidebit);
	 				$('#payNowButton').removeAttr('onclick');
	 				$('.self-payment-msg').empty();
	 				$('#payNowButton').removeAttr('disabled');
		 			$('#payNowButton').show();
	 			}
			}
		)

		$('.pronto-donation-form').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
		  }
		});
 		
		setTimeout(function() {
			if( $('input[name=payment]').length == 1 ) {
				$('input[name=payment]').trigger('change');
			}
		}, 2000);


	});

</script>