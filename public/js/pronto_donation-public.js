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
	})
	
	jQuery('#donorType').on('change', function(){
		var companyName = jQuery('#companyName');
		if(jQuery(this).val() == 'I')
		{
			companyName.hide();
		}else{
			companyName.show();
		}
	})
});


