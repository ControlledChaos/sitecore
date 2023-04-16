<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_taxonomy_term_type')):

class acfe_location_taxonomy_term_type extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'taxonomy_term_type';
        $this->label    = __('Taxonomy Term Type', 'acf');
        $this->category = 'forms';
        
    }
    
    function rule_values($choices, $rule){
    
        $choices = array(
            'top_level' => __('Top Level Term (no parent)', 'acfe'),
            'parent'    => __('Parent Term (has children)', 'acfe'),
            'child'     => __('Child Term (has parent)', 'acfe'),
        );
        
        return $choices;
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $taxonomy = acf_maybe_get($screen, 'taxonomy');
        $term_id = acf_maybe_get($screen, 'term_id');
    
        // Bail early
        if(!$taxonomy || !$term_id)
            return false;
        
        $term_parent = get_term_field('parent', $term_id);
        
        // Top Level
        if($rule['value'] === 'top_level'){
    
            $result = ($term_parent == 0);
        
        // Parent
        }elseif($rule['value'] === 'parent'){
    
            $term_childs = acf_get_terms(array(
                'parent'    => $term_id,
                'taxonomy'  => $taxonomy,
                'fields'    => 'ids',
            ));
    
            $result = !empty($term_childs);
        
        // Child
        }elseif($rule['value'] === 'child'){
    
            $result = ($term_parent > 0);
        
        }
        
        // Reverse
        if($rule['operator'] === '!='){
        
            $result = !$result;
        
        }
        
        return $result;
        
    }
    
}

acf_register_location_rule('acfe_location_taxonomy_term_type');

endif;