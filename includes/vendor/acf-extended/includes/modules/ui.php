<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/ui'))
    return;

if(!class_exists('acfe_enhanced_ui')):
    
class acfe_enhanced_ui{
    
    function __construct(){
        
        $this->initialize();
        
    }
    
    function initialize(){
        // ...
    }
    
    function enqueue_scripts(){
    
        // acf
        acf_enqueue_scripts();
        
        // acfe
        wp_enqueue_style('acf-extended-ui');
        wp_enqueue_script('acf-extended-ui');
        
    }
    
    /*
     * Add Field Groups Metaboxes
     */
    function add_metaboxes($field_groups, $post_id, $screen){
    
        $postboxes = array();
    
        foreach($field_groups as $field_group){
        
            // vars
            $id = "acf-{$field_group['key']}";      // acf-group_123
            $title = $field_group['title'];         // Group 1
            $context = $field_group['position'];    // normal, side, acf_after_title
            $priority = 'high';                     // high, core, default, low
        
            // Reduce priority for sidebar metaboxes for best position.
            if($context == 'side'){
                $priority = 'core';
            }
        
            $priority = apply_filters('acf/input/meta_box_priority', $priority, $field_group);
        
            // Localize data
            $postboxes[] = array(
                'id'    => $id,
                'key'   => $field_group['key'],
                'style' => $field_group['style'],
                'label' => $field_group['label_placement'],
                'edit'  => acf_get_field_group_edit_link($field_group['ID'])
            );
        
            // Add meta box
            add_meta_box($id, $title, array($this, 'render_metabox'), $screen, $context, $priority, array('post_id' => $post_id, 'field_group' => $field_group));
        
        }
    
        // Localize postboxes.
        acf_localize_data(array(
            'postboxes' => $postboxes
        ));
    
    }
    
    /*
     * Render Metabox
     */
    function render_metabox($post, $metabox){
        
        // vars
        $post_id = $metabox['args']['post_id'];
        $field_group = $metabox['args']['field_group'];
        
        // Render fields.
        $fields = acf_get_fields($field_group);
        acf_render_fields($fields, $post_id, 'div', $field_group['instruction_placement']);
        
    }
    
    /*
     * Render Metabox Submit
     */
    function render_metabox_submit($object, $metabox){
        
        // screen
        $screen = $metabox['args'];
    
        do_action("acfe/{$screen}/submitbox_before_major_actions", $object);
        
        ?>
        <div id="major-publishing-actions">
            <div id="publishing-action"></div>
            
            <?php do_action("acfe/{$screen}/submitbox_major_actions", $object); ?>
            
            <div class="clear"></div>
        </div>
        <?php
    }

}

endif;