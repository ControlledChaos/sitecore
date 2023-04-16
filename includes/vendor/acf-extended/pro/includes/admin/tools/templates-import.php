<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/templates'))
    return;

if(!class_exists('acfe_dynamic_templates_import')):

class acfe_dynamic_templates_import extends acfe_module_import{
    
    function initialize(){
        
        // vars
        $this->hook = 'template';
        $this->name = 'acfe_dynamic_templates_import';
        $this->title = __('Import Templates');
        $this->description = __('Import Templates');
        $this->instance = acf_get_instance('acfe_dynamic_templates');
        $this->messages = array(
            'success_single'    => '1 template imported',
            'success_multiple'  => '%s templates imported',
        );
        
    }
    
}

acf_register_admin_tool('acfe_dynamic_templates_import');

endif;