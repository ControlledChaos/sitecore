<?php

if(!defined('ABSPATH'))
    exit;

/*
 * Has Flexible Grid
 */
if(!function_exists('has_flexible_grid')){

function has_flexible_grid($name, $post_id = false){
    
    // get field
    $field = acf_maybe_get_field($name, $post_id);
    
    // bail early
    if(!$field)
        return false;
    
    // vars
    $flexible_grid = acf_maybe_get($field, 'acfe_flexible_grid');
    $flexible_grid_enabled = acf_maybe_get($flexible_grid, 'acfe_flexible_grid_enabled');
    
    // not enabled
    if(!$flexible_grid_enabled)
        return false;
    
    // return
    return true;
    
}

}

/*
 * Get Flexible Grid
 */
if(!function_exists('get_flexible_grid')){

function get_flexible_grid($name, $post_id = false){
    
    // bail early
    if(!has_flexible_grid($name, $post_id))
        return false;
    
    // vars
    $field = acf_maybe_get_field($name, $post_id);
    $flexible_grid = acf_maybe_get($field, 'acfe_flexible_grid');
    $flexible_grid_enabled = acf_maybe_get($flexible_grid, 'acfe_flexible_grid_enabled');
    
    // not enabled
    if(!$flexible_grid_enabled)
        return false;
    
    // return data
    return array(
        'align'     => $flexible_grid['acfe_flexible_grid_align'],
        'valign'    => $flexible_grid['acfe_flexible_grid_valign'],
        'wrap'      => $flexible_grid['acfe_flexible_grid_wrap'],
        'container' => $field['acfe_flexible_grid_container'],
    );
    
}

}

/*
 * Get Flexible Grid Class
 */
if(!function_exists('get_flexible_grid_class')){

function get_flexible_grid_class($name, $post_id = false){
    
    // get field
    $grid = get_flexible_grid($name, $post_id);
    
    // bail early
    if(!$grid)
        return false;
    
    // vars
    $class = "align-{$grid['align']} valign-{$grid['valign']}";
    $class .= $grid['wrap'] ? " wrap" : "";
    
    //return
    return $class;
    
}

}

/*
 * Get Layout Col
 */
if(!function_exists('get_layout_col')){

function get_layout_col(){
    return get_sub_field('acfe_layout_col');
}

}