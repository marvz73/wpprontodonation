<div class="pronto_donation_error">
<?php
	$eway_SP_error = (!isset($_GET['SP_Status'])) ? "" : $_GET['SP_Status'];
	$eway_SP_error_array = explode(",", $eway_SP_error);

	if(in_array("V6069", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Email Address</div>';
	}if(in_array("V6070", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Phone Number</div>';
	}


	if(in_array("V6110", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Card Number</div>';
	}if(in_array("V6100", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Card Name</div>';
	}if(in_array("V6101", $eway_SP_error_array)||in_array("V6102", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Card Expiry Date</div>';
	}if(in_array("V6106", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Card CCV</div>';
	}


	if(in_array("V6064", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Street Address</div>';
	}if(in_array("V6053", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Country</div>';
	}if(in_array("V6067", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid State</div>';
	}if(in_array("V6068", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Postal Code</div>';
	}if(in_array("V6066", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid City/Suburb</div>';
	}

	if(in_array("V6011", $eway_SP_error_array)){
		echo '<div class="eway_alert">Invalid Amount</div>';
	}
	
?>
</div>