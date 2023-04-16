<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_visibility')):

class acfe_field_visibility{
    
    function __construct(){
        
        // Actions
        add_action('acf/field_group/admin_head', array($this, 'admin_head'));
        
    }
 
    /*
     * Admin Head
     */
    function admin_head(){
        
        global $field_group;
        
        if(!acf_maybe_get($field_group, 'acfe_form'))
            return;
    
        add_action('acf/render_field_settings', array($this, 'render_field_visibility_settings'), 15);
        
    }
    
    /*
     * Render Field Visibility Settings
     */
    function render_field_visibility_settings($field){
        
        // Hide Field
        acf_render_field_setting($field, array(
            'label'         => __('Field Visibility', 'acfe'),
            'instructions'  => '',
            'name'          => 'hide_field',
            'prepend'       => '',
            'append'        => '',
            'type'          => 'select',
            'placeholder'   => 'Visible',
            'allow_null'    => true,
            'choices'       => array(
                'all'   => 'Hidden Everywhere',
                'admin' => 'Hidden in Administration',
                'front' => 'Hidden on Front-end',
            ),
            'wrapper' => array(
                'data-after' => 'type',
                'data-acfe-prepend' => 'Field',
            )
        ), true);
        
        // Hide Label
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'name'          => 'hide_label',
            'prepend'       => '',
            'append'        => '',
            'type'          => 'select',
            'placeholder'   => 'Visible',
            'allow_null'    => true,
            'choices'       => array(
                'all'   => 'Hidden Everywhere',
                'admin' => 'Hidden in Administration',
                'front' => 'Hidden on Front-end',
            ),
            'wrapper' => array(
                'data-acfe-prepend' => 'Label',
            ),
            '_append' => 'hide_field'
        ), true);
        
        // Hide Instructions
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'name'          => 'hide_instructions',
            'prepend'       => '',
            'append'        => '',
            'type'          => 'select',
            'placeholder'   => 'Visible',
            'allow_null'    => true,
            'choices'       => array(
                'all'   => 'Hidden Everywhere',
                'admin' => 'Hidden in Administration',
                'front' => 'Hidden on Front-end',
            ),
            'wrapper' => array(
                'data-acfe-prepend' => 'Instructions',
            ),
            '_append' => 'hide_field'
        ), true);
    
        // Hide Required
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'name'          => 'hide_required',
            'prepend'       => '',
            'append'        => '',
            'type'          => 'select',
            'placeholder'   => 'Default',
            'allow_null'    => true,
            'choices'       => array(
                'all'   => 'Disabled Everywhere',
                'admin' => 'Disabled in Administration',
                'front' => 'Disabled on Front-end',
            ),
            'wrapper' => array(
                'data-acfe-prepend' => 'Required',
            ),
            '_append' => 'hide_field'
        ), true);
        
    }
    
}

// initialize
new acfe_field_visibility();

endif;