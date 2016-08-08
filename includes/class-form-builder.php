<?php

/**
 * Fired during plugin activation
 *
 * @link       http://alphasys.com.au/
 * @since      1.0.0
 *
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pronto_donation
 * @subpackage Pronto_donation/includes
 * @author     AlphaSys <marvin@alphasys.com.au>
 */

class form_builder {

	function __construct(){
	
	}


	function generate_fields($fields){

		$html = array();

		foreach($fields as $key=>$field)
		{
			switch($field['type'])
			{
				case 'text':
						array_push(
							$html,
							array(
								'label' => ucfirst($field['label']), 
								'field'=> '<input type="text" class="regular-text" name="' .$field['name']. '" value="' .$field['value']. '"/>'
							)
						);
					break;
				case 'checkbox':
						array_push(
							$html,
							array(
								'label' => ucfirst($field['label']), 
								'field'=> $field['value'] ? '<input type="checkbox" name="' . $field['name'] . '" checked="true" />' : '<input type="checkbox" name="' . $field['name'] . '"  />'
							)
						);
					break;
				case 'email':
						array_push(
							$html,
							array(
								'label' => ucfirst($field['label']), 
								'field'=> '<input type="email" class="regular-text" name="' .$field['name']. '" value="' .$field['value']. '"/>'
							)
						);
					break;
				case 'number':
						array_push(
							$html,
							array(
								'label' => ucfirst($field['label']), 
								'field'=> '<input type="number" class="regular-text" name="' .$field['name']. '" value="' .$field['value']. '"/>'
							)
						);
					break;
				case 'url':
						array_push(
							$html,
							array(
								'label' => ucfirst($field['label']), 
								'field'=> '<input type="url" class="regular-text" name="' .$field['name']. '" value="' .$field['value']. '"/>'
							)
						);
					break;
				default:
						array_push( $html, array('label' => ucfirst($field['name']), 'field'=> '<input type="text" class="regular-text" name="' .$field['name']. '" value="' .$field['value']. '"/>'));
			}
		}

		return $html;

	}

}