<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_post_author')):

class acfe_location_post_author extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'post_author';
        $this->label    = __('Post Author', 'acf');
        $this->category = 'post';
        
    }
    
    function rule_values($choices, $rule){
    
        $choices = array();
        $users = get_users();
    
        // Append.
        if(!$users)
            return $choices;
        
        foreach($users as $user){
            
            $get_user = acf_get_user_result($user);
            
            $choices[$user->ID] = $get_user['text'];
            
        }
        
        return $choices;
        
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
    
        return $this->compare($post_author, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_post_author');

endif;