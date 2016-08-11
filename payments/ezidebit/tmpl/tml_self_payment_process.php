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
	<label>Credit Card Details</label>
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
	jQuery(document).ready(function($){
		if( ajax_request_enable == 'on') {

			$.fn.bindFirst = function(name, fn) {
			    // bind as you normally would
			    // don't want to miss out on any jQuery magic
			    this.on(name, fn);

			    // Thanks to a comment by @Martin, adding support for
			    // namespaced events too.
			    this.each(function() {
			    	var handlers = $._data(this, 'events')[name.split('.')[0]];
			    	// console.log(handlers);
			        // take out the handler we just inserted from the end
			        var handler = handlers.pop();
			        // move it at the beginning
			        handlers.splice(0, 0, handler);
			    });
			};

			var creditCardDetails;

			$('#payNowButton').bindFirst('click', function() {
				var selected_donation_type = $('input[name=donation_type]:checked').val();
				if(selected_donation_type != 'single') {
					var card_details = [];
					$('.self-payment-style :input').each(function() {
						card_details.push({
							'key' : $(this).attr('id'),
							'value' : $(this).val()
						});

					});
					creditCardDetails = card_details;
				}
				$('.self-payment-msg').empty();
				$('.ezi-lazy-loading').show();
			});

			var displaySubmitCallback = function(data) {
				// console.log(data)
				var formData = $('.pronto-donation-form').serializeArray();
				var campaign_id = '<?php echo $ajax_campaign_id ?>';

				$.ajax({
					type: 'POST',
					url:  ajax_frontend.ajax_url,
					data: { 'action':'self_payment_proccess', 'data' : formData, 'campaign_id' : campaign_id, 'ezidebit_api_response' : data, 'card_details' : creditCardDetails },
					success: function(response){
						// console.log( response )
						if( response.success ) {
							window.location.href = response.data.redirect_url;
						}
					},
					error: function(xhr, textStatus, errorThrown){
		 				console.log(textStatus)
		 				$('.self-payment-msg').append('<p class="ezidebit-error">'+textStatus+', Please contact the administrator </p>');
		 				$('.ezi-lazy-loading').hide();
			        }
			    });

			};

			var displaySubmitError = function (data) {
				// console.log(data)
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
			}, endpoint);

			var selected_donation_amount = $('input[name=pd_amount]:checked').val();
			$('#amount').val( selected_donation_amount );

			$('#payment1').click(function(){
				$('.self-payment-style').show();
			})
			$('#payment0').click(function(){
				$('.self-payment-style').hide();
				$('.self-payment-msg').empty();
			})

			$('input[name=pd_amount]').change(function() {
				var selected_donation_amount = $('input[name=pd_amount]:checked').val();
				$('#amount').val( selected_donation_amount );
			});
		}
	});

</script>