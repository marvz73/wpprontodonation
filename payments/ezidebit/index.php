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
				'type'  => 'text',
				'value' => '',
				'name'	=> 'url',
				'label' => 'URL'
			),
		);

	}
}