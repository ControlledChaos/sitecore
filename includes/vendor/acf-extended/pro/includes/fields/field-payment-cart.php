<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_payment_cart')):

class acfe_payment_cart extends acf_field{
    
    public $payment_field = false;
    
    function initialize(){
        
        $this->name = 'acfe_payment_cart';
        $this->label = __('Payment Cart', 'acfe');
        $this->category = 'E-Commerce';
        $this->defaults = array(
            'payment_field'         => '',
            'choices'               => array(),
            'default_value'         => '',
            'display_format'        => '{item} - {currency}{price}',
            'field_type'            => 'checkbox',
            'allow_null'            => 0,
            'multiple'              => 0,
            'ui'                    => 0,
            'placeholder'           => '',
            'search_placeholder'    => '',
            'layout'                => '',
            'toggle'                => 0,
        );
        
    }
    
    function render_field_settings($field){
    
        // Choices
        $field['choices'] = acf_encode_choices($field['choices']);
    
        // Default Value
        $field['default_value'] = acf_encode_choices($field['default_value'], false);
    
        // enable local
        acf_enable_filter('local');
        
        $payment_field = acf_get_field($field['payment_field']);
        
        acf_disable_filter('local');
    
        $choices = array();
        
        // add choices
        if($payment_field){
            $choices[ $field['payment_field'] ] = $this->get_field_label($payment_field);
        }
    
        // Payment Field
        acf_render_field_setting($field, array(
            'label'         => __('Payment Field', 'acfe'),
            'instructions'  => '',
            'name'          => 'payment_field',
            'type'          => 'select',
            'ui'            => 1,
            'ajax'          => 1,
            'allow_null'    => 1,
            'ajax_action'   => 'acfe/get_payment_field',
            'placeholder'   => __('Select the payment field', 'acfe'),
            'choices'       => $choices
        ));
        
        // Items
        acf_render_field_setting($field, array(
            'label'         => __('items', 'acfe'),
            'instructions'  => __('Enter each choice on a new line with the item price. For example:','acf') . '<br /><br />' . __('Item 1 : 29<br/>Item 2 : 49','acf'),
            'name'          => 'choices',
            'type'          => 'textarea',
        ));
    
        // Default Value
        acf_render_field_setting($field, array(
            'label'         => __('Default Value','acf'),
            'instructions'  => __('Enter each default value on a new line','acf'),
            'name'          => 'default_value',
            'type'          => 'textarea',
        ));
    
        // display format
        acf_render_field_setting($field, array(
            'label'         => __('Display Format','acf'),
            'instructions'  => __('The format displayed when editing a post','acf'),
            'type'          => 'radio',
            'name'          => 'display_format',
            'other_choice'  => 1,
            'choices'       => array(
                '{item} - {currency}{price}'    => '<span>Item A - 29$</span><code>{item} - {currency}{price}</code>',
                '{currency}{price} - {item}'    => '<span>29$ - Item A</span><code>{currency}{price} - {item}</code>',
                'other'                         => '<span>' . __('Custom:', 'acf') . '</span>',
            )
        ));
    
        // Field Type
        acf_render_field_setting($field, array(
            'label'         => __('Field Type', 'acfe'),
            'instructions'  => __('Field Type', 'acfe'),
            'name'          => 'field_type',
            'type'          => 'select',
            'choices'       => array(
                'checkbox'  => __('Checkbox', 'acf'),
                'radio'     => __('Radio Button', 'acf'),
                'select'    => __('Select', 'acfe'),
            ),
        ));
    
        // Select + Radio: allow_null
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
    
        // Select: multiple
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
    
        // Select: ui
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
    
        // Checkbox: layout
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
    
        // Checkbox: toggle
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
        
    }
    
    function prepare_field($field){
        
        // payment field
        $this->payment_field = $this->get_payment_field($field);
        
        // no payment field found
        if(!$this->payment_field){
            return false;
        }
    
        // get meta
        $meta = acf_get_meta(acfe_get_post_id());
    
        // loop meta
        foreach($meta as $key => $val){
        
            // find the payment field in meta
            if($val !== $this->payment_field['key']) continue;
        
            // hide field if payment value is set on current post
            return false;
        
        }
    
        // type
        $field['wrapper']['data-type'] = $field['field_type'];
        
        // return
        return $field;
        
    }
    
