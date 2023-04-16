<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_column')):

class acfe_pro_field_column{
    
    function __construct(){
    
        // Instance
        $instance = acf_get_field_type('acfe_column');
    
        // Defaults
        $instance->defaults['border'] = '';
        $instance->defaults['border_endpoint'] = array('endpoint');
    
        add_filter('acf/prepare_field/name=columns',                    array($this, 'prepare_columns'), 5);
        add_action('acf/render_field_settings/type=acfe_column',        array($this, 'render_field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=acfe_column',    array($this, 'field_wrapper_attributes'), 10, 2);
        
    }
    
    function prepare_columns($field){
        
        $wrapper = acf_maybe_get($field, 'wrapper');
        
        if(!$wrapper)
            return $field;
        
        if(acf_maybe_get($wrapper, 'data-setting') !== 'acfe_column')
            return $field;
        
        $field['choices'] = array_merge(array(
            'auto' => 'Auto',
            'fill' => 'Fill',
        ), $field['choices']);
        
        return $field;
        
    }
    
    function render_field_settings($field){
    
        // border
        acf_render_field_setting($field, array(
            'label'         => __('Border', 'acf'),
            'instructions'  => '',
            'type'          => 'checkbox',
            'name'          => 'border',
            'layout'        => 'horizontal',
            'choices'       => array(
                'column'        => __('Column Border', 'acfe'),
                'fields'        => __('Fields Border', 'acfe'),
            ),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'endpoint',
                        'operator'  => '!=',
                        'value'     => '1',
                    )
                )
            )
        ));
    
        // border
        acf_render_field_setting($field, array(
            'label'         => __('Border', 'acf'),
            'instructions'  => '',
            'type'          => 'checkbox',
            'name'          => 'border_endpoint',
            'layout'        => 'horizontal',
            'choices'       => array(
                'endpoint' => __('Endpoint Border', 'acfe'),
            ),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'endpoint',
                        'operator'  => '==',
                        'value'     => '1',
                    )
                )
            )
        ));
        
    }
    
    function field_wrapper_attributes($wrapper, $field){
    
        if(is_array($field['border']) && in_array('column', $field['border'])){
        
            $wrapper['data-column-border'] = true;
        
        }
        
        if($field['endpoint'] && is_array($field['border_endpoint']) && in_array('endpoint', $field['border_endpoint'])){
        
            $wrapper['data-column-border'] = true;
        
        }
    
        if(is_array($field['border']) && in_array('fields', $field['border'])){
        
            $wrapper['data-fields-border'] = true;
        
        }
        
        return $wrapper;
        
    }
    
}

new acfe_pro_field_column();

endif;