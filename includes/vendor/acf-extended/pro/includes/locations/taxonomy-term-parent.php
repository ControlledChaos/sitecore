<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_taxonomy_term_parent')):

class acfe_location_taxonomy_term_parent extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'taxonomy_term_parent';
        $this->label    = __('Taxonomy Term Parent', 'acf');
        $this->category = 'forms';
        
    }
    
    function rule_values($choices, $rule){
    
        return acf_get_location_rule('taxonomy_term')->rule_values($choices, $rule);
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $taxonomy = acf_maybe_get($screen, 'taxonomy');
        $term_id = acf_maybe_get($screen, 'term_id');
    
        // Bail early
        if(!$taxonomy || !$term_id)
            return false;
        
        $term_parent = get_term_field('parent', $term_id);
        
        return $this->compare($term_parent, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_taxonomy_term_parent');

endif;