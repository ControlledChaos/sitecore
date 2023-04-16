<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_phone_number')):

class acfe_field_phone_number extends acf_field{
    
    /*
     * Construct
     */
    function __construct(){
        
        $this->name = 'acfe_phone_number';
        $this->label = __('Phone Number', 'acfe');
        $this->category = 'jquery';
        $this->defaults = array(
            'countries'             => array(),
            'preferred_countries'   => array(),
            'default_country'       => '',
            'geolocation'           => 0,
            'native'                => 0,
            'national'              => 0,
            'dropdown'              => 0,
            'dial_code'             => 0,
            'default_value'         => '',
            'placeholder'           => '',
            'return_format'         => 'number',
        );
        
        parent::__construct();
        
    }
    
    /*
     * Render Field Settings
     */
    function render_field_settings($field){
        
        // Countries
        acf_render_field_setting($field, array(
            'label'         => __('Allow Countries', 'acf'),
            'instructions'  => 'Allow only the defined countries',
            'name'          => 'countries',
            'type'          => 'acfe_countries',
            'field_type'    => 'select',
            'flags'         => true,
            'placeholder'   => __('All countries', 'acf'),
            'ui'            => true,
            'multiple'      => true,
        ));
        
        // Preferred Countries
        acf_render_field_setting($field, array(
            'label'         => __('Preferred Countries','acf'),
            'instructions'  => 'Define the countries to appear at the top of the list',
            'name'          => 'preferred_countries',
            'type'          => 'acfe_countries',
            'field_type'    => 'select',
            'flags'         => true,
            'placeholder'   => __('Select', 'acf'),
            'ui'            => true,
            'multiple'      => true,
        ));
    
        // Default Country
        acf_render_field_setting($field, array(
            'label'         => __('Default Country', 'acf'),
            'instructions'  => 'Set the initial country selection',
            'name'          => 'default_country',
            'type'          => 'acfe_countries',
            'field_type'    => 'select',
            'flags'         => true,
            'ui'            => true,
            'allow_null'    => true,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'geolocation',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                ),
            )
        ));
    
        // Geolocation
        acf_render_field_setting($field, array(
            'label'         => __('Geolocation','acf'),
            'instructions'  => 'Lookup the user\'s country based on their IP address',
            'name'          => 'geolocation',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
    
        // Native Names
        acf_render_field_setting($field, array(
            'label'         => __('Native Names','acf'),
            'instructions'  => 'Show native country names',
            'name'          => 'native',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
    
        // National Mode
        acf_render_field_setting($field, array(
            'label'         => __('National Mode','acf'),
            'instructions'  => 'Allow users to enter national numbers',
            'name'          => 'national',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditions'    => array(
                array(
                    array(
                        'field'     => 'dial_code',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                ),
            )
        ));
    
        // Dropdown
        acf_render_field_setting($field, array(
            'label'         => __('Allow Dropdown','acf'),
            'instructions'  => 'Whether or not to allow the dropdown',
            'name'          => 'dropdown',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
    
        // Separate Dial Code
        acf_render_field_setting($field, array(
            'label'         => __('Separate Dial Code','acf'),
            'instructions'  => 'Display the country dial code next to the selected flag',
            'name'          => 'dial_code',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
        
        // Default Value
        acf_render_field_setting($field, array(
            'label'         => __('Default Value','acf'),
            'instructions'  => 'Must be international number with prefix. ie: +1201-555-0123',
            'name'          => 'default_value',
            'type'          => 'text',
        ));
        
        // Placeholder
        acf_render_field_setting($field, array(
            'label'         => __('Placeholder','acf'),
            'instructions'  => 'You may use <code>{placeholder}</code> to print the country phone number placeholder',
            'name'          => 'placeholder',
            'type'          => 'text',
        ));
        
        // return_format
        acf_render_field_setting($field, array(
            'label'         => __('Return Value', 'acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'return_format',
            'layout'        => 'horizontal',
            'choices'       => array(
                'array'         => __('Phone Array', 'acf'),
                'number'        => __('Phone Number', 'acf'),
            ),
        ));
        
        // Server Validation
        if(!class_exists('libphonenumber\PhoneNumberUtil')){
        
            acf_render_field_setting($field, array(
                'label'         => __('Server Validation', 'acf'),
                'instructions'  => '',
                'type'          => 'message',
                'new_lines'     => 'br',
                'message'       => '<a href="https://github.com/giggsey/libphonenumber-for-php" target="_blank">Libphonenumber for PHP</a> was not found on your WordPress website.<br />In order to enable the server validation you must manually include the library or install the <a href="https://www.acf-extended.com/addons/acf-extended-pro-libphonenumber.zip" target="_blank">ACF Extended: Phone Number Library Addon</a>.',
            ));
        
        }
        
    }
    
    /*
     * Register Scripts
     */
    function input_admin_enqueue_scripts(){
        
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        
        wp_register_script('acfe-intl-tel-input', acfe_get_url('pro/assets/inc/intl-tel-input/intl-tel-input' . $suffix . '.js'), array('acf-input'), '17.0.0');
        wp_register_style('acfe-intl-tel-input', acfe_get_url('pro/assets/inc/intl-tel-input/intl-tel-input' . $suffix . '.css'), array(), '17.0.0');
        
        // localize
        acf_localize_data(array(
            'phoneNumberL10n' => array(
                'invalidPhoneNumber'    => _x('Invalid Phone Number',       'Phone Number JS invalidPhoneNumber',   'acfe'),
                'invalidCountry'        => _x('Invalid Country',            'Phone Number JS invalidCountry',       'acfe'),
                'phoneNumberTooShort'   => _x('Phone Number is too short',  'Phone Number JS phoneNumberTooShort',  'acfe'),
                'phoneNumberTooLong'    => _x('Phone Number is too long',   'Phone Number JS phoneNumberTooLong',   'acfe'),
            )
        ));
        
    }
    
    /*
     * Render Field
     */
    function render_field($field){
        
        // Enqueue
        wp_enqueue_script('acfe-intl-tel-input');
        wp_enqueue_style('acfe-intl-tel-input');
        
        // Div
        $div = array(
            'class'                     => "acfe-phone-number {$field['class']}",
            'data-countries'            => $field['countries'],
            'data-preferred_countries'  => $field['preferred_countries'],
            'data-default_country'      => $field['default_country'],
            'data-geolocation'          => $field['geolocation'],
            'data-native'               => $field['native'],
            'data-dropdown'             => $field['dropdown'],
            'data-dial_code'            => $field['dial_code'],
            'data-national'             => $field['national'],
            'data-placeholder'          => $field['placeholder'],
        );
        
        $value = $field['value'] === false ? '' : $field['value'];
        $hidden_value = is_array($value) ? $value : '';
        $text_value = is_array($value) ? acf_maybe_get($field['value'], 'number') : $value;
        
        // Hidden
        $hidden_input = array(
            'name'  => $field['name'],
            'value' => $hidden_value,
        );
        
        // Text
        $text_input = array(
            'type'  => 'tel',
            'class' => 'input',
            'value' => $text_value,
        );
        
        // Render
        ?>
        <div <?php echo acf_esc_attrs($div); ?>>
            <?php acf_hidden_input($hidden_input); ?>
            <?php acf_text_input($text_input); ?>
        </div>
        <?php
        
    }
    
    function validate_value($valid, $value, $field, $input){
        
        if(!$value)
            return $valid;
        
        // Check library
        if(!class_exists('libphonenumber\PhoneNumberUtil'))
            return $valid;
        
        // Check string
        if(!is_string($value))
            return __('Invalid Phone Number', 'acfe');
        
        // Decode JSON
        $value = json_decode(wp_unslash($value), true);
        
        if(!$value)
            return __('Invalid Phone Number', 'acfe');
        
        // Ensure value is an array
        $value = acf_get_array($value);
        
        // Format array
        $value = wp_parse_args($value, array(
            'number' => '',
            'country' => '',
        ));
        
        // Bail early
        if(empty($value['number']) || empty($value['country']))
            $valid = __('Invalid Phone Number', 'acfe');
        
        // Get libphonenumber instance
        $libphonenumber = libphonenumber\PhoneNumberUtil::getInstance();
        
        // Validate
        try{
        
            $number_data = $libphonenumber->parse($value['number'], $value['country']);
        
            if(!$libphonenumber->isValidNumber($number_data))
                $valid = __('Invalid Phone Number', 'acfe');
        
        }catch(libphonenumber\NumberParseException $e){
            
            $valid = __('Invalid Phone Number', 'acfe');
            
        }
        
        return $valid;
        
    }
    
    function update_value($value, $post_id, $field){
        
        // Decode JSON string
        if(is_string($value)){
            $value = json_decode(wp_unslash($value), true);
        }
    
        // Ensure value is an array.
        if($value){
    
            $value = acf_get_array($value);
            
            return wp_parse_args($value, array(
                'number' => '',
                'country' => '',
            ));
            
        }
        
        // Return
        return false;
        
    }
    
    /*
     * Format Value
     */
    function format_value($value, $post_id, $field){
    
        // Decode JSON string
        if(acfe_is_json($value)){
            $value = json_decode(wp_unslash($value), true);
        }
        
        // Number
        if($field['return_format'] === 'number'){
            $value = acf_maybe_get($value, 'number');
        }
    
        // Return
        return $value;
        
    }
    
}

// initialize
acf_register_field_type('acfe_field_phone_number');

endif;