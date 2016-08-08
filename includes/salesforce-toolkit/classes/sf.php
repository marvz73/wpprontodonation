<?php
/**
* SF-PHP API Integration
*
* @desc: This will wrap the PHP Toolkit for SF
* @author: clifton s. alegarme - kryptonalegarme@gmail.com
* @date: 7/22/2016
* @version: 1.0
*
*/

ini_set('display_errors',0);

require_once(dirname(dirname(__FILE__))."/config/global.php");
require_once (SOAP_CLIENT_BASEDIR.'/SforceEnterpriseClient.php');
require_once (SOAP_CLIENT_BASEDIR.'/SforcePartnerClient.php');

class SF {

	var $SforceConnection;
	var $isSandbox;
	var $SfInstanceType; //Enterprise || Partner
	var $isENVType = true; //true:Enterprise, false:Partner
	var $options;

	public $faultCode;

	public $faultMessage;

	public function __construct($isSandbox = false, $SfInstanceType="Enterprise"){

		try{

			//Determine what SF Instance Type
			if($SfInstanceType == "Enterprise"){
				$this->SforceConnection  = new SforceEnterpriseClient();
				$this->SforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/enterprise.wsdl.xml');
			}
			else{
				$this->isENVType = false;
				$this->SforceConnection  = new SforcePartnerClient();
				$this->SforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/partner.wsdl.xml');
			}

			//Now check if Org Instance is Sandbox
			if($isSandbox){
				if($SfInstanceType == "Enterprise")
					$this->SforceConnection->setEndpoint('https://test.salesforce.com/services/Soap/c/37.0');
				else
					$this->SforceConnection->setEndpoint('https://test.salesforce.com/services/Soap/u/37.0');

			}
			
			//Login to Connection

			@$this->SforceConnection->login(USERNAME,PASSWORD);
			
			$this->options  = new QueryOptions(200);
			$this->SforceConnection->setQueryOptions($this->options);

		} catch (Exception $e) {

		  //echo $SforceConnection->getLastRequest();
		  $this->faultMessage = $e->faultstring;
		  $this->faultCode = $e->faultcode;
		  //return array('status'=>'401','message' => $e->faultstring);

		}
	}

	/* *     Public Methods   * */

	/**
	* Get UserInfo
	*
	* @return object
	*/

	public function getUserInfo(){

		return $this->SforceConnection->getUserInfo();
	}


	/** 
	* Get SF Server Timestamp
	*
	* @return object
	*/
	public function getServerTimestamp(){

		return $this->SforceConnection->getServerTimestamp();
	}


	/**
	* Create Record
	* 
	* @return : object
	*/
	public function createRecord($array, $sObject){

		try{

			$arrayObjects = $this->__prepData($array, $sObject);

			if($this->isENVType){ //Enterpirse
				return $this->SforceConnection->create($arrayObjects, $sObject);
			}else{
				return $this->SforceConnection->create($arrayObjects);
			}	

		} catch (Exception $e) {

		  //echo $SforceConnection->getLastRequest();

		  echo $e->faultstring;

		}
		
	}

	/**
	* Update Record
	*
	* @return : object
	*/
	public function updateRecord($array, $sObject){

		try{
			$arrayObjects = $this->__prepData($array, $sObject);

			if($this->isENVType){ //Enterpirse
				return $this->SforceConnection->update($arrayObjects, $sObject);
			}else{
				return $this->SforceConnection->update($arrayObjects);
			}	
		} catch (Exception $e) {

		  //echo $SforceConnection->getLastRequest();

		  echo $e->faultstring;

		}
	}

	/**
	* Upsert Record
	*
	* @return : object
	*/
	public function upsertRecord($array, $sObject){

		try {
			$arrayObjects = $this->__prepData($array, $sObject);

			if($this->isENVType){ //Enterpirse
				return $this->SforceConnection->upsert('Id',$arrayObjects, $sObject);
			}else{
				return $this->SforceConnection->upsert('Id',$arrayObjects);
			}	
		} catch (Exception $e) {

		  //echo $SforceConnection->getLastRequest();

		  echo $e->faultstring;

		}
	}

	/**
	* Merge Record
	* 
	*
	*/
	public function mergeRecord(){
		
	}

	/**
	* Get Record
	* 
	* @return : object
	*/
	public function getRecord($query){

		try{
			if($this->isENVType){ //Enterpirse

				return $this->SforceConnection->query($query);

			} else{

				$response = $this->SforceConnection->query($query);

				return new QueryResult($response);
			}

		} catch (Exception $e) {

		  //echo $SforceConnection->getLastRequest();

		  echo $e->faultstring;

		}

	}

	/**
	* Get Record More
	*
	* @return : array(objects);
	*/
	public function getMoreRecord($query){

		$arrayResult = array();
		$response = $this->getRecord($query);

		!$done = false;

		if($response.size > 0){
			while(!$done){

				foreach($response->records as $record){
					$arrayResult[] = ($this->isENVType) ? $record : $record->fields;
				}

				if($response->done != true){

					try{
						$resp = $this->SforceConnection->queryMore($response->queryLocator);
						
						if($this->isENVType){
							$response = $resp;
						}else{
							$response = new QueryResult($resp);
						}	
					} catch (Exception $e) {

					  //echo $SforceConnection->getLastRequest();

					  echo $e->faultstring;

					}
				}else{
					$done = true;
				}
			}
		}

		return $arrayResult;

	}


	/**
	* Delete Record
	* 
	* @param: $array - is collection of Record ID i.e array('1','2','3')
	* @return array(objects);
	*/
	public function delete($array){
		try{
			return $this->SforceConnection->delete($array);
		} catch (Exception $e) {
		 //echo $SforceConnection->getLastRequest();
		 echo $e->faultstring;
		}
	}

	/**
	* UnDelete Record
	* 
	* @param: $array - is collection of Record ID i.e array('1','2','3')
	* @return array(objects);
	*/
	public function undelete($array){
		try{
			return $this->SforceConnection->undelete($array);
		} catch (Exception $e) {
		 //echo $SforceConnection->getLastRequest();
  		  echo $e->faultstring;
		}
	}


	/** 	Private Methods  **/

	/**
	* Prep Data
	* 
	* @return array(objects);
	*/
	private function __prepData($array, $sObject){

		$arrayObject = array();

		if(is_array($array) && count($array) > 0){

			foreach ($array as $arr) {
				
				if($this->isENVType){ //Enterpirse			
				
					$arrayObject[] = (object) $arr;
				
				} else{

					$Object = new SObject();
					$Object->fields = $arr;
					$Object->type = $sObject;
					
					if(isset($array['Id']) && $array['Id'] != null)
						$Object->Id = $array['Id'];

					$arrayObject[] = $Object;
				}

			}
		}

		return $arrayObject;
		
	}

}