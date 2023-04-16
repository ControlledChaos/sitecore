<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_dynamic_taxonomies')):

class acfe_pro_dynamic_taxonomies{
    
    public $post_type;
    
    /*
     * Construct
     */
    function __construct(){
        
        $this->post_type = 'acfe-dt';
        
        add_filter('acfe/taxonomy/register',        array($this, 'register'), 15, 2);
        add_filter('acfe/taxonomy/save_args',       array($this, 'save_args'), 15, 3);
        add_action('acfe/taxonomy/save',            array($this, 'save'), 15, 3);
        add_action('acfe/taxonomy/import_fields',   array($this, 'import_fields'), 15, 3);
        
        $this->add_local_field_group();
        
    }
    
    /*
     * Register
     */
    function register($args, $name){
    
        // Check Active
        if(!acf_maybe_get($args, 'active', true))
            return false;
        
        return $args;
        
    }
    
    /*
     * Save Args
     */
    function save_args($args, $name, $post_id){
    
        // Active
        $active = get_field('acfe_dt_active', $post_id);
        $active = $active === null ? true : $active;
    
        $args['active'] = $active;
        
        return $args;
        
    }
    
    /*
     * Save
     */
    function save($name, $args, $post_id){
    
        // Update post
        wp_update_post(array(
            'ID'            => $post_id,
            'post_status'   => $args['active'] ? 'publish' : 'acf-disabled',
        ));
        
    }
    
    /*
     * Import Fields
     */
    function import_fields($name, $args, $post_id){
        
        update_field('acfe_dt_active', $args['active'], $post_id);
        
    }
    
    /*
     * Add Local Field Group
     */
    function add_local_field_group(){
    
        acf_add_local_field_group(array(
            'key' => 'group_acfe_dynamic_taxonomy_side',
            'title' => 'Taxonomy: Side',
            'acfe_display_title' => 'Active',
            'fields' => array(
                array(
                    'key' => 'field_acfe_dt_active',
                    'label' => '',
                    'name' => 'acfe_dt_active',
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

acf_new_instance('acfe_pro_dynamic_taxonomies');

endif;