    function render_field($field){
    
        // settings
        $field['type'] = $field['field_type'];
        $field['other_choice'] = 0;
        $field['ajax'] = 0;
        $field['allow_custom'] = 0;
        
        // currency
        $currency = acf_maybe_get($this->payment_field, 'currency', 'USD');
        $currency = acfe_get_currency($currency, 'symbol');
    
        // loop choices
        foreach(array_keys($field['choices']) as $item){
            
            // display format
            $label = $field['display_format'];
            
            // parse template tags
            $label = str_replace('{item}', $item, $label);
            $label = str_replace('{price}', $this->get_item_price($item, $field), $label);
            $label = str_replace('{currency}', $currency, $label);
        
            // set choice
            $field['choices'][ $item ] = $label;
        
        }
        
        // render
        acf_get_field_type($field['type'])->render_field($field);
        
    }
    
    /*
     * Update Field
     */
    function update_field($field){
        
        // choices
        $field['choices'] = acf_decode_choices($field['choices']);
        
        // default value
        $field['default_value'] = acf_decode_choices($field['default_value'], true);
        
        // single line
        if(!$field['multiple'] && $field['field_type'] !== 'checkbox'){
            $field['default_value'] = acfe_unarray($field['default_value']);
        }
        
        return $field;
        
    }
    
    /*
     * Validate Value
     */
    function validate_value($valid, $value, $field, $input){
        
        // empty value
        if(empty($value)){
            return $valid;
        }
        
        // force array
        $items = acf_get_array($value);
        
        // loop items
        foreach($items as $item){
    
            // validate item
            if($this->validate_item($item, $field)) continue;
            
            // return error
            return __("This item doesn't exists. Please try again", 'acfe');
        
        }
        
        return $valid;
        
    }
    
    /*
     * Update Value
     */
    function update_value($value, $post_id, $field){
        
        // set cart data
        $this->set_cart_data($value, $field);
        
        // return value for local meta
        if(acfe_is_local_post_id($post_id)){
            return $value;
        }
        
        // do not save in admin
        if(is_admin()){
            return null;
        }
        
        // do not save
        return null;
        
    }
    
    function set_cart_data($value, $field){
    
        // items
        $items = acf_get_array($value);
    
        // get existing cart
        $cart = acf_get_form_data('acfe/payment_cart');
        $cart = acf_get_array($cart);
    
        // parse default cart
        $cart = wp_parse_args($cart, array(
            'fields' => array(),
            'items'  => array(),
            'amount' => 0,
        ));
    
        // avoid processing the same cart field twice
        if(in_array($field['key'], $cart['fields'])){
            return;
        }
    
        // add current field
        $cart['fields'][] = $field['key'];
    
        // loop items
        foreach($items as $item){
        
            // validate item
            if(!$this->validate_item($item, $field)) continue;
        
            // vars
            $name = wp_strip_all_tags($item);
            $price = $this->get_item_price($item, $field);
        
            // generate item
            $cart['items'][] = array(
                'item'  => $name,
                'price' => $price
            );
        
            // add to amount
            $cart['amount'] += $price;
        
        }
    
        // set form data for payment process update
        acf_set_form_data('acfe/payment_cart', $cart);
        
    }
    
    function validate_item($item, $field){
        
        $choices = array_keys($field['choices']);
        
        return in_array($item, $choices);
        
    }
    
    function get_item_price($item, $field){
        
        $price = 0;
        
        foreach(array_keys($field['choices']) as $key){
            
            if($key !== $item) continue;
        
            $price = $field['choices'][ $key ];
        
        }
        
        return $price;
        
    }
    
    function get_payment_field($field){
    
        // payment field already set in field
        if($field['payment_field']){
        
            // get field
            $payment_field = acf_get_field($field['payment_field']);
            
            // found field
            if($payment_field){
                return $payment_field;
            }
        
        }
    
        // retrieve payment field in the same field group
        $field_group = acfe_get_field_group_from_field($field);
    
        // get fields
        $fields = acf_get_fields($field_group['key']);
    
        // return
        return $this->find_payment_field($fields);
        
    }
    
    function find_payment_field($fields){
        
        // loop
        foreach($fields as $field){
            
            // Recursive search for sub_fields (groups & clones)
            if(acf_maybe_get($field, 'sub_fields')){
                return $this->find_payment_field($field['sub_fields']);
            }
            
            // allow only payment field
            if($field['type'] !== 'acfe_payment') continue;
            
            // return field
            return $field;
            
        }
        
        // nothing found
        return false;
        
    }
    
    function get_field_label($field){
        
        $label = acf_maybe_get($field, 'label', $field['name']);
        
        return "{$label} ({$field['key']})";
        
    }
    
}

// initialize
acf_register_field_type('acfe_payment_cart');

endif;