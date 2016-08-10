<?php
// this will be the Initialization of the Salesforce SOAP
require_once("config/global.php");

$pronto_donation_settings = get_option('pronto_donation_settings',0);


$security_token = (empty($pronto_donation_settings['SecurityToken'])) ? "" : $pronto_donation_settings['SecurityToken'];
$salesforce_username = (empty($pronto_donation_settings['SalesforceUsername'])) ? "" : $pronto_donation_settings['SalesforceUsername'];
$salesforce_password = (empty($pronto_donation_settings['SalesforcePassword'])) ? "" : $pronto_donation_settings['SalesforcePassword'];

define("USERNAME",$salesforce_username);
define("SECURITY_TOKEN",$security_token);
define("PASSWORD",$salesforce_password.SECURITY_TOKEN);


require_once("classes/sf.php");
