<style type="text/css">
	.theme-screenshot{
		padding: 1rem;
	}
	.theme-browser .theme .theme-screenshot img{
		max-width: 80%;
		top: 50%;
		left: 50%;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
	    transform: translate(-50%, -50%);
	}
	.payment-action{
		text-decoration: none;
		margin-top: 0.2rem;
		display: block;
		margin-left: 1rem;
		margin-right: 1rem;
	}
</style>

<div class="wrap">
<h1>Payment</h1>

<div class="theme-browser rendered">
	<div class="themes">
		<?php foreach($this->payments as $payment) :?>
		<div class="theme">

			<div class="theme-screenshot">
				<img src="<?php $payment->get_payment_logo() ?>" width="100%" />
			</div>

			<h3 class="theme-name"><?php $payment->get_payment_name() ?></h3>
			<!-- <p><?php $payment->get_payment_description() ?></p> -->
			<div class="theme-actions">
				
				<a href="<?php echo admin_url() . 'admin.php?page=donation-payment&action=1&payment=' . $payment->className ?>" class="payment-action"><span class="dashicons dashicons-admin-generic"></span></a>
			</div>
		</div>

		<?php endforeach; ?>
	</div>
</div>








</div>
