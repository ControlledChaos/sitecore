<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_min_max')):

class acfe_field_min_max{
    
    var $allowed_field_types = array();
    
    function __construct(){
        
        $acfe_fields_conditions = array(
            array(
                array(
                    'field'     => 'field_type',
                    'operator'  => '==',
                    'value'     => 'select'
                ),
                array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),
            array(
                array(
                    'field'     => 'field_type',
                    'operator'  => '==',
                    'value'     => 'checkbox'
                ),
            ),
        );
        
        $this->allowed_field_types = array(
            
            // ACFE: Block Types
            array(
                'field_type'        => 'acfe_block_types',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Countries
            array(
                'field_type'        => 'acfe_countries',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Currencies
            array(
                'field_type'        => 'acfe_currencies',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Field Groups
            array(
                'field_type'        => 'acfe_field_groups',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Field Types
            array(
                'field_type'        => 'acfe_field_types',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Fields
            array(
                'field_type'        => 'acfe_fields',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Forms
            array(
                'field_type'        => 'acfe_forms',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Image Sizes
            array(
                'field_type'        => 'acfe_image_sizes',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: Image Selector
            array(
                'field_type'        => 'acfe_image_selector',
                'after'             => 'multiple',
                'conditional_logic' => array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),
    
            // ACFE: Languages
            array(
                'field_type'        => 'acfe_languages',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Menu Locations
            array(
                'field_type'        => 'acfe_menu_locations',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: Menus
            array(
                'field_type'        => 'acfe_menus',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: Options Pages
            array(
                'field_type'        => 'acfe_options_pages',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Payment Cart
            array(
                'field_type'        => 'acfe_payment_cart',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Post Formats
            array(
                'field_type'        => 'acfe_post_formats',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Post Statuses
            array(
                'field_type'        => 'acfe_post_statuses',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Post Types
            array(
                'field_type'        => 'acfe_post_types',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // ACFE: Taxonomies
            array(
                'field_type'        => 'acfe_taxonomies',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: Taxonomy Terms
            array(
                'field_type'        => 'acfe_taxonomy_terms',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: Templates
            array(
                'field_type'        => 'acfe_templates',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
    
            // ACFE: User Roles
            array(
                'field_type'        => 'acfe_user_roles',
                'after'             => 'field_type',
                'conditional_logic' => $acfe_fields_conditions,
            ),
            
            // Checkbox
            array(
                'field_type'        => 'checkbox',
                'after'             => false,
                'conditional_logic' => false
            ),
            
            // Post Object
            array(
                'field_type'        => 'post_object',
                'after'             => 'multiple',
                'conditional_logic' => array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),

            // File
            array(
                'field_type'        => 'file',
                'after'             => 'multiple',
                'conditional_logic' => array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),
            
            // Select
            array(
                'field_type'        => 'select',
                'after'             => 'multiple',
                'conditional_logic' => array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),
            
            // Taxonomy
            array(
                'field_type'        => 'taxonomy',
                'after'             => 'field_type',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'     => 'field_type',
                            'operator'  => '==',
                            'value'     => 'multi_select'
                        ),
                    ),
                    array(
                        array(
                            'field'     => 'field_type',
                            'operator'  => '==',
                            'value'     => 'checkbox'
                        ),
                    ),
                ),
            ),
    
            // Select
            array(
                'field_type'        => 'user',
                'after'             => 'multiple',
                'conditional_logic' => array(
                    'field'     => 'multiple',
                    'operator'  => '==',
                    'value'     => 1
                ),
            ),
            
        );

        foreach($this->allowed_field_types as $rule){
    
            add_action("acf/render_field_settings/type={$rule['field_type']}", array($this, 'field_settings'));
            add_filter("acf/validate_value/type={$rule['field_type']}",        array($this, 'validate_value'), 10, 4);
        
        }
        
        
    }
    
    function field_settings($field){
        
        $row = array();
        
        foreach($this->allowed_field_types as $rule){
            
            if($field['type'] !== $rule['field_type'])
                continue;
            
            $row = $rule;
            break;
            
        }
        
        $min = array(
            'label'         => __('Selection restrictions','acfe'),
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'min',
            'min'           => 0,
            'prepend'       => __('Min', 'acf'),
        );
        
        $max = array(
            'label'         => '',
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'max',
            'min'           => 0,
            'prepend'       => __('Max', 'acfe'),
            '_append'       => 'min'
        );
        
        // After
        if(acf_maybe_get($row, 'after')){
        
            $min['wrapper']['data-after'] = $row['after'];
        
        }
        
        // Conditional Logic
        if(acf_maybe_get($row, 'conditional_logic')){
            
            $min['conditional_logic'] = $row['conditional_logic'];
            $max['conditional_logic'] = $row['conditional_logic'];
            
        }
        
        acf_render_field_setting($field, $min);
        acf_render_field_setting($field, $max);
        
    }
    
    function validate_value($valid, $value, $field, $input){
        
        $min = (int) acf_maybe_get($field, 'min', 0);
        $max = (int) acf_maybe_get($field, 'max', 0);
        
        if(empty($min) && empty($max))
            return $valid;
        
        //$value = acf_array($value);
        
        // Min
        if($min > 0){
    
            // min
            if(empty($value) || count((array) $value) < $min){
        
                $valid = _n('%s requires at least %s selection', '%s requires at least %s selections', $min, 'acfe');
                $valid = sprintf($valid, $field['label'], $min);
        
            }
            
        }
    
        // Max
        if($max > 0){
        
            // min
            if(count((array) $value) > $max){
            
                $valid = _n('%s allows a maximum of %s selection', '%s allows a maximum of %s selections', $max, 'acfe');
                $valid = sprintf($valid, $field['label'], $max);
            
            }
        
        }
        
        // return
        return $valid;
        
    }
    
}

new acfe_field_min_max();

endif;