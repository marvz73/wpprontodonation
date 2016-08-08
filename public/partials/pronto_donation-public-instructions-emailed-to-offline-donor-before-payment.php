<?php

$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
$instructions_emailed_to_offline_donor_before_payment_page = (empty($pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'])) ? "" : $pronto_donation_settings['InstructionsEmailedToOfflineDonorBeforePaymentPage'];	

$my_postid = $instructions_emailed_to_offline_donor_before_payment_page;//This is page id or post id
$content_post = get_post($my_postid);
$content = $content_post->post_content;
echo $content;