<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_settings')):

class acfe_location_settings{
    
    function __construct(){
        
        add_action('current_screen',                        array($this, 'current_screen'));
        add_action('load-options.php',                      array($this, 'load_options'));
        add_action('load-options-permalink.php',            array($this, 'load_options'));
        
        add_filter('acf/location/rule_types',               array($this, 'location_types'));
        add_filter('acf/location/rule_values/wp_settings',  array($this, 'location_values'));
        add_filter('acf/location/rule_match/wp_settings',   array($this, 'location_match'), 10, 3);
        
    }
    
    function load_options(){
        
        // Nonce
        if(!acf_verify_nonce('wp_settings'))
            return;
        
        $post_id = acf_maybe_get_POST('_acf_post_id');
        
        if(!$post_id)
            return;
    
        $post_id = acf_get_valid_post_id($post_id);
        
        // Validate
        if(!acf_validate_save_post(true))
            return;
            
        // Autoload
        acf_update_setting('autoload', false);
        
        // Save
        acf_save_post($post_id);
        
    }
    
    function current_screen(){
        
        if(!acf_is_screen(array('options-general', 'options-writing', 'options-reading', 'options-discussion', 'options-media', 'options-permalink')))
            return;
        
        $screen = get_current_screen()->id;
    
        $field_groups = acf_get_field_groups(array(
            'wp_settings' => $screen
        ));
    
        if(empty($field_groups))
            return;
    
        // Enqueue ACF JS
        acf_enqueue_scripts();
    
        add_action('in_admin_header', array($this, 'in_admin_header'));
        
    }
    
    function in_admin_header(){
        
        acf_enqueue_uploader();
        
    }
    
    function location_types($choices){
        
        $name = __('Forms', 'acf');
        
        $choices[$name] = acfe_array_insert_after('options_page', $choices[$name], 'wp_settings', __('WP Settings'));

        return $choices;
        
    }
    
    function location_values($choices){
        
        $choices = array(
            'all'                   => __('All', 'acf'),
            'options-general'       => _x('General', 'settings screen'),
            'options-writing'       => __('Writing'),
            'options-reading'       => __('Reading'),
            'options-discussion'    => __('Discussion'),
            'options-media'         => __('Media'),
            'options-permalink'     => __('Permalinks')
        );
        
        return $choices;
        
    }
    
    function location_match($match, $rule, $screen){
        
        if(!acf_maybe_get($screen, 'wp_settings') || !acf_maybe_get($rule, 'value'))
            return $match;
        
        $match = ($screen['wp_settings'] === $rule['value']);
        
        if($rule['value'] === 'all')
            $match = true;
        
        if($rule['operator'] === '!=')
            $match = !$match;
        
        return $match;

    }
    
}

new acfe_location_settings();

endif;