<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_post_formats')):

class acfe_post_formats extends acf_field{
    
    function __construct(){
        
        $this->name = 'acfe_post_formats';
        $this->label = __('Post Formats', 'acfe');
        $this->category = 'WordPress';
        $this->defaults = array(
            'formats'               => array(),
            'field_type'            => 'checkbox',
            'multiple'              => 0,
            'allow_null'            => 0,
            'choices'               => array(),
            'default_value'         => '',
            'ui'                    => 0,
            'ajax'                  => 0,
            'placeholder'           => '',
            'search_placeholder'    => '',
            'layout'                => '',
            'toggle'                => 0,
            'allow_custom'          => 0,
            'other_choice'          => 0,
        );
        
        parent::__construct();
        
    }
    
    /*
     * Render Field Settings
     */
    function render_field_settings($field){
        
        // Encode Choices
        $field['default_value'] = acf_encode_choices($field['default_value'], false);
        
        // Allow Formats
        acf_render_field_setting($field, array(
            'label'         => __('Allow Formats','acf'),
            'instructions'  => '',
            'type'          => 'select',
            'name'          => 'formats',
            'choices'       => $this->get_choices(),
            'multiple'      => 1,
            'ui'            => 1,
            'allow_null'    => 1,
            'placeholder'   => __('All Formats','acf'),
        ));
        
        // Field Type
        acf_render_field_setting($field, array(
            'label'         => __('Appearance','acf'),
            'instructions'  => __('Select the appearance of this field', 'acf'),
            'type'          => 'select',
            'name'          => 'field_type',
            'choices'       => array(
                'checkbox'      => __('Checkbox', 'acf'),
                'radio'         => __('Radio Buttons', 'acf'),
                'select'        => _x('Select', 'noun', 'acf')
            )
        ));
        
        // Default Value
        acf_render_field_setting($field, array(
            'label'         => __('Default Value','acf'),
            'instructions'  => __('Enter each default value on a new line','acf'),
            'name'          => 'default_value',
            'type'          => 'textarea',
        ));
        
        // Select: Allow Null
        acf_render_field_setting($field, array(
            'label'         => __('Allow Null?','acf'),
            'instructions'  => '',
            'name'          => 'allow_null',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
        ));
        
        // Select: Multiple
        acf_render_field_setting($field, array(
            'label'         => __('Select multiple values?','acf'),
            'instructions'  => '',
            'name'          => 'multiple',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
            )
        ));
        
        // Select: UI
        acf_render_field_setting($field, array(
            'label'         => __('Stylised UI','acf'),
            'instructions'  => '',
            'name'          => 'ui',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                ),
            )
        ));
        
        // Select: Ajax
        acf_render_field_setting($field, array(
            'label'         => __('Use AJAX to lazy load choices?','acf'),
            'instructions'  => '',
            'name'          => 'ajax',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => 1,
                    ),
                ),
            )
        ));
        
        // Select: Placeholder
        acf_render_field_setting($field, array(
            'label'             => __('Placeholder','acf'),
            'instructions'      => __('Appears within the input','acf'),
            'type'              => 'text',
            'name'              => 'placeholder',
            'placeholder'       => _x('Select', 'verb', 'acf'),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '0',
                    ),
                    array(
                        'field'     => 'allow_null',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '0',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    array(
                        'field'     => 'allow_null',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
            )
        ));
        
        // Select: Search Placeholder
        acf_render_field_setting($field, array(
            'label'             => __('Search Input Placeholder','acf'),
            'instructions'      => __('Appears within the search input','acf'),
            'type'              => 'text',
            'name'              => 'search_placeholder',
            'placeholder'       => '',
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '0',
                    ),
                ),
            )
        ));
        
        // Radio: Other Choice
        acf_render_field_setting($field, array(
            'label'         => __('Other','acf'),
            'instructions'  => '',
            'name'          => 'other_choice',
            'type'          => 'true_false',
            'ui'            => 1,
            'message'       => __("Add 'other' choice to allow for custom values", 'acf'),
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
        ));
        
        // Checkbox: Layout
        acf_render_field_setting($field, array(
            'label'         => __('Layout','acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'layout',
            'layout'        => 'horizontal', 
            'choices'       => array(
                'vertical'      => __("Vertical",'acf'),
                'horizontal'    => __("Horizontal",'acf')
            ),
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio',
                    ),
                ),
            )
        ));
        
        // Checkbox: Toggle
        acf_render_field_setting($field, array(
            'label'         => __('Toggle','acf'),
            'instructions'  => __('Prepend an extra checkbox to toggle all choices','acf'),
            'name'          => 'toggle',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
            )
        ));
        
        // Checkbox: Allow Custom
        acf_render_field_setting($field, array(
            'label'         => __('Allow Custom','acf'),
            'instructions'  => '',
            'name'          => 'allow_custom',
            'type'          => 'true_false',
            'ui'            => 1,
            'message'       => __("Allow 'custom' values to be added", 'acf'),
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'checkbox',
                    ),
                ),
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'select',
                    ),
                    array(
                        'field'     => 'ui',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
    }
    
    /*
     * Update Field
     */
    function update_field($field){
        
        // Checkbox: Default Value
        $field['default_value'] = acf_decode_choices($field['default_value'], true);
        
        // Radio: Default Value
        if($field['field_type'] === 'radio'){
            
            $field['default_value'] = acfe_unarray($field['default_value']);
            
        }
        
        return $field;
        
    }
    
    /*
     * Prepare Field
     */
    function prepare_field($field){
        
        // Field Type
        $field['type'] = $field['field_type'];
        
        // Choices
        $field['choices'] = $this->get_choices($field);
    
        // Radio: Value
        if($field['field_type'] === 'radio')
            $field['value'] = acfe_unarray($field['value']);
    
        // Labels
        $field = acfe_prepare_checkbox_labels($field);
        
        // Checkbox: Allow Custom
        if(acf_maybe_get($field, 'allow_custom')){
            
            if($value = acf_maybe_get($field, 'value')){
                
                $value = acf_get_array($value);
                
                foreach($value as $v){
                    
                    if(isset($field['choices'][$v]))
                        continue;
                    
                    $field['choices'][$v] = $v;
                    
                }
                
            }
            
        }
        
        // return
        return $field;
        
    }
    
    /*
     * Get Choices
     */
    function get_choices($field = array()){
        
        // vars
        $choices = array();
        $allowed = acf_maybe_get($field, 'formats');
        
        // bail early
        if(!current_theme_supports('post-formats'))
            return $choices;
        
        // get formats
        $formats = get_theme_support('post-formats');
        $formats = reset($formats);
        
        // add 'standard' as first in list
        array_unshift($formats, 'standard');
        
        // loop
        foreach($formats as $format){
            
            // filter
            if($allowed && !in_array($format, $allowed)) continue;
            
            // add choice
            $choices[$format] = get_post_format_string($format);
            
        }
        
        // return
        return $choices;
        
    }
    
}

// initialize
acf_register_field_type('acfe_post_formats');

endif;