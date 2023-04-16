<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_post_author_role')):

class acfe_location_post_author_role extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'post_author_role';
        $this->label    = __('Post Author Role', 'acf');
        $this->category = 'post';
        
    }
    
    function rule_values($choices, $rule){
    
        global $wp_roles;
        
        return wp_parse_args($wp_roles->get_names(), array(
            'all' => __('All', 'acf')
        ));
        
    }
    
    function rule_match($result, $rule, $screen){
        
        // Vars
        $post_id = acf_maybe_get($screen, 'post_id');
        $post_type = acf_maybe_get($screen, 'post_type');
        
        // Bail early
        if(!$post_id || !$post_type)
            return false;
        
        $post_author = get_post_field('post_author', $post_id);
        
        if(!$post_author)
            return false;
        
        $user_role = false;
    
        if(user_can($post_author, $rule['value']))
            $user_role = $rule['value'];
    
        return $this->compare($user_role, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_post_author_role');

endif;