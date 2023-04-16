<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_relationship')):

class acfe_field_relationship{
    
    function __construct(){
    
        add_action('acf/render_field_settings/type=relationship',       array($this, 'field_settings'));
        add_action('acf/render_field_settings/type=post_object',        array($this, 'field_settings'));
    
        add_filter('acfe/field_wrapper_attributes/type=relationship',   array($this, 'field_wrapper'), 10, 2);
        add_filter('acfe/field_wrapper_attributes/type=post_object',    array($this, 'field_wrapper'), 10, 2);
        
        add_action('acf/render_field/type=relationship',                array($this, 'render'));
        add_action('acf/render_field/type=post_object',                 array($this, 'render'));
        
        add_action('wp_ajax_acfe/relationship/add_post',                array($this, 'relationship_ajax'));
        add_action('wp_ajax_nopriv_acfe/relationship/add_post',         array($this, 'relationship_ajax'));
        
    }
    
    function field_settings($field){
    
        acf_render_field_setting($field, array(
            'label'         => __('Allow Post Creation','acf'),
            'instructions'  => '',
            'name'          => 'acfe_add_post',
            'type'          => 'true_false',
            'ui'            => 1
        ));
    
        acf_render_field_setting($field, array(
            'label'         => __('Allow Post Edit','acf'),
            'instructions'  => '',
            'name'          => 'acfe_edit_post',
            'type'          => 'true_false',
            'ui'            => 1
        ));
        
    }
    
    function field_wrapper($wrapper, $field){
        
        if(acf_maybe_get($field, 'acfe_add_post')){
            
            $wrapper['data-acfe-add-post'] = 1;
            
        }
        
        if(acf_maybe_get($field, 'acfe_edit_post')){
            
            $wrapper['data-acfe-edit-post'] = 1;
            
        }
        
        
        return $wrapper;
        
    }
    
    function render($field){
        
        // bail early
        if(!acf_maybe_get($field, 'acfe_add_post')) return;
        
        // allowed post types
        $allowed_post_types = acf_get_array($field['post_type']);
        $post_types = acf_get_pretty_post_types($allowed_post_types);
        $post_types = array_keys($post_types);
        
        // permission
        /*
        foreach(array_keys($post_types) as $i){
            
            // vars
            $post_type = $post_types[$i];
            $post_type_object = get_post_type_object($post_type);
            
            // check user can create post
            if(!current_user_can($post_type_object->cap->create_posts)){
                
                unset($post_types[$i]);
                
            }
        
        }
        */
        
        // bail early
        if(empty($post_types)) return;
        
        // default button
        $button = array(
            'class' => 'button add-post',
            'href' => '#',
        );
        
        // only one post type
        if(count($post_types) === 1){
            
            // post type
            $post_type = reset($post_types);
            
            // href
            $button['href'] = $this->get_add_new_button_href($post_type);
            
        }
        
        ?>
        <div class="filter -add-post">
            <a <?php echo acf_esc_attrs($button); ?>><?php echo _x('Add New', 'post'); ?></a>
        </div>
        
        <script type="text-html" class="acfe-relationship-popup">
            <ul><?php
            
                // get pretty post types
                $post_types = acf_get_pretty_post_types($post_types);
                
                // loop
                foreach($post_types as $post_type => $label){
    
                    // button
                    $button = array();
                    $button['href'] = $this->get_add_new_button_href($post_type);
                    
                    ?><li><a <?php echo acf_esc_attrs($button); ?>><?php echo $label; ?></a></li><?php
                    
                }
            ?></ul>
        </script>
        <?php
    
    }
    
    function get_add_new_button_href($post_type){
    
        // href
        $href = admin_url(add_query_arg(
            array('post_type' => $post_type),
            'post-new.php'
        ));
    
        // attachment exception
        if($post_type === 'attachment'){
            $href = admin_url('media-new.php');
        }
        
        return $href;
        
    }
    
    function relationship_ajax(){
        
        // validate action
        if(!acf_verify_ajax()) die();
        
        // validate options
        $options = wp_parse_args($_POST, array(
            'field_key' => false,
            'pid' => 0,
        ));
        
        // get field
        $field = acf_get_field($options['field_key']);
        
        // field not found
        if(!$field) die();
        
        // get post
        $post = get_post($options['pid']);
        
        ?>
        <span data-id="<?php echo esc_attr($options['pid']); ?>">
            <?php echo acf_esc_html(acf_get_field_type('relationship')->get_post_title($post, $field)); ?>
        </span>
        <?php
        
        // die
        die();
        
    }
    
}

new acfe_field_relationship();

endif;