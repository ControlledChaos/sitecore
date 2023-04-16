<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_taxonomy_term')):

class acfe_location_taxonomy_term extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'taxonomy_term';
        $this->label    = __('Taxonomy Term', 'acf');
        $this->category = 'forms';
        
    }
    
    function rule_values($choices, $rule){
        
        $choices = acfe_get_taxonomy_terms_ids();
        
        return $choices;
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $taxonomy = acf_maybe_get($screen, 'taxonomy');
        $term_id = acf_maybe_get($screen, 'term_id');
    
        // Bail early
        if(!$taxonomy || !$term_id)
            return false;
        
        return $this->compare($term_id, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_taxonomy_term');

endif;