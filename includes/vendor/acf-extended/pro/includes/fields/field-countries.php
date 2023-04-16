<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_countries')):

class acfe_countries extends acf_field{
    
    function __construct(){
        
        $this->name = 'acfe_countries';
        $this->label = __('Countries', 'acfe');
        $this->category = 'choice';
        $this->defaults = array(
            'countries'             => array(),
            'field_type'            => 'checkbox',
            'multiple'              => 0,
            'flags'                 => 0,
            'continents'            => 0,
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
            'display_format'        => '{localized}',
            'return_format'         => 'array',
        );
        
        parent::__construct();
        
    }
    
    /*
     * Render Field Settings
     */
    function render_field_settings($field){
        
        // Encode Choices
        $field['default_value'] = acf_encode_choices($field['default_value'], false);
        
        // Allow Countries
        acf_render_field_setting($field, array(
            'label'         => __('Allow Countries','acf'),
            'instructions'  => '',
            'type'          => 'select',
            'name'          => 'countries',
            'choices'       => acfe_get_countries(array(
                'field'         => 'localized',
                'display'       => '<span class="iti__flag iti__{code}"></span>{localized}',
            )),
            'multiple'      => 1,
            'ui'            => 1,
            'allow_null'    => 1,
            'placeholder'   => __('All Countries','acf'),
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
        
        // Return Format
        acf_render_field_setting($field, array(
            'label'         => __('Return Value', 'acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'return_format',
            'layout'        => 'horizontal',
            'choices'       => array(
                'array'         => __('Country array', 'acfe'),
                'name'          => __('Country name', 'acfe'),
                'code'          => __('Country code', 'acfe'),
            ),
        ));
    
        // Localize Name
        $localized = 'Italy';
        
        if(function_exists('locale_get_display_region')){
        
            // Get Locale
            $locale = acf_get_locale();
    
            $localized = locale_get_display_region('-it', $locale);
        
        }
    
        // display format
        acf_render_field_setting($field, array(
            'label'         => __('Display Format','acf'),
            'instructions'  => __('The format displayed when editing a post','acf'),
            'type'          => 'radio',
            'name'          => 'display_format',
            'other_choice'  => 1,
            'choices'       => array(
                '{localized}'       => '<span>' . $localized . '</span><code>{localized}</code>',
                '{native} ({code})' => '<span>Italia (it)</span><code>{native} ({code})</code>',
                ''                  => '<span>Empty</span>',
                'other'             => '<span>' . __('Custom:', 'acf') . '</span>',
            )
        ));
        
        // Display Flags
        acf_render_field_setting($field, array(
            'label'         => __('Display Flags','acf'),
            'instructions'  => __('Display countries flags', 'acfe'),
            'name'          => 'flags',
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
                        'value'     => '1',
                    ),
                ),
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
        
        // Group by Continents
        acf_render_field_setting($field, array(
            'label'         => __('Group by Continents','acf'),
            'instructions'  => __('Group countries by their continent', 'acfe'),
            'name'          => 'continents',
            'type'          => 'true_false',
            'ui'            => 1,
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
        if($field['field_type'] === 'radio')
            $field['default_value'] = acfe_unarray($field['default_value']);
        
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
     * Format Value
     */
    function format_value($value, $post_id, $field){
        
        // Bail early
        if(empty($value))
            return $value;
    
        // Vars
        $is_array = is_array($value);
        $value = acf_get_array($value);
    
        // Loop
        foreach($value as &$v){
        
            // Retrieve Object
            $object = acfe_get_country($v);
        
            if(!$object || is_wp_error($object))
                continue;
        
            // Return: Object
            if($field['return_format'] === 'array'){
                
                $v = $object;
    
            // Return: Name
            }elseif($field['return_format'] === 'name'){
                
                $v = acf_maybe_get($object, 'localized');
                
            }
        
        }
        
        // Do not return array
        if(!$is_array){
            $value = acfe_unarray($value);
        }
        
        // Return
        return $value;
        
    }
    
    /*
     * Get Choices
     */
    function get_choices($field){
        
        // Default
        $args = array(
            'field'   => 'name',
            'display' => $field['display_format']
        );
    
        // Flags
        if($field['flags']){
            
            $args['prepend'] = '<span class="iti__flag iti__{code}"></span>';
            
        }
        
        // Allowed Countries
        if($field['countries']){
            
            $args['code__in'] = $field['countries'];
            $args['orderby'] = 'code__in';
            
        }
        
        // Group by Continents
        if($field['continents']){
            
            $args['groupby'] = 'continent';
            
        }
    
        // Vars
        $post_id = acfe_get_post_id();
        $field_name = $field['_name'];
        $field_key = $field['key'];
        
        // Filters
        $args = apply_filters("acfe/fields/countries/query",                    $args, $field, $post_id);
        $args = apply_filters("acfe/fields/countries/query/name={$field_name}", $args, $field, $post_id);
        $args = apply_filters("acfe/fields/countries/query/key={$field_key}",   $args, $field, $post_id);
        
        // Query
        $choices = acfe_get_countries($args);
        
        // Loop
        foreach(array_keys($choices) as $code){
            
            // Vars
            $text = $choices[$code];
            $country = acfe_get_country($code);
    
            // Filters
            $text = apply_filters("acfe/fields/countries/result",                       $text, $country, $field, $post_id);
            $text = apply_filters("acfe/fields/countries/result/name={$field_name}",    $text, $country, $field, $post_id);
            $text = apply_filters("acfe/fields/countries/result/key={$field_key}",      $text, $country, $field, $post_id);
            
            // Apply
            $choices[$code] = $text;
            
        }
    
        // Return
        return $choices;
        
    }
    
}

// initialize
acf_register_field_type('acfe_countries');

endif;