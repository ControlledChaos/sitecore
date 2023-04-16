<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_payment_selector')):

class acfe_payment_selector extends acf_field{
    
    public $payment_field = false;
    
    function initialize(){
        
        $this->name = 'acfe_payment_selector';
        $this->label = __('Payment Selector', 'acfe');
        $this->category = 'E-Commerce';
        $this->defaults = array(
            'payment_field'         => '',
            'credit_card_label'     => __('Credit Card', 'acfe'),
            'paypal_label'          => 'PayPal',
            'field_type'            => 'radio',
            'layout'                => 'horizontal',
            'ui'                    => 0,
            'icons'                 => 0,
        );
        
    }
    
    function render_field_settings($field){
    
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
    
        // Credit Card Label
        acf_render_field_setting($field, array(
            'label'         => __('Payments Labels', 'acfe'),
            'instructions'  => '',
            'name'          => 'credit_card_label',
            'prepend'       => __('Credit Card', 'acfe'),
            'type'          => 'text',
        ));
        
        // PayPal Label
        acf_render_field_setting($field, array(
            'label'         => __('PayPal Label', 'acfe'),
            'instructions'  => '',
            'name'          => 'paypal_label',
            'type'          => 'text',
            'prepend'       => 'PayPal',
            '_append'       => 'credit_card_label'
        ));
        
        // Selector Type
        acf_render_field_setting($field, array(
            'label'         => __('Field Type', 'acfe'),
            'instructions'  => __('Field Type', 'acfe'),
            'name'          => 'field_type',
            'type'          => 'select',
            'choices'       => array(
                'radio'     => __('Radio Button', 'acf'),
                'select'    => __('Select', 'acfe'),
            ),
        ));
    
        // Radio: Layout
        acf_render_field_setting($field, array(
            'label'         => __('Layout', 'acfe'),
            'instructions'  => '',
            'name'          => 'layout',
            'type'          => 'radio',
            'layout'		=> 'horizontal',
            'choices'		=> array(
                'vertical'		=> __('Vertical', 'acf'),
                'horizontal'	=> __('Horizontal', 'acf')
            ),
            'conditions'    => array(
                array(
                    'field'     => 'field_type',
                    'operator'  => '==',
                    'value'     => 'radio'
                ),
            ),
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
    
        // Icons
        acf_render_field_setting($field, array(
            'label'         => __('Icons','acf'),
            'instructions'  => '',
            'name'          => 'icons',
            'type'          => 'true_false',
            'ui'            => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'field_type',
                        'operator'  => '==',
                        'value'     => 'radio'
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
        
        // hide field if only one gateway
        if(count($this->payment_field['gateways']) === 1){
            return false;
        }
    
        // field wrapper
        $field['wrapper']['data-type'] = "acfe_payment_selector_{$field['field_type']}";
        $field['wrapper']['data-payment-field'] = $this->payment_field['key'];
    
        // icons
        if($field['icons']){
            $field['wrapper']['data-icons'] = 1;
        }
        
        // return
        return $field;
        
    }
    
    function render_field($field){
    
        // settings
        $field['type'] = $field['field_type'];
        $field['other_choice'] = 0;
        $field['ajax'] = 0;
        $field['multiple'] = 0;
        $field['allow_null'] = 0;
    
        // default choices
        $choices = array(
            'stripe' => $field['credit_card_label'],
            'paypal' => $field['paypal_label'],
        );
    
        // clone
        $_choices = array();
    
        // assign payment field gateways order
        foreach($this->payment_field['gateways'] as $gateway){
            $_choices[ $gateway ] = $choices[ $gateway ];
        }
    
        $choices = $_choices;
    
        // assign choices
        $field['choices'] = $choices;
    
        // add icons to select
        if($field['icons'] && $field['type'] === 'select' && $field['ui']){
    
            // stripe
            if(isset($field['choices']['stripe'])){
                $field['choices']['stripe'] .= '<span class="acfe-payments-icons -stripe"></span>';
            }
    
            // paypal
            if(isset($field['choices']['paypal'])){
                $field['choices']['paypal'] .= '<span class="acfe-payments-icons -paypal"></span>';
            }
        
        }
        
        acf_get_field_type($field['type'])->render_field($field);
        
    }
    
    function update_value($value, $post_id, $field){
        
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
acf_register_field_type('acfe_payment_selector');

endif;