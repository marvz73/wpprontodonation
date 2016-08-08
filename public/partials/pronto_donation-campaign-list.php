<div id="pronto_donation_list" class="row">
<?php 
 	foreach($campaign_list as $index=>$campaign):
	?>
		<div class="col-4">
			<div id="pronto-donation-banner"><img width="100%" src="<?php echo $campaign->post_meta['banner_image'] ?>" alt="<?php echo $campaign->post_title ?>" /></div>
			<h5 id="pronto-donation-title"><?php echo $campaign->post_title ?></h5> 

			<p id="pronto-donation-description"><?php echo substr( $campaign->post_content,0,140 ) . "..";  ?></p>
			<p>
				
			<a class="pronto-donation-btn" href="<?php echo get_home_url() . '?campaign='.$campaign->post_name ?>" class="button btn button-primary">Donate Now</a>
			</p>
		</div>
 	<?php
 		endforeach;
	?>
</div>