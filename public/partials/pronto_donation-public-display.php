<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article class="post-2 page type-page status-publish hentry">



				<div class="entry-content container">
					<?php do_shortcode('[pronto-donation-full campaign='.$post->ID.']') ?>
				</div>




			</article>
		</main>
	</div>


