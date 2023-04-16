<?php

if(!defined('ABSPATH'))
    exit;

/*
 * Register Local Templates Store
 */
acf_register_store('local-templates');

/*
 * Get Local Templates
 */
function acfe_get_local_templates(){
    return acf_get_local_store('templates')->get();
}

/*
 * Get Local Template
 */
function acfe_get_local_template($name = ''){
    return acf_get_local_store('templates')->get($name);
}

/*
 * Remove Local Template
 */
function acfe_remove_local_template($name = ''){
    return acf_get_local_store('templates')->remove($name);
}

/*
 * Have Local Templates
 */
function acfe_have_local_templates() {
    return acf_get_local_store('templates')->count() ? true : false;
}

/*
 * Is Local Template
 */
function acfe_is_local_template($name = ''){
    return acf_get_local_store('templates')->has($name);
}

/*
 * Count Local Template
 */
function acfe_count_local_templates(){
    return acf_get_local_store('templates')->count();
}

/*
 * Add Local Template
 */
function acfe_add_local_template($args = array()){
    
    $args = wp_parse_args($args, array(
        'title'     => '',
        'name'      => '',
        'active'    => true,
        'values'    => array(),
        'location'  => array(),
    ));
    
    acf_get_local_store('templates')->set($args['name'], $args);
    
    return true;
    
}