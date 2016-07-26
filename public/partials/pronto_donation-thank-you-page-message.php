<?php

$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
$thank_you_page_message_page = (empty($pronto_donation_settings['ThankYouPageMessagePage'])) ? "" : $pronto_donation_settings['ThankYouPageMessagePage'];

$my_postid = $thank_you_page_message_page;//This is page id or post id
$content_post = get_post($my_postid);
$content = $content_post->post_content;
echo $content;