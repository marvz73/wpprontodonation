<?php

class salesforceSOAP{

  /**
   * The salesforce is the variable to hold the function of other class.
   *
   * @access   public
   * @var      object    $salesforce    The object used to hold the salesforce function
   */
  public $salesforce;

  /**
   * The wpOptions is the wordpress options.
   *
   * @access   protected
   * @var      object    $wpOptions    The object used to hold the wp options
   */
  protected $wpOptions;


  /**
   * Define the core functionality of the plugin.
   */
  public function __construct() {

    //Wordpress option
    $this->wpOptions = $this->getWPOptions();

    //Load salesforce dependencies
    $this->load_dependencies();

    //Salesforce client init and authentication
    $this->salesforce = $this->_initSalesforce(
      $this->wpOptions->SalesforceUsername, 
      $this->wpOptions->SalesforcePassword . $this->wpOptions->SecurityToken,
      'ENTERPRICE'
    );

  }

  /** 
  * Select the instance type and login to salesforce
  * @return object
  */
  private function _initSalesforce($username, $passwordtoken, $instanceType){
    
    try{

      if(!empty($instanceType) && $instanceType == "ENTERPRICE"){
        $instanceClass  = new SforceEnterpriseClient();
        $instanceClass->createConnection(dirname(dirname(__FILE__)).'/salesforce-toolkit/soapclient/enterprise.wsdl.xml');
      }else{
        $instanceClass  = new SforcePartnerClient();
        $instanceClass->createConnection(dirname(dirname(__FILE__)).'/salesforce-toolkit/soapclient/partner.wsdl.xml');
      }

      $authResult = $instanceClass->login($username, $passwordtoken);

      $options = new QueryOptions(200);
      $instanceClass->setQueryOptions($options);

    }catch(Exception $e){

      $instanceClass->error = $e->faultstring;

    }

    return $instanceClass;

  }

  /**
   * Load the required dependencies for this class.
   *
   * Create an instance of the enterprise and partner client which will be used to register the class function.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     *  This will load the enterprise client class
     */

    require_once ( dirname(dirname(__FILE__)) . '/salesforce-toolkit/soapclient/SforceEnterpriseClient.php');
    
    /**
     *  This will load the partner client class
     */
    require_once (dirname(dirname(__FILE__)) . '/salesforce-toolkit/soapclient/SforcePartnerClient.php');  

  }

  /** 
  * Get wordpress pronto_donation_settings
  * @return object
  */
  private function getWPOptions(){

    $wpOptions = get_option('pronto_donation_settings', 0);

    return $this->_toObject($wpOptions);
  }

  /** 
  * Convert array to object array
  * @return object
  */
  private function _toObject($option){
    $options = new stdClass();
    foreach ($option as $key => $value)
    {
      $options->$key = $value;
    }
    return $options;
  }

}


























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
