<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_payment_hooks')):

class acfe_payment_hooks{
    
    /*
     * Construct
     */
    function __construct(){

        // handle cart
        add_filter('acfe/fields/payment/create/gateway=stripe', array($this, 'stripe_cart'), 9, 4);
        add_filter('acfe/fields/payment/create/gateway=paypal', array($this, 'paypal_cart'), 9, 4);
        add_filter('acfe/fields/payment/object',                array($this, 'payment_object'), 9, 4);
        
        // field ajax
        add_action('wp_ajax_acfe/get_payment_field',            array($this, 'ajax_get_payment_field'));
        
    }
    
    /*
     * Stripe Cart
     */
    function stripe_cart($args, $field, $gateway, $post_id){
        
        // get cart
        if(!$cart = acf_get_form_data('acfe/payment_cart')){
            return $args;
        }
        
        // vars
        $items = wp_list_pluck($cart['items'], 'item');
        $amount = $cart['amount'];
        
        // set items in stripe metadata
        $args['metadata']['items'] = implode(', ', $items);
        $args['amount'] = $amount;
        
        // return
        return $args;
        
    }
    
    /*
     * PayPal Cart
     */
    function paypal_cart($args, $field, $gateway, $post_id){
        
        // get cart
        if(!$cart = acf_get_form_data('acfe/payment_cart')){
            return $args;
        }
        
        // vars
        $items = wp_list_pluck($cart['items'], 'item');
        $amount = $cart['amount'];
        
        // set items in paypal description
        $args['description'] = implode(', ', $items);
        $args['amount'] = $amount;
        
        // return
        return $args;
        
    }
    
    /*
     * Payment Object
     */
    function payment_object($response, $field, $gateway, $post_id){
    
        // get cart
        if(!$cart = acf_get_form_data('acfe/payment_cart')){
            return $response;
        }
    
        // add cart items
        $response['items'] = acf_extract_var($cart, 'items', array());
        
        return $response;
        
    }
    
    function ajax_get_payment_field(){
        
        // validate
        if(!acf_verify_ajax()) die();
        
        // defaults
        $options = acf_parse_args($_POST, array(
            'post_id'   => 0,
            's'         => '',
            'field_key' => '',
            'paged'     => 1
        ));
        
        // results
        $response = $this->ajax_get_payment_field_results($options);
        
        // send results
        acf_send_ajax_results($response);
        
    }
    
    function ajax_get_payment_field_results($options){
        
        // vars
        $hidden = acfe_get_setting('reserved_field_groups', array());
        $choices = array();
        
        // loop field groups
        foreach(acf_get_field_groups() as $field_group){
            
            // bail early if hidden
            if(in_array($field_group['key'], $hidden)) continue;
            
            // get fields
            $fields = acf_get_fields($field_group['key']);
            
            $choices = $this->ajax_get_payment_field_choices($choices, $fields, $field_group);
            
        }
        
        // vars
        $results = array();
        $s = null;
        
        if(empty($choices)){
            
            return array(
                'results' => $results
            );
            
        }
        
        // search
        if($options['s'] !== ''){
            
            // strip slashes (search may be integer)
            $s = strval($options['s']);
            $s = wp_unslash($s);
            
        }
        
        // format results
        foreach($choices as $title => $fields){
            
            // vars
            $title = strval($title);
            $childrens = array();
            
            // loop
            foreach($fields as $key => $label){
                
                $label = strval($label);
                
                // search
                if(is_string($s) && stripos($label, $s) === false && stripos($title, $s) === false){
                    continue 2;
                }
                
                // set children
                $childrens[] = array(
                    'id'    => $key,
                    'text'  => $label,
                );
                
            }
            
            // set result
            $results[] = array(
                'text' => $title,
                'children' => $childrens
            );
            
        }
        
        // return
        return array(
            'results' => $results
        );
        
    }
    
    function ajax_get_payment_field_choices($choices, $fields, $field_group){
        
        // bail early
        if(empty($fields)){
            return $choices;
        }
        
        // loop
        foreach($fields as $field){
            
            // search for sub_fields (groups & clones)
            if(acf_maybe_get($field, 'sub_fields')){
                
                // recursive call
                return $this->ajax_get_payment_field_choices($choices, $field['sub_fields'], $field_group);
                
            }
            
            // allow only specific field
            if($field['type'] !== 'acfe_payment') continue;
            
            // set choice
            $choices[ $field_group['title'] ][ $field['key'] ] = $this->get_field_label($field);
            
        }
        
        // return
        return $choices;
        
    }
    
    function get_field_label($field){
        
        $label = acf_maybe_get($field, 'label', $field['name']);
        
        return "{$label} ({$field['key']})";
        
    }
    
}

new acfe_payment_hooks();

endif;