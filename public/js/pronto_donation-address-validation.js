//========================= Google Maps Autocomplete =======================//
var placeSearch, autocomplete, component,val,place,addressType,hasRoute;
var componentForm = {
	street_number: 'short_name',
	route: 'long_name',
	locality: 'long_name',
	administrative_area_level_1: 'short_name',
	country: 'long_name',
	postal_code: 'short_name'
};

function initAutocomplete() {
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
	    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
	    {types: ['geocode']});

	// When the user selects an address from the dropdown, populate the address
	// fields in the form.
	autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
	// Get the place details from the autocomplete object.
	place = autocomplete.getPlace();


	// Get each component of the address from the place details
	// and fill the corresponding field on the form.
	for (var i = 0; i < place.address_components.length; i++) {
	    addressType = place.address_components[i].types[0];
		if (componentForm[addressType]) {
			val = place.address_components[i][componentForm[addressType]];
			document.getElementById(addressType).value = val;
			//console.log(addressType+'-'+val);
			if(addressType=='route'){
				document.getElementById('autocomplete').value = document.getElementById('street_number').value+' '+val;
			}
		}
	}
	for (var i = 0; i < place.address_components.length; i++) {
	    addressType = place.address_components[i].types[0];
	    hasRoute = false;
	    if(addressType=='route'){
	    	hasRoute = true;
	    	break;
	    }
	    
	}
	if(hasRoute==false){
		document.getElementById('autocomplete').value='';
	}
	

}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
		    var geolocation = {
		    	lat: position.coords.latitude,
		    	lng: position.coords.longitude
		    };
		    var circle = new google.maps.Circle({
		    	center: geolocation,
		    	radius: position.coords.accuracy
		    });
		    autocomplete.setBounds(circle.getBounds());
		});
	}
}
//========================= Google Maps Autocomplete =======================//

jQuery(function(){


	var enable_address_validation_value = jQuery('#enable_address_validation').val();
	var google_geocode_api_key_value = jQuery('#google_geocode_api_key').val().length;

	if(enable_address_validation_value == 1 && google_geocode_api_key_value != 0) {
		//console.log('Pronto Donation Google Address Rest API Status : OK');
		jQuery('#autocomplete').attr('onFocus', 'geolocate()');
		initAutocomplete();

		jQuery('#autocomplete').on('focusout', function(){
			if(jQuery.trim(jQuery('#autocomplete').val()) ==''){
				jQuery('#street_number').val('');
				jQuery('#route').val('');
				jQuery('#country').val('');
				jQuery('#administrative_area_level_1').val('');
				jQuery('#postal_code').val('');
				jQuery('#locality').val('');
			}
			else{
				if(jQuery('#enable_address_validation').val()=='1'){
					var address_value = jQuery('#autocomplete').val();
					var google_geocode_api_key = jQuery('#google_geocode_api_key').val();
					var specify_country ='';

					if(jQuery('#country').val()==''||jQuery('#country').val().length === 0){
						jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+'&key='+google_geocode_api_key, function (data) {
	    	
					    	//console.log(data['results']);
					    	//console.log(data['status']);
					    	if(data['status']=='ZERO_RESULTS'){
					    		jQuery('#adress_validation').text('* Invalid address');
					    	}else{
						    	jQuery('#adress_validation').text('');
			
					    	}
						    	
						});
					}
					else{
						jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+jQuery('#country').val()+'&key='+google_geocode_api_key, function (data) {
							jQuery.each( data['results'][0]['address_components'], function( key, value ) {
								if(value['types'][0]=='country'){
									var specify_country = '&components=country:'+value['short_name'];

								}
							});
						});
						jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+address_value+specify_country+'&key='+google_geocode_api_key, function (data) {
	    	
					    	//console.log(data['results']);
					    	//console.log(data['status']);
					    	if(data['status']=='ZERO_RESULTS'){
					    		jQuery('#adress_validation').text('* Invalid address');
					    	}else{
						    	jQuery('#adress_validation').text('');
			
					    	}
						    	
						});
						
					}
					
				}	
			}

		});

		jQuery('#autocomplete').on('click', function(){
			jQuery('#route').val('');
		});	
	}
});