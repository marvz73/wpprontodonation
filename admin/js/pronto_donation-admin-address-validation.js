jQuery(document).ready(function($){


	if ($('#enable_address_validation').prop('checked')==true){ 
   
        //======================== Check Google Api Key ========================//
		if($('#google_geocode_api_key').val() == '' || $('#google_geocode_api_key').val() == null) {
			$('#enable_address_validation').attr('checked', false);
			$('#adress_validation_status').text('EMPTY');
			$('#adress_validation_status').css("color", "red");	
		} else {
			$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+'ozamiz'+'&key='+$('#google_geocode_api_key').val(), function (data) {
				google_geocode_request_status = data['status'];
				if(google_geocode_request_status=='REQUEST_DENIED'){
					$('#enable_address_validation').attr('checked', false);
					$('#adress_validation_status').val('INVALID');
					$('#adress_validation_status').css("color", "red");				
				}else{
					$('#adress_validation_status').val('VALID');
					$('#adress_validation_status').css("color", "green");
				}
			});
		}
		//======================== Check Google Api Key ========================//
    }

	//======================== Check Google Api Key On Input Focusout ========================//
	$('#google_geocode_api_key').on('focusout', function(){
		if($('#google_geocode_api_key').val() == '' || $('#google_geocode_api_key').val() == null) {
			$('#enable_address_validation').attr('checked', false);
			$('#adress_validation_status').val('EMPTY');
			$('#adress_validation_status').css("color", "red");	
		} else {
			$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+'ozamiz'+'&key='+$('#google_geocode_api_key').val(), function (data) {
				google_geocode_request_status = data['status'];
				if(google_geocode_request_status=='REQUEST_DENIED'){
					$('#enable_address_validation').attr('checked', false);
					$('#adress_validation_status').val('INVALID');
					$('#adress_validation_status').css("color", "red");					
				}else{
					$('#adress_validation_status').val('VALID');
					$('#adress_validation_status').css("color", "green");	
				}
			});
		}
	});
	//======================== Check Google Api Key On Input Focusout ========================//

	//======================== Check Google Api Key On Checkbox Change ========================//
	$('#enable_address_validation').on('change', function(){
		if($('#google_geocode_api_key').val() == '' || $('#google_geocode_api_key').val() == null) {
			$('#enable_address_validation').attr('checked', false);
			$('#adress_validation_status').val('EMPTY');
			$('#adress_validation_status').css("color", "red");	
		} else {
			$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+'ozamiz'+'&key='+$('#google_geocode_api_key').val(), function (data) {
				google_geocode_request_status = data['status'];
				if(google_geocode_request_status=='REQUEST_DENIED'){
					$('#enable_address_validation').attr('checked', false);
					$('#adress_validation_status').val('INVALID');
					$('#adress_validation_status').css("color", "red");					
				}else{
					$('#adress_validation_status').val('VALID');
					$('#adress_validation_status').css("color", "green");	
				}
			});
		}
	});
	//======================== Check Google Api Key On Checkbox Change ========================//


});