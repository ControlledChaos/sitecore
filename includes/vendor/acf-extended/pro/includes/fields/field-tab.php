<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_tab')):

class acfe_field_tab{
    
    function __construct(){
    
        add_action('acf/render_field_settings/type=tab',        array($this, 'field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=tab',    array($this, 'field_wrapper'), 10, 2);
        
    }
    
    function field_settings($field){
    
        acf_render_field_setting($field, array(
            'label'         => __('No Preference','acf'),
            'instructions'  => 'Do not save opened tab user preference',
            'name'          => 'no_preference',
            'type'          => 'true_false',
            'ui'            => 1
        ));
        
    }
    
    function field_wrapper($wrapper, $field){
        
        if(acf_maybe_get($field, 'no_preference')){
            
            $wrapper['data-no-preference'] = 1;
            
        }
        
        return $wrapper;
        
    }
    
}

new acfe_field_tab();

endif;