<?php 

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/templates'))
    return;

if(!class_exists('acfe_dynamic_templates_export')):

class acfe_dynamic_templates_export extends acfe_module_export{
    
    function initialize(){
        
        // vars
        $this->name = 'acfe_dynamic_templates_export';
        $this->title = __('Export Templates');
        $this->description = __('Export Templates');
        $this->select = __('Select Templates');
        $this->default_action = 'json';
        $this->allowed_actions = array('json', 'php');
        $this->instance = acf_get_instance('acfe_dynamic_templates');
        $this->file = 'template';
        $this->files = 'templates';
        $this->messages = array(
            'not_found'         => __('No template available.'),
            'not_selected'      => __('No templates selected'),
            'success_single'    => '1 template exported',
            'success_multiple'  => '%s templates exported',
        );
        
    }
    
}

acf_register_admin_tool('acfe_dynamic_templates_export');

endif;