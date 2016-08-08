<?php

require_once("config/global.php");

$pronto_donation_settings = get_option('pronto_donation_settings',0);


  $security_token = (empty($pronto_donation_settings['SecurityToken'])) ? "" : $pronto_donation_settings['SecurityToken'];
  $salesforce_username = (empty($pronto_donation_settings['SalesforceUsername'])) ? "" : $pronto_donation_settings['SalesforceUsername'];
  $salesforce_password = (empty($pronto_donation_settings['SalesforcePassword'])) ? "" : $pronto_donation_settings['SalesforcePassword'];

  define("USERNAME",$salesforce_username);
  define("SECURITY_TOKEN",$security_token);
  define("PASSWORD",$salesforce_password.SECURITY_TOKEN);


require_once("classes/sf.php");



// $SF = new SF(true);
// print_r($SF->faultMessage);









//$array = array((object)array('Name'=>'Test1'),array('Name'=>'Test2'),array('Name'=>'Test3'));

//$object = (object) $array;

// echo "<Pre>";
// //print_r($array);exit;
// try{

// 	$SF = new SF(true);

// 	print_r($SF->getServerTimestamp());
// 	//$query = 'SELECT Id, CaseNumber, Subject from Case';

	//$response = $SF->getRecord($query);
	//print_r($response);
	  /*foreach ($response->records as $record) {
	    print_r($record);
	    print_r("<br>");
	  }*/

	/*$SforceConnection  = new SforceEnterpriseClient();

	$SforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
	$SforceConnection->setEndpoint('https://test.salesforce.com/services/Soap/c/37.0');


	$MyLogin = $SforceConnection->login(USERNAME,PASSWORD);

	echo "<pre>";
*/
 /*
  echo "***** Login Info*****\n";
  print_r($MyLogin);
 */

 //Create Record
 /* $sObject = new stdclass();
  $sObject->Name = 'TEST CLIFTON WEB';
  echo "<pre>";
  $response = $SforceConnection->create(array($sObject),'Account');

  print_r($response);
*/
  
  
  //Query
  /*$query = 'SELECT Id, CaseNumber, Subject from Case';
  $options = new QueryOptions(200);
  $SforceConnection->setQueryOptions($options);
  $response = $SforceConnection->query(($query));
  foreach ($response->records as $record) {
    print_r($record);
    print_r("<br>");
  }*/


  //QueryMORE
 /* $query = 'SELECT Id, CaseNumber, Subject from Case'; //where NumberOfEmployees != null order by NumberOfEmployees
  $options = new QueryOptions(200);
  $SforceConnection->setQueryOptions($options);
  $response = $SforceConnection->query($query);

  !$done = false;
  echo "Size of records:  ".$response ->size."\n";

  if ($response->size > 0) {
    while (!$done) {
      foreach ($response->records as $record) {
        echo $record->CaseNumber." - ".$record->Subject."\r\n";

      }

      if ($response->done != true) {

        echo "***** Get Next Chunk *****\n";
        try {
          $response = $SforceConnection->queryMore($response->queryLocator);
        } catch (Exception $e) {
          print_r($SforceConnection->getLastRequest());
          echo $e->faultstring;
        }
      } else {
        $done = true;
      }
    }
  }
*/
  //DescribeObjecct
  //print_r($SforceConnection->describeSObject('Case')->fields);

// } catch (Exception $e) {

//   //echo $SforceConnection->getLastRequest();

//   echo $e->faultstring;

// }


require_once("config/global.php");

$pronto_donation_settings = get_option('pronto_donation_settings',0);

if($pronto_donation_settings!=0){
  
  $security_token = (empty($pronto_donation_settings['SecurityToken'])) ? "" : $pronto_donation_settings['SecurityToken'];
  $salesforce_username = (empty($pronto_donation_settings['SalesforceUsername'])) ? "" : $pronto_donation_settings['SalesforceUsername'];
  $salesforce_password = (empty($pronto_donation_settings['SalesforcePassword'])) ? "" : $pronto_donation_settings['SalesforcePassword'];

  define("USERNAME",$salesforce_username);
  define("SECURITY_TOKEN",$security_token);
  define("PASSWORD",$salesforce_password.SECURITY_TOKEN);

}


require_once("classes/sf.php");

//$array = array((object)array('Name'=>'Test1'),array('Name'=>'Test2'),array('Name'=>'Test3'));

//$object = (object) $array;

// echo "<Pre>";
// //print_r($array);exit;
// try{

// 	$SF = new SF(true);

// 	print_r($SF->getServerTimestamp());
// 	//$query = 'SELECT Id, CaseNumber, Subject from Case';

	//$response = $SF->getRecord($query);
	//print_r($response);
	  /*foreach ($response->records as $record) {
	    print_r($record);
	    print_r("<br>");
	  }*/

	/*$SforceConnection  = new SforceEnterpriseClient();

	$SforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
	$SforceConnection->setEndpoint('https://test.salesforce.com/services/Soap/c/37.0');


	$MyLogin = $SforceConnection->login(USERNAME,PASSWORD);

	echo "<pre>";
*/
 /*
  echo "***** Login Info*****\n";
  print_r($MyLogin);
 */

 //Create Record
 /* $sObject = new stdclass();
  $sObject->Name = 'TEST CLIFTON WEB';
  echo "<pre>";
  $response = $SforceConnection->create(array($sObject),'Account');

  print_r($response);
*/
  
  
  //Query
  /*$query = 'SELECT Id, CaseNumber, Subject from Case';
  $options = new QueryOptions(200);
  $SforceConnection->setQueryOptions($options);
  $response = $SforceConnection->query(($query));
  foreach ($response->records as $record) {
    print_r($record);
    print_r("<br>");
  }*/


  //QueryMORE
 /* $query = 'SELECT Id, CaseNumber, Subject from Case'; //where NumberOfEmployees != null order by NumberOfEmployees
  $options = new QueryOptions(200);
  $SforceConnection->setQueryOptions($options);
  $response = $SforceConnection->query($query);

  !$done = false;
  echo "Size of records:  ".$response ->size."\n";

  if ($response->size > 0) {
    while (!$done) {
      foreach ($response->records as $record) {
        echo $record->CaseNumber." - ".$record->Subject."\r\n";

      }

      if ($response->done != true) {

        echo "***** Get Next Chunk *****\n";
        try {
          $response = $SforceConnection->queryMore($response->queryLocator);
        } catch (Exception $e) {
          print_r($SforceConnection->getLastRequest());
          echo $e->faultstring;
        }
      } else {
        $done = true;
      }
    }
  }
*/
  //DescribeObjecct
  //print_r($SforceConnection->describeSObject('Case')->fields);

// } catch (Exception $e) {

//   //echo $SforceConnection->getLastRequest();

//   echo $e->faultstring;

// }

