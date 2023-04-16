<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_assets')):

class acfe_pro_assets{
    
    /*
     * Construct
     */
    function __construct(){
        
        // Hooks
        add_action('init',                              array($this, 'init'));
        add_action('admin_enqueue_scripts',             array($this, 'wp_admin_enqueue_scripts'));
        add_action('acf/input/admin_enqueue_scripts',   array($this, 'acf_admin_enqueue_scripts'));
        
    }
    
    /*
     * Init
     */
    function init(){
        
        $version = ACFE_VERSION;
        $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        
        // register scripts
        wp_register_script('acf-extended-pro-input',        acfe_get_url("pro/assets/js/acfe-pro-input{$min}.js"),          array('acf-extended'),      $version);
        wp_register_script('acf-extended-pro-admin',        acfe_get_url("pro/assets/js/acfe-pro-admin{$min}.js"),          array('acf-extended'),      $version);
        wp_register_script('acf-extended-pro-field-group',  acfe_get_url("pro/assets/js/acfe-pro-field-group{$min}.js"),    array('acf-field-group'),   $version);
        
        // register styles
        wp_register_style('acf-extended-pro-input',         acfe_get_url("pro/assets/css/acfe-pro-input{$min}.css"),        array(),                    $version);
        wp_register_style('acf-extended-pro-admin',         acfe_get_url("pro/assets/css/acfe-pro-admin{$min}.css"),        array(),                    $version);
        wp_register_style('acf-extended-pro-field-group',   acfe_get_url("pro/assets/css/acfe-pro-field-group{$min}.css"),  array(),                    $version);
        
    }
    
    /*
     * WP Admin Enqueue Scripts
     */
    function wp_admin_enqueue_scripts(){
        
        // Admin
        wp_enqueue_style('acf-extended-pro-admin');
        
        // Media
        // Enqueue ACF on "New Media" Screen (Used for Post Object & Relationship inline Add/Edit features)
        if(acf_is_screen('media')){
    
            acf_enqueue_scripts();
            
        }
        
    }
    
    /*
     * ACF Admin Enqueue Scripts
     */
    function acf_admin_enqueue_scripts(){
    
        // Input
        wp_enqueue_style('acf-extended-pro-input');
        wp_enqueue_script('acf-extended-pro-input');
        
        // Field Group
        if(acf_is_screen('acf-field-group')){
    
            wp_enqueue_style('acf-extended-pro-field-group');
            wp_enqueue_script('acf-extended-pro-field-group');
            
        }
    
        // Admin
        if(is_admin()){
            
            wp_enqueue_script('acf-extended-pro-admin');
        
        }
        
    }
    
}

new acfe_pro_assets();

endif;