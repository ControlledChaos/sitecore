<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location')):

class acfe_location extends acf_location{
    
    function initialize(){
    
        add_filter('acf/location/rule_types', array($this, 'location_rules_types'));
        
    }
    
    function location_rules_types($groups){
        
        foreach($groups as $group => &$sub_group){
            
            if(isset($sub_group['taxonomy_list'])){
    
                $sub_group = acfe_array_insert_after('taxonomy_list', $sub_group, 'taxonomy_term_type', '');
                $sub_group = acfe_array_insert_after('taxonomy_list', $sub_group, 'taxonomy_term_slug', '');
                $sub_group = acfe_array_insert_after('taxonomy_list', $sub_group, 'taxonomy_term_parent', '');
                $sub_group = acfe_array_insert_after('taxonomy_list', $sub_group, 'taxonomy_term_name', '');
                $sub_group = acfe_array_insert_after('taxonomy_list', $sub_group, 'taxonomy_term', '');
                
            }
            
            if(isset($sub_group['nav_menu_item'])){
    
                $sub_group = acfe_array_insert_after('nav_menu_item', $sub_group, 'nav_menu_item_type', '');
                $sub_group = acfe_array_insert_after('nav_menu_item', $sub_group, 'nav_menu_item_depth', '');
            
            }
            
        }
        
        return $groups;
        
    }
    
    function compare_advanced($value, $rule, $allow_all = false){
        
        if($allow_all && $value === 'all'){
    
            return true;
            
        }
    
        if($rule['operator'] === '=='){
        
            return ($value == $rule['value']);
        
        }
        
        if($rule['operator'] === '!='){
    
            return ($value != $rule['value']);
        
        }
        
        if($rule['operator'] === '<'){
    
            return ($value < $rule['value']);
        
        }
        
        if($rule['operator'] === '<='){
    
            return ($value <= $rule['value']);
        
        }
        
        if($rule['operator'] === '>'){
    
            return ($value > $rule['value']);
        
        }
        
        if($rule['operator'] === '>='){
    
            return ($value >= $rule['value']);
        
        }
        
        if($rule['operator'] === 'contains'){
    
            return (stripos($value, $rule['value']) !== false);
        
        }
        
        if($rule['operator'] === '!contains'){
    
            return (stripos($value, $rule['value']) === false);
        
        }
        
        if($rule['operator'] === 'starts'){
    
            return (stripos($value, $rule['value']) === 0);
        
        }
        
        if($rule['operator'] === '!starts'){
    
            return (stripos($value, $rule['value']) !== 0);
        
        }
        
        if($rule['operator'] === 'ends'){
    
            return (acfe_ends_with($value, $rule['value']));
        
        }
        
        if($rule['operator'] === '!ends'){
    
            return (!acfe_ends_with($value, $rule['value']));
        
        }
        
        if($rule['operator'] === 'regex'){
    
            return (preg_match('/' . $rule['value'] . '/', $value));
        
        }
        
        if($rule['operator'] === '!regex'){
    
            return (!preg_match('/' . $rule['value'] . '/', $value));
        
        }
        
        if($rule['operator'] === '=count'){
        
            return (count($value) == $rule['value']);
        
        }
    
        if($rule['operator'] === '!=count'){
        
            return (count($value) != $rule['value']);
        
        }
        
        if($rule['operator'] === '>count'){
        
            return (count($value) > $rule['value']);
        
        }
        
        if($rule['operator'] === '>=count'){
        
            return (count($value) >= $rule['value']);
        
        }
        
        if($rule['operator'] === '<count'){
        
            return (count($value) < $rule['value']);
        
        }
    
        if($rule['operator'] === '<=count'){
        
            return (count($value) <= $rule['value']);
        
        }
        
        return false;
        
    }
    
}

new acfe_location();

endif;