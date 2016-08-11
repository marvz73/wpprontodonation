jQuery(document).ready(function($){

	if( ajax_request_enable == 'on') {
 
		var displaySubmitCallback = function(data) {
			console.log(data)

			var formData = $('.pronto-donation-form').serializeArray();
			console.log(formData);
		};

		var displaySubmitError = function (data) {
			console.log(data)
		};

		var endpoint = "https://api.demo.ezidebit.com.au/V3-5/public-rest";
		var publicKey = "0B0927FC-0C6E-457C-0F8D-5EF50018AE20";

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


		$('#payment1').click(function(){
			$('.self-payment-style').show();
		})
		$('#payment0').click(function(){
			$('.self-payment-style').hide();
		})


	}

});