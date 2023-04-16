<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_dynamic_forms')):

class acfe_pro_dynamic_forms{
    
    public $post_type;
    
    /*
     * Construct
     */
    function __construct(){
        
        $this->post_type = 'acfe-form';
        
        add_filter('mce_css',                               array($this, 'mce_css'));
        add_filter('acfe/form/register',                    array($this, 'register'), 15, 3);
        add_action('acfe/form/save',                        array($this, 'save'), 15, 2);
    
        // ajax
        add_action('wp_ajax_acfe/form/shortcode',           array($this, 'ajax_shortcode'));
        add_action('wp_ajax_nopriv_acfe/form/shortcode',    array($this, 'ajax_shortcode'));
        
        $this->add_local_field_group();
        
    }
    
    /*
     * TinyMCE CSS
     */
    function mce_css($css){
        
        // shortcode preview setting
        $preview = acfe_get_setting('modules/forms/shortcode_preview');
        
        // bail early
        if($preview === false){
            return $css;
        }
        
        global $wp_styles;
        $src = array();
        $acf_styles = array('acf-global', 'acf-input', 'acf-pro-input', 'acf-extended', 'acf-extended-input', 'acf-extended-pro-input');
        
        // loop
        foreach($wp_styles->registered as $handle => $style){
            
            if(!in_array($handle, $acf_styles)) continue;
            
            // add to list
            $src[] = $style->src;
            
        }
        
        // check list
        if($src){
            
            $css .= !empty($css) ? ',' : '';
            $css .= implode(',', $src);
            
        }
        
        return $css;
        
    }
    
    /*
     * Shortcode
     */
    function ajax_shortcode(){
    
        // validate
        if(!acf_verify_ajax()) die;
    
        // get instance for ajax
        $instance = acf_get_instance('acfe_form_front');
    
        // vars
        $args = acf_maybe_get_POST('args', array());
        $form = $instance->get_form($args);
        $preview = acfe_get_setting('modules/forms/shortcode_preview');
    
        // no preview
        if(!$form || $preview === false){
            return;
        }
        
        // force array
        $preview = acf_get_array($preview);
    
        // no preview to display
        if(!empty($preview) && !in_array($form['ID'], $preview) && !in_array($form['name'], $preview)){
            return;
        }
    
        // set preview mode
        global $is_preview;
        $is_preview = true;
    
        // display form
        echo $instance->render_shortcode($form);
    
        die;
    
    }
    
    /*
     * Register
     */
    function register($register, $name, $id){
    
        // Active
        $active = get_field('acfe_form_active', $id);
        $active = $active === null ? true : $active;
    
        if(!$active)
            return false;
        
        return $register;
        
    }
    
    /*
     * Save
     */
    function save($name, $post_id){
        
        // Active
        $active = get_field('acfe_form_active', $post_id);
        $active = $active === null ? true : $active;
        
        // Update post
        wp_update_post(array(
            'ID'            => $post_id,
            'post_status'   => $active ? 'publish' : 'acf-disabled',
        ));
        
    }
    
    /*
     * Add Local Field Group
     */
    function add_local_field_group(){
    
        acf_add_local_field_group(array(
            'key' => 'group_acfe_dynamic_form_side',
            'title' => 'Form: Side',
            'acfe_display_title' => 'Active',
            'fields' => array(
                array(
                    'key' => 'field_acfe_form_active',
                    'label' => '',
                    'name' => 'acfe_form_active',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => $this->post_type,
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
        
    }
    
}

acf_new_instance('acfe_pro_dynamic_forms');

endif;