jQuery(function(){
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//
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

	

});