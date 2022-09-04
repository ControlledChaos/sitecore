<?php

if(!defined('ABSPATH'))
    exit;

/**
 * acfe_is_admin
 *
 * Check if current screen is back-end
 *
 * @return bool
 */
function acfe_is_admin(){
    
    return !acfe_is_front();
    
}

/**
 * acfe_is_front
 *
 * Check if current screen is front-end
 *
 * @return bool
 */
function acfe_is_front(){
    
    // todo: use acf_get_form_data('screen')
    
    if(!is_admin() || (is_admin() && wp_doing_ajax() && (acf_maybe_get_POST('_acf_screen') === 'acfe_form' || acf_maybe_get_POST('_acf_screen') === 'acf_form')))
        return true;
    
    return false;
    
}


/**
 * acfe_get_acf_screen_id
 *
 * Check if the current admin screen is ACF Field Group UI, ACF Tools, ACF Updates screens etc...
 *
 * @param string $page
 *
 * @return string
 */
function acfe_get_acf_screen_id($page = ''){
    
    $prefix = sanitize_title( __("Custom Fields", 'acf') );
    
    if(empty($page))
        return $prefix;
    
    return $prefix . '_page_' . $page;
    
}

/**
 * acfe_is_admin_screen
 *
 * Check if the current admin screen is ACF Field Group UI, ACF tools, ACF Updates screens etc...
 *
 * @param false $modules
 *
 * @return bool
 */
function acfe_is_admin_screen($modules = false){
    
    // bail early if not defined
    if(!function_exists('get_current_screen'))
        return false;
    
    // vars
    $screen = get_current_screen();
    
    // no screen
    if(!$screen)
        return false;
    
    $post_types = array(
        'acf-field-group',  // ACF
    );
    
    $field_group_category = false;
    
    // include ACF Extended Modules?
    if($modules){
        
        // Reserved
        $post_types = array_merge($post_types, acfe_get_setting('reserved_post_types', array()));
        
        // Field Group Category
        $field_group_category = $screen->post_type === 'post' && $screen->taxonomy === 'acf-field-group-category';
        
    }
    
    if(in_array($screen->post_type, $post_types) || $field_group_category)
        return true;
    
    return false;
    
}

/**
 * acfe_match_location_rules
 *
 * Match screen data against an array of location
 *
 * @param $location
 * @param $screen
 *
 * @return bool
 */
function acfe_match_location_rules($location, $screen){
    
    // Loop through location groups.
    foreach($location as $group){
        
        // ignore group if no rules.
        if(empty($group)) continue;
        
        // Loop over rules and determine if all rules match.
        $match_group = true;
        
        foreach($group as $rule){
            
            if(!acf_match_location_rule($rule, $screen, array())){
                $match_group = false;
                break;
            }
            
        }
        
        // Show the field group
        if($match_group) return true;
        
    }
    
    return false;
    
}

/**
 * acfe_is_dynamic_preview
 *
 * Check if currently in ACFE FlexibleContent Preview or ACF Block Type Preview
 *
 * @return bool
 */
function acfe_is_dynamic_preview(){
    
    // vars
    global $is_preview;
    $return = false;
    
    // flexible content
    if(isset($is_preview) && $is_preview){
    
        $return = true;
        
    // block type
    }elseif(wp_doing_ajax() && acf_maybe_get_POST('query')){
        
        $query = acf_maybe_get_POST('query');
        
        if(acf_maybe_get($query, 'preview')){
            $return = true;
        }
        
    }
    
    return apply_filters('acfe/is_preview', $return);
    
}

/**
 * acfe_is_block_editor
 *
 * An enhanced version of acf_is_block_editor that also check if currently in a block type
 *
 * @return bool
 */
function acfe_is_block_editor(){
    
    // check block editor screen
    if(acf_is_block_editor()){
        return true;
    }
    
    // check if a block is currently fetched (edit mode)
    if(acf_maybe_get_POST('action') === 'acf/ajax/fetch-block'){
        return true;
    }
    
    return false;
    
}


/**
 * acfe_is_gutenberg
 *
 * Check if current screen is block editor
 *
 * @return bool
 * @deprecated
 */
function acfe_is_gutenberg(){
    
    return acfe_is_block_editor();
    
}