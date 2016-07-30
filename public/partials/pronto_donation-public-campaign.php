<?php if($attrs['hidebanner']!='true'): ?>
<div id="pronto-donation-banner">
	<img src="<?php echo $pronto_donation_campaign['banner_image'] ?>" alt="<?php echo $post->post_title ?>">
</div>
<?php endif; ?>

<div class="pd-container pronto-donation-campaign">

	<div class='pd-col s12 m8 l8 pronto-donation-contents'>
	<?php if($attrs['hidetitle']!='true'): ?>
		<h3 id="pronto-donation-title" ><?php echo $pronto_donation_campaign['post']['post_title'] ?></h3>
	<?php endif; ?>

	<?php if($attrs['hidedesc']!='true'): ?>
	<p id="pronto-donation-description"><?php echo $pronto_donation_campaign['post']['post_content'] ?></p>
	<?php endif; ?>

	</div>
	<div class="pd-col s12 m4 l4 pronto-donation-attributes">
		<?php if($pronto_donation_campaign['donation_target']!=''): ?>
			<div id="pronto-donation-target">
				<div>Target</div> 
				<strong><?php echo $pronto_donation_campaign['currency'] ?><?php echo $pronto_donation_campaign['donation_target'] ?></strong>
				
			</div>
		<?php endif; ?>

		<div id="pronto-donation-amount">
			<div>Amount</div> <strong><?php echo $pronto_donation_campaign['currency'] ?><?php echo $pd_donation_details['total_donation_amount'] ?></strong>
				
		</div>

		<div id="pronto-donation-backers">
			<div>Backers</div> <strong><?php echo $pd_donation_details['total_donator'] ?></strong>
		</div>
			<a id="pronto-donation-link" class="pronto-donation-btn btn" href="<?php echo get_home_url() . '?campaign='.$pronto_donation_campaign['post']['post_name'] ?>">Donate Now</a>
	</div>


</div>