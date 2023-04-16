<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_post_screen')):

class acfe_location_post_screen extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'post_screen';
        $this->label    = __('Post Screen', 'acf');
        $this->category = 'post';
        
    }
    
    function rule_values($choices, $rule){
        
        return array(
            'post-new.php' => __('Add New'),
            'post.php' => __('Edit'),
        );
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        global $pagenow;
        
        $post_id = acf_maybe_get($screen, 'post_id');
        $post_type = acf_maybe_get($screen, 'post_type');
    
        // Bail early
        if(!$pagenow || !$post_id || !$post_type)
            return false;
    
        // Compare
        return $this->compare($pagenow, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_post_screen');

endif;