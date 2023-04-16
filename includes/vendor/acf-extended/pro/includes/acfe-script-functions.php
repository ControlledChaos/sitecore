<?php

if(!defined('ABSPATH'))
    exit;

/*
 * Register Scripts Store
 */
acf_register_store('acfe-scripts');

/*
 * Get Scripts
 */
function acfe_get_scripts(){
    return acf_get_store('acfe-scripts')->get();
}

/*
 * Get Script
 */
function acfe_get_script($name = ''){
    return acf_get_store('acfe-scripts')->get($name);
}

/*
 * Remove Script
 */
function acfe_remove_script($name = ''){
    return acf_get_store('acfe-scripts')->remove($name);
}

/*
 * Have Scripts
 */
function acfe_have_scripts() {
    return acf_get_store('acfe-scripts')->count() ? true : false;
}

/*
 * Is Script
 */
function acfe_is_script($name = ''){
    return acf_get_store('acfe-scripts')->has($name);
}

/*
 * Count Script
 */
function acfe_count_scripts(){
    return acf_get_store('acfe-scripts')->count();
}

/*
 * Get Script Categories
 */
function acfe_get_scripts_categories(){
    
    $scripts = acfe_get_scripts();
    
    $categories = array();
    
    foreach($scripts as $script){
        
        if(!$script->category || in_array($script->category, $categories)) continue;
    
        $categories[] = $script->category;
        
    }
    
    return $categories;
    
}

/*
 * Register Script
 */
function acfe_register_script($class){
    
    // var
    $instance = $class;
    
    // instanciate
    if(!$instance instanceOf acfe_script){
        $instance = new $class();
    }
    
    // no name
    if(empty($instance->name)) return false;
    
    // no permission
    if(!current_user_can($instance->capability)) return false;
    
    // disabled
    if(!$instance->active) return false;
    
    // add to store
    acf_get_store('acfe-scripts')->set($instance->name, $instance);
    
    // return
    return true;
    
}