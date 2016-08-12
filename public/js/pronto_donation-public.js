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






jQuery(function(){
	function ShowEwayCardDetails(){
		if(jQuery('#pronto-donation-type-recurring').is(':checked')) { 
			if(jQuery('#payment0').is(':checked')) { 
				jQuery('#eway_card_datails').show();
				jQuery("#eway_card_number").attr('required', true);
				jQuery("#eway_name_on_card").attr('required', true);
				jQuery("#eway_expiry_month").attr('required', true);
				jQuery("#eway_expiry_year").attr('required', true);
				jQuery("#eway_ccv").attr('required', true); 
			}else{				
				jQuery('#eway_card_datails').hide();
				jQuery("#eway_card_number").attr('required', false);
				jQuery("#eway_name_on_card").attr('required', false);
				jQuery("#eway_expiry_month").attr('required', false);
				jQuery("#eway_expiry_year").attr('required', false);
				jQuery("#eway_ccv").attr('required', false);
			}
		}


	}
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//
	if(jQuery('#donation_type').length&&jQuery('#donation_type').val()=='recurring')
	{
		if(jQuery('#eway_card_datails').length )
		{
			jQuery('#payment0').click(function(){
				jQuery('#eway_card_datails').show();
				jQuery("#eway_card_number").attr('required', true);
				jQuery("#eway_name_on_card").attr('required', true);
				jQuery("#eway_expiry_month").attr('required', true);
				jQuery("#eway_expiry_year").attr('required', true);
				jQuery("#eway_ccv").attr('required', true);
			})
			jQuery('#payment1').click(function(){
				jQuery('#eway_card_datails').hide();
				jQuery("#eway_card_number").attr('required', false);
				jQuery("#eway_name_on_card").attr('required', false);
				jQuery("#eway_expiry_month").attr('required', false);
				jQuery("#eway_expiry_year").attr('required', false);
				jQuery("#eway_ccv").attr('required', false);
			})
		}
	}
	if(jQuery('#donation_type').length&&jQuery('#donation_type').val()=='both')
	{
		if(jQuery('#eway_card_datails').length )
		{
			jQuery("#eway_card_number").attr('required', false);
			jQuery("#eway_name_on_card").attr('required', false);
			jQuery("#eway_expiry_month").attr('required', false);
			jQuery("#eway_expiry_year").attr('required', false);
			jQuery("#eway_ccv").attr('required', false);
			
			jQuery('#pronto-donation-type-single').click(function(){
				jQuery('#eway_card_datails').hide();
				jQuery("#eway_card_number").attr('required', false);
				jQuery("#eway_name_on_card").attr('required', false);
				jQuery("#eway_expiry_month").attr('required', false);
				jQuery("#eway_expiry_year").attr('required', false);
				jQuery("#eway_ccv").attr('required', false);
			})
			jQuery('#pronto-donation-type-recurring').click(function(){
				ShowEwayCardDetails();
			})
			jQuery('#payment0').click(function(){
				ShowEwayCardDetails();
			})
			jQuery('#payment1').click(function(){
				ShowEwayCardDetails();
			})


		}
	}
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//

	jQuery('.pd-level-amount').on('change', function(){
		if(jQuery(this).val() == '0')
		{
			jQuery('#pd_custom_amount').removeAttr('disabled', 'disabled');
		}
		else
		{
			jQuery('#pd_custom_amount').val('').attr('disabled', 'disabled');
		}
	});
	
	jQuery('#donorType').on('change', function(){
		var companyName = jQuery('#companyName');
		if(jQuery(this).val() == 'I')
		{
			companyName.attr('disabled', 'disabled');
		}else if(jQuery(this).val() == 'B'){
			companyName.removeAttr('disabled');
		}else{
			companyName.attr('disabled', 'disabled');
		}
	});

	jQuery('#donation_gift').on('change', function(){
		var self = this;
		var message = jQuery('#gift_message');
		if(jQuery(self).is(':checked')){
			message.show();
		}else{
			message.hide();
		}
	})

});
