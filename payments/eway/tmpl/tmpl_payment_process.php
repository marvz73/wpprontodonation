
<script type="text/javascript">
	
	setTimeout(function(){
		window.location.href = "<?php echo $result->SharedPaymentUrl ?>";
	}, 200)

</script>

<div style="text-align: center;">
	<div class="circle-wrapper">
	  <div class="circle-loader">
	      <div class="circle circle_four"></div>
	      <div class="circle circle_three"></div>
	      <div class="circle circle_two"></div>
	      <div class="circle circle_one"></div>
	  </div>
	</div>

	<h3>Redirecting to payment gateway...</h3>
</div>