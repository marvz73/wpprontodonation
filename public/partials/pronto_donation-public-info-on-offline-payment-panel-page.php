<?php

$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
$info_on_offline_payment_panel_page = (empty($pronto_donation_settings['InfoOnOfflinePaymentPanelPage'])) ? "" : $pronto_donation_settings['InfoOnOfflinePaymentPanelPage'];

$my_postid = $info_on_offline_payment_panel_page;//This is page id or post id
$content_post = get_post($my_postid);
$content = $content_post->post_content;
echo $content;