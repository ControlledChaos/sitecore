<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_group_display_title')):

class acfe_field_group_display_title{
 
    function __construct(){
        
        add_filter('acfe/prepare_field_group', array($this, 'prepare_field_group'));
        
    }
    
    /*
     * Prepare Field Group
     */
    function prepare_field_group($field_group){
        
        if(!acf_maybe_get($field_group, 'acfe_display_title'))
            return $field_group;
        
        $field_group['title'] = $field_group['acfe_display_title'];
        
        return $field_group;
        
    }
    
}

// initialize
new acfe_field_group_display_title();

endif;