(function( $ ) {
	'use strict';

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


})( jQuery );


jQuery(function(){
	populateCountries("country", "state");
	jQuery('.pd_amount').on('change', function(){
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
			companyName.hide();
		}else{
			companyName.show();
		}
	});




	//===================== Address Validation ======================================//
	if(jQuery('#enable_address_validation').val()=='1'){
		var google_geocode_api_key = jQuery('#google_geocode_api_key').val();
		jQuery('#address').on('focusout', function(){
			var address_value = jQuery('#address').val();
			if(jQuery.trim(jQuery('#address').val()) ==''){
				jQuery('#adress_validation').text('');
			}
			else{	
				jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+'&key='+google_geocode_api_key, function (data) {
			    	console.log(data['results']);
			    	console.log(data['status']);
			    	if(data['status']=='ZERO_RESULTS'){
			    		jQuery('#adress_validation').text('Invalid');
			    	}else{
			    		jQuery('#adress_validation').text('Valid');
			    	}
			    	
			    });
			}

		});
		jQuery('#country').on('focusout', function(){
			var address_value = jQuery('#country').val();
			if(jQuery.trim(jQuery('#country').val()) =='-1'){
				jQuery('#country_validation').text('');
			}
			else{	
				jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+'&key='+google_geocode_api_key, function (data) {
			    	console.log(data['results']);
			    	console.log(data['status']);
			    	if(data['status']=='ZERO_RESULTS'){
			    		jQuery('#country_validation').text('Invalid');
			    	}else{
			    		jQuery('#country_validation').text('Valid');
			    	}
			    	
			    });
			}

		});
		jQuery('#country').on('click', function(){
			jQuery('#state_validation').text('');
		});
		jQuery('#state').on('focusout', function(){
			var address_value = jQuery('#state').val();
			if(jQuery.trim(jQuery('#state').val()) ==''){
				jQuery('#state_validation').text('');
			}
			else{	
				jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+'&key='+google_geocode_api_key, function (data) {
			    	console.log(data['results']);
			    	console.log(data['status']);
			    	if(data['status']=='ZERO_RESULTS'){
			    		jQuery('#state_validation').text('Invalid');
			    	}else{
			    		jQuery('#state_validation').text('Valid');
			    	}
			    	
			    });
			}

		});
		jQuery('#suburb').on('focusout', function(){
			var address_value = jQuery('#suburb').val();
			if(jQuery.trim(jQuery('#suburb').val()) ==''){
				jQuery('#suburb_validation').text('');
			}
			else{	
				jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+'&key='+google_geocode_api_key, function (data) {
			    	console.log(data['results']);
			    	console.log(data['status']);

					if(data['status']=='ZERO_RESULTS'){
			    		jQuery('#suburb_validation').text('Invalid');
			    	}else{
			    		jQuery('#suburb_validation').text('Valid');
			    		jQuery.each( data['results'][0]['address_components'], function( key, value ) {
				    		if(value['types'][0]=='country'){
				    			jQuery('#country').val(value['long_name']);
				    		}
					  	
						});
			    	}

			    });
			}
		});
	}
	//===================== Address Validation ======================================//
// jQuery.getJSON('https://raw.githubusercontent.com/David-Haim/CountriesToCitiesJSON/master/countriesToCities.json', function (data) {
// 	console.log(data);
// 	});

});
