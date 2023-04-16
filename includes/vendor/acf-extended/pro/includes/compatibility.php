<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_compatibility')):

class acfe_pro_compatibility{
    
    function __construct(){
        
        add_action('acf/init',                              array($this, 'init'), 98);
        add_filter('acfe/form_field_type_category',         array($this, 'form_field_type_category'));
        add_filter('wpgraphql_acf_supported_fields',        array($this, 'wpgraphql_supported_fields'));
        add_filter('wpgraphql_acf_register_graphql_field',  array($this, 'wpgraphql_register_field'), 10, 4);
        
    }
    
    function init(){
    
        $this->update_settings();
        
    }
    
    /*
     * ACF Extended: Settings
     */
    function update_settings(){
        
        // ACF Extended: 0.8.8 - renamed modules
        if(acf_get_setting('acfe/modules/dynamic_templates') !== null){
            acf_update_setting('acfe/modules/templates', acf_get_setting('acfe/modules/dynamic_templates'));
        }
        
    }
    
    /*
     * ACF Extended: 0.8.8.1 - Change Forms Field category to 'ACF'
     */
    function form_field_type_category($category){
        return 'ACF';
    }
    
    /*
     * ACF Extended: 0.8.8.2
     * WP GraphQL ACF Supported Fields
     */
    function wpgraphql_supported_fields($fields){
        
        $acfe_fields = array(
            'acfe_block_types',
            'acfe_countries',
            'acfe_currencies',
            'acfe_date_range_picker',
            'acfe_field_groups',
            'acfe_field_types',
            'acfe_fields',
            'acfe_languages',
            'acfe_menu_locations',
            'acfe_menus',
            'acfe_options_pages',
            'acfe_phone_number',
            'acfe_post_formats',
            'acfe_templates',
        );
        
        return array_merge($fields, $acfe_fields);
        
    }
    
    /*
     * ACF Extended: 0.8.8.4
     * WP GraphQL ACF Register Field
     */
    function wpgraphql_register_field($field_config, $type_name, $field_name, $config){
    
        $acf_field = isset( $config['acf_field'] ) ? $config['acf_field'] : null;
        $acf_type  = isset( $acf_field['type'] ) ? $acf_field['type'] : null;
    
        if($acf_type === 'acfe_block_types'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_countries'){
    
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_currencies'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_date_range_picker'){
    
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_field_groups'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_field_types'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_fields'){
    
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_languages'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_menu_locations'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_menus'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_options_pages'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_phone_number'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_post_formats'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }elseif($acf_type === 'acfe_templates'){
        
            $field_config['type'] = array('list_of' => 'String');
        
        }
        
        return $field_config;
        
    }
    
}

new acfe_pro_compatibility();

endif;