<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_checkbox')):

class acfe_pro_field_checkbox{
    
    public $instance;
    
    /*
     * Construct
     */
    function __construct(){
        
        // instance
        $this->instance = $instance = acf_get_field_type('checkbox');
    
        // render field
        remove_action('acf/render_field/type=checkbox', array($instance, 'render_field'), 9);
        add_action('acf/render_field/type=checkbox',    array($this, 'render_field'), 9);
        
    }
    
    /*
     * Render Field
     */
    function render_field($field){
        
        // instance
        $instance = $this->instance;
        
        // reset vars
        $instance->_values = array();
        $instance->_all_checked = true;
        
        
        // ensure array
        $field['value'] = acf_get_array($field['value']);
        $field['choices'] = acf_get_array($field['choices']);
        
        
        // hiden input
        acf_hidden_input( array('name' => $field['name']) );
        
        
        // vars
        $li = '';
        $ul = array(
            'class' => 'acf-checkbox-list',
        );
        
        
        // append to class
        $ul['class'] .= ' ' . ($field['layout'] == 'horizontal' ? 'acf-hl' : 'acf-bl');
        $ul['class'] .= ' ' . $field['class'];
        
        
        // checkbox saves an array
        $field['name'] .= '[]';
        
        
        // choices
        if( !empty($field['choices']) ) {
            
            // choices
            $li .= $this->render_field_choices( $field );
            
            
            // toggle
            if( $field['toggle'] ) {
                $li = $this->render_field_toggle( $field ) . $li;
            }
            
        }
        
        
        // custom
        if( $field['allow_custom'] ) {
            $li .= $instance->render_field_custom( $field );
        }
        
        
        // return
        echo '<ul ' . acf_esc_attrs( $ul ) . '>' . "\n" . $li . '</ul>' . "\n";
        
    }
    
    /*
     * Render Field Toggle
     */
    function render_field_toggle($field){
    
        // instance
        $instance = $this->instance;
        
        // vars
        $atts = array(
            'type'  => 'checkbox',
            'class' => 'acf-checkbox-toggle',
            'label' => __("Toggle All", 'acf')
        );
        
        
        // custom label
        if( is_string($field['toggle']) ) {
            $atts['label'] = $field['toggle'];
        }
        
        
        // checked
        if( $instance->_all_checked ) {
            $atts['checked'] = 'checked';
        }
        
        
        // return
        return '<li>' . $this->get_checkbox_input($atts, $field) . '</li>' . "\n";
        
    }
    
    /*
     * Render Field Choices
     */
    function render_field_choices($field){
        
        // walk
        return $this->walk($field['choices'], $field);
        
    }
    
    /*
     * Walk Choices
     */
    function walk($choices = array(), $args = array(), $depth = 0){
        
        // bail ealry if no choices
        if( empty($choices) ) return '';
        
        
        // instance
        $instance = $this->instance;
        
        
        // defaults
        $args = wp_parse_args($args, array(
            'id'        => '',
            'type'      => 'checkbox',
            'name'      => '',
            'value'     => array(),
            'disabled'  => array(),
        ));
        
        
        // vars
        $html = '';
        
        
        // sanitize values for 'selected' matching
        if( $depth == 0 ) {
            $args['value'] = array_map('esc_attr', $args['value']);
            $args['disabled'] = array_map('esc_attr', $args['disabled']);
        }
        
        
        // loop
        foreach( $choices as $value => $label ) {
            
            // open
            $html .= '<li>';
            
            
            // optgroup
            if( is_array($label) ){
                
                $html .= '<ul>' . "\n";
                $html .= $this->walk( $label, $args, $depth+1 );
                $html .= '</ul>';
                
                // option
            } else {
                
                // vars
                $esc_value = esc_attr($value);
                $atts = array(
                    'id'    => $args['id'] . '-' . str_replace(' ', '-', $value),
                    'type'  => $args['type'],
                    'name'  => $args['name'],
                    'value' => $value,
                    'label' => $label,
                );
                
                
                // selected
                if( in_array( $esc_value, $args['value'] ) ) {
                    $atts['checked'] = 'checked';
                } else {
                    $instance->_all_checked = false;
                }
                
                
                // disabled
                if( in_array( $esc_value, $args['disabled'] ) ) {
                    $atts['disabled'] = 'disabled';
                }
                
                
                // store value added
                $instance->_values[] = $esc_value;
                
                
                // append
                $html .= $this->get_checkbox_input($atts, $args);
                
            }
            
            
            // close
            $html .= '</li>' . "\n";
            
        }
        
        
        // return
        return $html;
        
    }
    
    /*
     * Get Checkbox Input
     * Modified from acf_get_checkbox_input()
     */
    function get_checkbox_input($atts, $field){
        
        // Allow radio or checkbox type.
        $atts = wp_parse_args($atts, array(
            'type' => 'checkbox'
        ));
        
        // Get label.
        $label = '';
        
        if(isset($atts['label'])){
            
            $label = $atts['label'];
            unset($atts['label']);
            
        }
    
        // vars
        $value = acf_maybe_get($atts, 'value');
        $field_key = $field['key'];
        $field_type = $field['type'];
        $field_name = $field['_name'];
        $field_input = '<input ' . acf_esc_attrs($atts) . '/>';
        $choice_render = $field_input  . acf_esc_html($label);
    
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
        
        // Render.
        $checked = isset($atts['checked']);
        return '<label' . ($checked ? ' class="selected"' : '') . '>' . $choice_render . '</label>';
    }
    
}

acf_new_instance('acfe_pro_field_checkbox');

endif;