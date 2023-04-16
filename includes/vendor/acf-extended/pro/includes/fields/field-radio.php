<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_radio')):

class acfe_pro_field_radio{
    
    /*
     * Construct
     */
    function __construct(){
        
        // instance
        $instance = acf_get_field_type('radio');
        
        // render field
        remove_action('acf/render_field/type=radio',    array($instance, 'render_field'), 9);
        add_action('acf/render_field/type=radio',       array($this, 'render_field'), 9);
        
    }
    
    /*
     * Render Field
     */
    function render_field($field){
        
        // vars
        $e = '';
        $ul = array(
            'class'             => 'acf-radio-list',
            'data-allow_null'   => $field['allow_null'],
            'data-other_choice' => $field['other_choice']
        );
        
        
        // append to class
        $ul['class'] .= ' ' . ($field['layout'] == 'horizontal' ? 'acf-hl' : 'acf-bl');
        $ul['class'] .= ' ' . $field['class'];
        
        
        // select value
        $checked = '';
        $value = strval($field['value']);
        
        
        // selected choice
        if( isset($field['choices'][ $value ]) ) {
            
            $checked = $value;
            
            // custom choice
        } elseif( $field['other_choice'] && $value !== '' ) {
            
            $checked = 'other';
            
            // allow null
        } elseif( $field['allow_null'] ) {
            
            // do nothing
            
            // select first input by default
        } else {
            
            $checked = key($field['choices']);
            
        }
        
        
        // ensure $checked is a string (could be an int)
        $checked = strval($checked);
        
        
        // other choice
        if( $field['other_choice'] ) {
            
            // vars
            $input = array(
                'type'      => 'text',
                'name'      => $field['name'],
                'value'     => '',
                'disabled'  => 'disabled',
                'class'     => 'acf-disabled'
            );
            
            
            // select other choice if value is not a valid choice
            if( $checked === 'other' ) {
                
                unset($input['disabled']);
                $input['value'] = $field['value'];
                
            }
            
            
            // allow custom 'other' choice to be defined
            if( !isset($field['choices']['other']) ) {
                
                $field['choices']['other'] = '';
                
            }
            
            
            // append other choice
            $field['choices']['other'] .= '</label> <input type="text" ' . acf_esc_attr($input) . ' /><label>';
            
        }
        
        
        // bail early if no choices
        if( empty($field['choices']) ) return;
        
        
        // hiden input
        $e .= acf_get_hidden_input( array('name' => $field['name']) );
        
        
        // open
        $e .= '<ul ' . acf_esc_attr($ul) . '>';
        
        
        // foreach choices
        foreach( $field['choices'] as $value => $label ) {
            
            // ensure value is a string
            $value = strval($value);
            $class = '';
            
            
            // vars
            $atts = array(
                'type'  => 'radio',
                'id'    => sanitize_title( $field['id'] . '-' . $value ),
                'name'  => $field['name'],
                'value' => $value
            );
            
            
            // checked
            if( $value === $checked ) {
                
                $atts['checked'] = 'checked';
                $class = ' class="selected"';
                
            }
            
            
            // deisabled
            if( isset($field['disabled']) && acf_in_array($value, $field['disabled']) ) {
                
                $atts['disabled'] = 'disabled';
                
            }
            
            // vars
            $field_key = $field['key'];
            $field_type = $field['type'];
            $field_name = $field['_name'];
            $field_input = '<input ' . acf_esc_attrs($atts) . '/>';
            $choice_render = $field_input  . $label;
            
            // buffer
            ob_start();
            
            // actions
            do_action("acfe/render_choice",                     $field_input, $value, $label, $field);
            do_action("acfe/render_choice/type={$field_type}",  $field_input, $value, $label, $field);
            do_action("acfe/render_choice/name={$field_name}",  $field_input, $value, $label, $field);
            do_action("acfe/render_choice/key={$field_key}",    $field_input, $value, $label, $field);
            
            // retrieve buffer
            $buffer = ob_get_clean();
    
            // append
            if(!empty($buffer)){
                
                $choice_render = $buffer;
                
            }
            
            // render
            $e .= '<li><label' . $class . '>' . $choice_render . '</label></li>';
            
        }
        
        
        // close
        $e .= '</ul>';
        
        
        // return
        echo $e;
        
    }
    
}

acf_new_instance('acfe_pro_field_radio');

endif;