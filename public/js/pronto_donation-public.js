/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */

jQuery(document).ready(function($){

	$('.pd-level-amount').on('change', function(){
		if($(this).val() == '0')
		{
			$('#pd_custom_amount').removeAttr('disabled', 'disabled');
		}
		else
		{
			$('#pd_custom_amount').val('').attr('disabled', 'disabled');
		}
	});
	
	$('#donorType').on('change', function(){
		var companyName = $('#companyName');
		if($(this).val() == 'I')
		{
			companyName.attr('disabled', 'disabled');
		}else if($(this).val() == 'B'){
			companyName.removeAttr('disabled');
		}else{
			companyName.attr('disabled', 'disabled');
		}
	});

	$('#donation_gift').on('change', function(){
		var self = this;
		var message = $('#gift_message');
		if($(self).is(':checked')){
			message.show();
		}else{
			message.hide();
		}
	})


	$('.pronto-donation-form').attr('novalidate', '');
	$('.pronto-donation-form').submit(function(e){
 	
		$('.form-error').remove();

		var formData = $('.pronto-donation-form').serializeArray();
		for(var i = 0; i < formData.length; i++ ) {

			if( formData[i].name == 'comment' ) {
				if( $('textarea[name='+ formData[i].name +']').prop('required') == true 
					&& ( formData[i].value == null || formData[i].value == '' ) ) {

					$('textarea[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Comment is required.</small>');
					$('.ezi-lazy-loading').hide();
					$('textarea[name='+ formData[i].name +']').focus();
					return false;
				}

			} else {
				if( $('input[name='+ formData[i].name +']').prop('required') == true 
					&& ( formData[i].value == null || formData[i].value == '' ) ) {

					if( formData[i].name == 'email' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Email is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'first_name' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> First Name is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'last_name' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Last Name is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'phone' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Phone is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'address' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Address is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'country' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Country is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'post_code' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Post Code is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'suburb' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> Suburb is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					} else if( formData[i].name == 'state' ) {
						$('input[name='+ formData[i].name +']').after('<small class="form-error"> <span style="color:red;">*</span> State is required.</small>');
						$('.ezi-lazy-loading').hide();
						$('input[name='+ formData[i].name +']').focus();
						return false;
					}
				}
			}
		}
	})

})