<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_select')):

class acfe_pro_field_select{
    
    function __construct(){
        
        // Actions
        add_action('acf/render_field_settings/type=select',         array($this, 'render_field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=select',     array($this, 'field_wrapper_attributes'), 10, 2);
        
    }
    
    function render_field_settings($field){

        // prepend
        acf_render_field_setting($field, array(
            'label'             => __('Prepend','acf'),
            'instructions'      => __('Appears before the input','acf'),
            'type'              => 'text',
            'name'              => 'prepend',
            'placeholder'       => '',
        ));

        // append
        acf_render_field_setting($field, array(
            'label'             => __('Append','acf'),
            'instructions'      => __('Appears after the input','acf'),
            'type'              => 'text',
            'name'              => 'append',
            'placeholder'       => '',
        ));

    }
    
    function field_wrapper_attributes($wrapper, $field){
        
        // Prepend
        if(acf_maybe_get($field, 'prepend')){
            
            $wrapper['data-acfe-prepend'] = $field['prepend'];
            
        }
        
        // Append
        if(acf_maybe_get($field, 'append')){
            
            $wrapper['data-acfe-append'] = $field['append'];
            
        }
        
        return $wrapper;
        
    }
    
}

new acfe_pro_field_select();

endif;