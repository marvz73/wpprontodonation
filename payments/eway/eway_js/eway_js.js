jQuery(function(){
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//
	function ShowEwayCardDetails(){
		
		if(jQuery('#payment0').is(':checked')) { 
			jQuery('#eway_card_datails').show();
			jQuery("#eway_card_number").attr('required', false);
			jQuery("#eway_name_on_card").attr('required', false);
			jQuery("#eway_expiry_month").attr('required', false);
			jQuery("#eway_expiry_year").attr('required', false);
			jQuery("#eway_ccv").attr('required', false); 
		}else{				
			jQuery('#eway_card_datails').hide();
			jQuery("#eway_card_number").attr('required', false);
			jQuery("#eway_name_on_card").attr('required', false);
			jQuery("#eway_expiry_month").attr('required', false);
			jQuery("#eway_expiry_year").attr('required', false);
			jQuery("#eway_ccv").attr('required', false);
		}
	


	}


	if(jQuery('#eway_card_datails').length ){

		ShowEwayCardDetails();
		jQuery('#payment0').click(function(){
			ShowEwayCardDetails();
		})
		jQuery('#payment1').click(function(){
			ShowEwayCardDetails();
		})


		jQuery('.pronto-donation-form').submit(function(e){
			//e.preventDefault();
			if (jQuery('#eway_card_number').val()==null || jQuery('#eway_card_number').val()=='') {
				jQuery('#eway_card_details_error').removeAttr( "hidden" );
				jQuery('#eway_card_number').focus();
			    return false;
			}if (jQuery('#eway_expiry_month').val()==null || jQuery('#eway_expiry_month').val()=='' || jQuery('#eway_expiry_month').val()=='MM') {
				jQuery('#eway_card_details_error').removeAttr( "hidden" );	
				jQuery('#eway_expiry_month').focus();
			    return false;
			}if (jQuery('#eway_expiry_year').val()==null || jQuery('#eway_expiry_year').val()=='' || jQuery('#eway_expiry_year').val()=='YYYY') {
				jQuery('#eway_card_details_error').removeAttr( "hidden" );
				jQuery('#eway_expiry_year').focus();
			    return false;
			}if (jQuery('#eway_name_on_card').val()==null || jQuery('#eway_name_on_card').val()=='') {
				jQuery('#eway_card_details_error').removeAttr( "hidden" );	
				jQuery('#eway_name_on_card').focus();
			    return false;
			}if (jQuery('#eway_ccv').val()==null || jQuery('#eway_ccv').val()=='') {
				jQuery('#eway_card_details_error').removeAttr( "hidden" );
				jQuery('#eway_ccv').focus();
			    return false;
			}

		})
		


	}

	
	//------------------------- Eway Monthly/Recurring Payment --------------------------------//

	

});