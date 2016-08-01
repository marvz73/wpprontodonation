jQuery(document).ready(function($){
	//======================== Check Google Api Key ========================//
	if($('#google_geocode_api_key').val() == '' || $('#google_geocode_api_key').val() == null) {
		$('#enable_address_validation').attr('checked', false);
		$('#adress_validation_status_invalid').text('EMPTY');
		$('#adress_validation_status_valid').text(''); 
	} else {
		$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+'ozamiz'+'&key='+$('#google_geocode_api_key').val(), function (data) {
			google_geocode_request_status = data['status'];
			if(google_geocode_request_status=='REQUEST_DENIED'){
				$('#enable_address_validation').attr('checked', false);
				$('#adress_validation_status_invalid').text('INVALID');
				$('#adress_validation_status_valid').text('');					
			}else{
				$('#adress_validation_status_valid').text('VALID');
				$('#adress_validation_status_invalid').text('');	
			}
		});
	}
	//======================== Check Google Api Key ========================//

	//======================== Check Google Api Key On Checkbox Change ========================//
	$('#enable_address_validation').on('change', function(){
		if($('#google_geocode_api_key').val() == '' || $('#google_geocode_api_key').val() == null) {
			$('#enable_address_validation').attr('checked', false);
			$('#adress_validation_status_invalid').text('EMPTY');
			$('#adress_validation_status_valid').text(''); 
		} else {
			$.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+'ozamiz'+'&key='+$('#google_geocode_api_key').val(), function (data) {
				google_geocode_request_status = data['status'];
				if(google_geocode_request_status=='REQUEST_DENIED'){
					$('#enable_address_validation').attr('checked', false);
					$('#adress_validation_status_invalid').text('INVALID');
					$('#adress_validation_status_valid').text('');					
				}else{
					$('#adress_validation_status_valid').text('VALID');
					$('#adress_validation_status_invalid').text('');	
				}
			});
		}
	});
	//======================== Check Google Api Key On Checkbox Change ========================//
});


 