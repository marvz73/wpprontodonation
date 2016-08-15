jQuery(function(){
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//
	function ShowEwayCardDetails(){
		
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

	if(jQuery('#eway_card_datails').length )
	{

		ShowEwayCardDetails();
		jQuery('#payment0').click(function(){
			ShowEwayCardDetails();
		})
		jQuery('#payment1').click(function(){
			ShowEwayCardDetails();
		})


	}
	
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//

	

});