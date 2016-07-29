
<div class="pronto_donation_error">
<?php
	foreach($this->errors as $key=>$error)
	{
		echo "<p style='color: red;'>* ".$error."</p>";
	}
?>
</div>




<div id="campaign_banner">
	<img src="<?php echo $pronto_donation_campaign['banner_image'] ?>" alt="<?php echo $post->post_title ?>">
</div>

<h3 id="pronto-donation-title" ><?php echo $pronto_donation_campaign['post']['post_title'] ?></h3>

<p id="pronto-donation-description"><?php echo $pronto_donation_campaign['post']['post_content'] ?></p>

<a id="pronto-donation-link" href="<?php echo get_home_url() . '?campaign='.$pronto_donation_campaign['post']['post_name'] ?>">Donate Now</a>
