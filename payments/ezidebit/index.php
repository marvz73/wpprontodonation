<?php
/*
 * Name: Marvin Aya-ay
 * Email: marvin@alphasys.com.au
 * Desc: eWay payment gateway
 * Date: July 20, 2016
 */

class ezidebit{

	//Payment Details
	var $payment = array(
		'logo'					=> 'logo.png',
		'payment_name' 			=> 'Ezidebit',
		'payment_description' 	=> 'This is a payment description here.',
		'url'					=> ''
	);


	function get_payment_name(){
		echo $this->payment['payment_name'];
	}

	function get_payment_description(){
		echo $this->payment['payment_description'];
	}

	function get_payment_logo(){
		echo plugins_url( $this->payment['logo'], __FILE__ );
	}

	function get_form_fields(){

		return $form = array(
			array(
				'type'  => 'checkbox',
				'value' => false,
				'name'	=> 'sandboxmode',
				'label'	=> 'Sanbox Mode'
			),
			array(
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'logo',
				'label'	=> 'Show Logo'
			),
			array(
				'type'  => 'checkbox',
				'value' => '',
				'name'	=> 'enable',
				'label'	=> 'Enable Payment'
			),
			array(
				'type'  => 'text',
				'value' => '',
				'name'	=> 'url',
				'label' => 'URL'
			),
		);

	}

	public function payment_process($ppd = array()){

		// ShowDisabledInputs	
		// RedirectMethod	
		// RedirectURL	
		// PaymentReference	

	 	// global $wpdb;
		// $wpdb->query("UPDATE $wpdb->postmeta SET meta_value = '123123123123' WHERE meta_id = 28");

		$url = $ppd['payment_info']->option['url'];

		$fields = array(
			// 'CompanyName'			=> $ppd['payment_info']
			'FirstName'				=> $ppd['first_name'],
			'LastName'				=> $ppd['last_name'],
			'EmailAddress'			=> $ppd['email'],
			'MobilePhoneNumber'		=> $ppd['phone'],
			'PaymentAmount'			=> $ppd['pd_amount'],
			'ShowDisabledInputs'	=> 0,
			'RedirectMethod'		=> 'GET',
			'RedirectURL'			=> 'http://localhost/wordpress',
			'PaymentReference'		=> $ppd['post_meta_id']
		);


		require_once('tmpl/tmpl_payment_process.php');

	}

}