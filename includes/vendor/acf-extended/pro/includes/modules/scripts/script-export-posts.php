<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_script_export_posts')):

class acfe_script_export_posts extends acfe_script{
    
    /*
     * Init
     */
    function initialize(){
        
        $this->name         = 'export_posts';
        $this->title        = 'Example: Export Posts';
        $this->description  = 'Export posts of any available post type into a json file';
        $this->recursive    = true;
        $this->category     = 'Data';
        $this->author       = 'ACF Extended';
        $this->link         = 'https://www.acf-extended.com';
        $this->version      = '1.0';
        
        $this->field_groups = array(
    
            array(
                'title'             => 'Settings',
                'key'               => 'group_acfe_export_posts',
                'position'          => 'side',
                'label_placement'   => 'top',
                'fields'            => array(
            
                    array(
                        'label'         => 'Post Types',
                        'name'          => 'post_types',
                        'type'          => 'acfe_post_types',
                        'instructions'  => '',
                        'required'      => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                        'toggle'        => true,
                        'callback'      => array(
                    
                            'prepare_field' => function($field){
                        
                                $post_types = acf_get_pretty_post_types();
                                $post_types = array_keys($post_types);
                                $post_types = array_diff($post_types, array('attachment')); // Remove attachment
                        
                                $field['choices'] = acf_get_pretty_post_types($post_types);
                        
                                return $field;
                        
                            }
                
                        ),
                    ),
            
                    array(
                        'label'         => 'Posts Per Request',
                        'name'          => 'posts_per_request',
                        'type'          => 'number',
                        'instructions'  => '',
                        'required'      => true,
                        'min'           => 1,
                        'default_value' => 5,
                    ),
        
                ),
    
            ),

        );
        
        $this->data = array(
            'offset' => -1,
            'tasks'  => array(),
        );
        
    }
    
    /*
     * Start
     */
    function start(){
    
        // Cleanup transient
        delete_transient('acfe_export_posts');
    
        // Get post types
        $post_types = get_field('post_types');
    
        // Update stats
        $total = $this->count_posts($post_types);
        
        $this->stats['total'] = $total;
        $this->stats['left'] = $total;
        
        // Update tasks
        $this->data['tasks'] = $post_types;
        
        // Send response
        $this->send_response(array(
            'message' => 'Preparing tasks...',
        ));
        
    }
    
    /*
     *
     */
    function stop(){
    
        // Get transient
        $export = acf_get_array(get_transient('acfe_export_posts'));
    
        // Export empty
        if(empty($export)){
        
            // Send Response
            $this->send_response(array(
                'message' => 'No posts to export',
                'status' => 'error',
            ));
        
        }
    
        // Prepare upload
        $upload_dir = wp_upload_dir();
        $path = $upload_dir['path'];
    
        // Prepare file
        $file = untrailingslashit($path) . '/acfe_export.json';
        $url = untrailingslashit($upload_dir['url']) . '/acfe_export.json';
    
        // Folder not writable
        if(!is_writable($path)){
        
            // Send Response
            $this->send_response(array(
                'message' => "Folder <strong>{$path}</strong> not writable",
                'status' => 'error',
            ));
        
        }
    
        // Export to json
        $json = json_encode($export);
    
        $result = file_put_contents($file, $json);
    
        // File not writable
        if(!is_int($result)){
        
            // Send Response
            $this->send_response(array(
                'message' => 'File not writable',
                'status' => 'error',
            ));
        
        }
        
        // Count
        $count = count($export);
    
        // Cleanup transient
        delete_transient('acfe_export_posts');
    
        // Send response
        $this->send_response(array(
            'message'   => "Export Ready. {$count} post" . ($count > 1 ? 's' : '') . " exported",
            'status'    => 'success',
            'link'      => array(
                array(
                    'text'     => 'Download',
                    'href'     => $url,
                    'download' => '',
                ),
            ),
        ));
        
    }
    
    /*
     * Request
     */
    function request(){
        
        // Vars
        $tasks = $this->data['tasks'];
        $post_type = array_shift($tasks);
    
        // Script finished
        if(!$post_type){
    
            // Send response
            $this->send_response(array(
                'event'     => 'stop',
            ));
        
        }
    
        // Get object
        $object = get_post_type_object($post_type);
    
        // Processing
        if($this->data['offset'] === -1){
        
            // Update data
            $this->data['offset'] = 0;
        
            // Count
            $count = $this->count_posts($post_type);
        
            // Send response
            $this->send_response(array(
                'message'   => "Processing: <strong>{$object->label}</strong> ({$count} post" . ($count > 1 ? 's' : '') . ")",
                'status'    => 'success',
            ));
        
        }
    
        // Items
        $items = get_posts(array(
            'post_type'         => $post_type,
            'post_status'       => 'any',
            'posts_per_page'    => get_field('posts_per_request'),
            'fields'            => 'ids',
            'offset'            => $this->data['offset'],
        ));
    
        // Finished post type
        if(!$items){
        
            // Update data
            $this->data['offset'] = -1;
            $this->data['tasks'] = $tasks; // array_shift() already removed current object
        
            // Empty response
            $this->send_response();
        
        }
    
        // Get transient
        $export = acf_get_array(get_transient('acfe_export_posts'));
    
        // Loop posts
        foreach($items as $post_id){
        
            // vars
            $post_title = get_the_title($post_id);
            $post_permalink = get_permalink($post_id);
            $post_content = get_the_content(null, false, $post_id);
            $post_status = get_post_status($post_id);
            $post_meta = array();
        
            // init meta
            $get_meta = get_post_meta($post_id);
        
            foreach($get_meta as $meta_key => $meta_value){
            
                $post_meta[$meta_key] = maybe_unserialize($meta_value[0]);
            
            }
        
            // Generate post
            $post = array(
                'post_id'           => $post_id,
                'post_title'        => $post_title,
                'post_permalink'    => $post_permalink,
                'post_content'      => $post_content,
                'post_status'       => $post_status,
                'post_meta'         => $post_meta,
            );
        
            // Add to message
            $message[] = "<a href='" . admin_url('post.php?post=' . $post_id . '&action=edit') . "' target='_blank'>{$post_title}</a>";
        
            // Add to debug
            $debug[] = $post;
        
            // Add to transient
            $export[] = $post;
        
            // Update offset
            $this->data['offset']++;
        
        }
    
        // Update stats
        $this->stats['left'] = absint($this->stats['total'] - count($export));
    
        // Save transient
        set_transient('acfe_export_posts', $export, 43200); // 12 hours cache
        
        // Send Response
        $this->send_response(array(
            'message' => $message,
            'debug'   => $debug
        ));
        
    }
    
    /*
     * Count Posts
     */
    function count_posts($post_type){
        
        $query = new WP_Query(array(
            'post_type'         => acf_get_array($post_type),
            'post_status'       => 'any',
            'posts_per_page'    => 1,
        ));
        
        return $query->found_posts;
        
    }
    
}

acfe_register_script('acfe_script_export_posts');

endif;