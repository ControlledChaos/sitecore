<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_post_path')):

class acfe_location_post_path extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'post_path';
        $this->label    = __('Post Path', 'acf');
        $this->category = 'post';
        
    }
    
    function rule_values($choices, $rule){
    
        if(!acf_is_screen('acf-field-group') && !acf_is_ajax('acf/field_group/render_location_rule')){
        
            return array(
                $rule['value'] => $rule['value']
            );
        
        }
        
        ob_start();
        
        acf_render_field(array(
            'type'      => 'text',
            'name'      => 'value',
            'prefix'    => 'acf_field_group[location]['.$rule['group'].']['.$rule['id'].']',
            'value'     => (isset($rule['value']) ? $rule['value'] : '')
        ));
        
        return ob_get_clean();
        
    }
    
    function rule_operators($choices, $rule){
        
        $choices['contains']    = __('contains', 'acf');
        $choices['!contains']   = __('doesn\'t contains', 'acf');
        $choices['starts']      = __('starts with', 'acf');
        $choices['!starts']     = __('doesn\'t starts with', 'acf');
        $choices['ends']        = __('ends with', 'acf');
        $choices['!ends']       = __('doesn\'t ends with', 'acf');
        $choices['regex']       = __('matches regex', 'acf');
        $choices['!regex']      = __('doesn\'t matches regex', 'acf');
        
        return $choices;
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $post_id = acf_maybe_get($screen, 'post_id');
        $post_type = acf_maybe_get($screen, 'post_type');
    
        // Bail early
        if(!$post_id || !$post_type)
            return false;
    
        $post_path = get_page_uri($post_id);
    
        // Compare
        return $this->compare_advanced($post_path, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_post_path');

endif;