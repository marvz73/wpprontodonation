//========================= Google Maps Autocomplete =======================//
var placeSearch, autocomplete;
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
var place = autocomplete.getPlace();

for (var component in componentForm) {
  document.getElementById(component).value = '';
  document.getElementById(component).disabled = false;
}

// Get each component of the address from the place details
// and fill the corresponding field on the form.
for (var i = 0; i < place.address_components.length; i++) {
	  var addressType = place.address_components[i].types[0];
	  if (componentForm[addressType]) {
	    var val = place.address_components[i][componentForm[addressType]];
	    document.getElementById(addressType).value = val;
	    console.log(val);
	  }
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
	initAutocomplete();
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

});
