<?php if($attrs['hidebanner']!='true'): ?>
<div id="campaign_banner">
	<img src="<?php echo $pronto_donation_campaign['banner_image'] ?>" alt="<?php echo $post->post_title ?>">
</div>
<?php endif; ?>


<?php if($attrs['hidetitle']!='true'): ?>
	<h3 id="pronto-donation-title" ><?php echo $pronto_donation_campaign['post']['post_title'] ?></h3>
<?php endif; ?>

<?php if($attrs['hidedesc']!='true'): ?>
<p id="pronto-donation-description"><?php echo $pronto_donation_campaign['post']['post_content'] ?></p>
<?php endif; ?>

<a id="pronto-donation-link" href="<?php echo get_home_url() . '?campaign='.$pronto_donation_campaign['post']['post_name'] ?>">Donate Now</a>
