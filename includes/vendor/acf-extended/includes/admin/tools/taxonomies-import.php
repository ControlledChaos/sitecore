<?php

if(!defined('ABSPATH'))
	exit;

if ( ! get_option( 'enable_dynamic_taxonomies' ) ) {
	return;
}

if(!class_exists('acfe_dynamic_taxonomies_import')):

class acfe_dynamic_taxonomies_import extends acfe_module_import{

	function initialize() {

		if ( ! get_option( 'enable_dynamic_taxonomies', true ) ) {
			return;
		}

		// vars
		$this->hook = 'taxonomy';
		$this->name = 'acfe_dynamic_taxonomies_import';
		$this->title = __('Import Taxonomies');
		$this->description = __('Import Taxonomies');
		$this->instance = acf_get_instance('acfe_dynamic_taxonomies');
		$this->messages = array(
			'success_single'    => '1 taxonomy imported',
			'success_multiple'  => '%s taxonomies imported',
		);

	}

}

acf_register_admin_tool('acfe_dynamic_taxonomies_import');

endif;