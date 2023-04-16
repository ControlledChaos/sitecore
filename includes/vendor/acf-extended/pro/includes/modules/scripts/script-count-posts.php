<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_script_count_posts')):

class acfe_script_count_posts extends acfe_script{
    
    /*
     * Init
     */
    function initialize(){
        
        $this->name         = 'count_posts';
        $this->title        = 'Example: Count Posts';
        $this->description  = 'Count posts of all available post types';
        $this->recursive    = true;
        $this->category     = 'Statistics';
        $this->author       = 'ACF Extended';
        $this->link         = 'https://www.acf-extended.com';
        $this->version      = '1.0';
        
        $this->field_groups = array(
    
            array(
                'title'             => 'Settings',
                'key'               => 'group_acfe_count_posts',
                'position'          => 'side',
                'label_placement'   => 'top',
                'fields'            => array(
            
                    array(
                        'label'         => 'Post Types',
                        'name'          => 'post_types',
                        'type'          => 'acfe_post_types',
                        'instructions'  => '',
                        'required'      => true,
                        'toggle'        => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                    ),
                    array(
                        'label'         => 'Post Status',
                        'name'          => 'post_statuses',
                        'type'          => 'acfe_post_statuses',
                        'instructions'  => '',
                        'field_type'    => 'select',
                        'ui'            => true,
                        'multiple'      => true,
                        'return_format' => 'name',
                        'placeholder'   => 'Any'
                    ),
        
                ),
    
            ),

        );
        
        $this->data = array(
            'tasks' => array(),
        );
        
    }
    
    /*
     * Start
     */
    function start(){
    
        // Set tasks
        $this->data['tasks'] = get_field('post_types');
        
        // Send response
        $this->send_response(array(
            'message' => 'Preparing tasks...',
            'status'  => 'success',
        ));
    
    }
    
    /*
     * Stop
     */
    function stop(){
    
        // Send response
        $this->send_response(array(
            'message' => 'Script finished',
            'status'  => 'success',
        ));
        
    }
    
    /*
     * Request
     */
    function request(){
    
        // Script finished
        if(!$this->data['tasks']){
        
            // Send response
            $this->send_response(array(
                'event' => 'stop',
            ));
        
        }
    
        // Get fields
        $post_statuses = acf_get_array(get_field('post_statuses'));
        $post_type = array_shift($this->data['tasks']); // update tasks
    
        // Empty post statuses = any
        if(empty($post_statuses)){
            $post_statuses = 'any';
        }
    
        // Attachment exception
        if($post_type === 'attachment'){
            
            $post_statuses = acf_get_array($post_statuses);
            
            if(in_array('publish', $post_statuses)){
                $post_statuses = 'inherit';
            }
        }
    
        $query = new WP_Query(array(
            'post_type' => $post_type,
            'post_status' => $post_statuses,
            'posts_per_page' => 1,
        ));
    
        $count = $query->found_posts;
    
        // Post type object
        $post_type_obj = get_post_type_object($post_type);
    
        // Send response
        $this->send_response("<strong>{$post_type_obj->label}</strong>: {$count} posts");
    
    }
    
}

acfe_register_script('acfe_script_count_posts');

endif;