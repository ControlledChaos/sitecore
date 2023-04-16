<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_menu_item_type')):

class acfe_location_menu_item_type extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'nav_menu_item_type';
        $this->label    = __('Menu Item Type', 'acf');
        $this->category = 'forms';
        
    }
    
    function rule_values($choices, $rule){
    
        return array(
            'custom'    => __('Custom'),
            'post_type' => __('Post'),
            'taxonomy'  => __('Taxonomy'),
        );
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $type = acf_maybe_get($screen, 'nav_menu_item');
    
        // Bail early
        if(!$type)
            return false;
    
        // Compare
        return $this->compare($type, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_menu_item_type');

endif;