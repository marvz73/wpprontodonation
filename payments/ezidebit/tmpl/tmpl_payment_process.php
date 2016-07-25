<script type="text/javascript">
setTimeout(function(){
	document.getElementById("payment").submit();
}, 500)
</script>

<form id="payment" method="post" action="<?php echo $url ?>">
	<input type="hidden" name="Type" value="I" />
	<input type="hidden" name="FirstName" value="<?php echo $fields['FirstName'] ?>" />
	<input type="hidden" name="LastName" value="<?php echo $fields['LastName'] ?>" />
	<input type="hidden" name="MobilePhoneNumber" value="<?php echo $fields['MobilePhoneNumber'] ?>" />
	<input type="hidden" name="EmailAddress" value="<?php echo $fields['EmailAddress'] ?>" />
	<input type="hidden" name="PaymentAmount" value="<?php echo $fields['PaymentAmount'] ?>" />
	<input type="hidden" name="ShowDisabledInputs" value="<?php echo $fields['ShowDisabledInputs'] ?>" />
	<input type="hidden" name="RedirectMethod" value="<?php echo $fields['RedirectMethod'] ?>" />
	<input type="hidden" name="RedirectURL" value="<?php echo $fields['RedirectURL'] ?>" />
	<input type="hidden" name="PaymentReference" value="<?php echo $fields['PaymentReference'] ?>" />
	<button type="submit" style="display: none;">Submit</button>
</form>