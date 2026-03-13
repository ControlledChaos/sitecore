<?php

if(!defined('ABSPATH'))
	exit;

if(!class_exists('acfe_dynamic_post_types_import')):

class acfe_dynamic_post_types_import extends acfe_module_import{

	function initialize() {

		if ( ! get_option( 'enable_dynamic_post_types', true ) ) {
			return;
		}

		// vars
		$this->hook = 'post_type';
		$this->name = 'acfe_dynamic_post_types_import';
		$this->title = __('Import Post Types');
		$this->description = __('Import Post Types');
		$this->instance = acf_get_instance('acfe_dynamic_post_types');
		$this->messages = array(
			'success_single'    => '1 post type imported',
			'success_multiple'  => '%s post types imported',
		);

	}

}

acf_register_admin_tool('acfe_dynamic_post_types_import');

endif;