<?php

$pronto_donation_settings = (empty(get_option('pronto_donation_settings'))) ? "" : get_option('pronto_donation_settings');
$cancel_page_message_page = (empty($pronto_donation_settings['CancelPageMessagePage'])) ? "" : $pronto_donation_settings['CancelPageMessagePage'];

$my_postid = $cancel_page_message_page;//This is page id or post id
$content_post = get_post($my_postid);
$content = $content_post->post_content;
echo $content;