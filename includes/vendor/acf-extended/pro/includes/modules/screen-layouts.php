<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/screen_layouts'))
    return;

if(!class_exists('acfe_pro_screen_layouts')):

class acfe_pro_screen_layouts{
    
    /*
     * Construct
     */
    function __construct(){
    
        add_action('load-post.php',     array($this, 'load'));
        add_action('load-post-new.php', array($this, 'load'));
        add_action('dbx_post_sidebar',  array($this, 'postbox'));
        
    }
    
    function load(){
    
        add_action('admin_head',        array($this, 'admin_head'));
        add_filter('admin_body_class',  array($this, 'body_class'));
        
    }
    
    function admin_head(){
    
        add_screen_option('layout_columns', array('max' => 6, 'default' => 2));
        
    }
    
    function body_class($classes){
        
        $classes .= ' acfe-screen-layouts';
        return $classes;
        
    }
    
    function postbox($post){
        
        echo '<div id="postbox-container-3" class="postbox-container">';
    
            do_meta_boxes($post->post_type, 'side2', $post);
        
        echo '</div>';
    
    }
    
}

acf_new_instance('acfe_pro_screen_layouts');

endif;