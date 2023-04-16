<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_script_orphan_meta_cleaner')):

class acfe_script_orphan_meta_cleaner extends acfe_script{
    
    /*
     * Init
     */
    function initialize(){
        
        $this->name         = 'orphan_meta_cleaner';
        $this->title        = 'Orphan Meta Cleaner';
        $this->description  = 'Clean orphan metadata from posts, terms, users and options pages';
        $this->recursive    = true;
        $this->category     = 'Maintenance';
        $this->author       = 'ACF Extended';
        $this->link         = 'https://www.acf-extended.com';
        $this->version      = '1.0';
        
        $this->field_groups = array(
    
            array(
                'title'             => 'Objects Settings',
                'key'               => 'group_acfe_orphan_meta_cleaner_top',
                'position'          => 'acf_after_title',
                'label_placement'   => 'top',
                'fields'            => array(
                    
                    array(
                        'name' => 'col-1',
                        'type' => 'acfe_column',
                        'required' => 0,
                        'columns' => 'fill',
                        'endpoint' => 0,
                        'border' => array('column'),
                    ),
            
                    array(
                        'label'         => 'Post Types',
                        'name'          => 'post_types',
                        'type'          => 'acfe_post_types',
                        'instructions'  => '',
                        'required'      => false,
                        'toggle'        => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                    ),
    
                    array(
                        'name' => 'col-2',
                        'type' => 'acfe_column',
                        'required' => 0,
                        'columns' => 'fill',
                        'endpoint' => 0,
                        'border' => array('column'),
                    ),
            
                    array(
                        'label'         => 'Taxonomies',
                        'name'          => 'taxonomies',
                        'type'          => 'acfe_taxonomies',
                        'instructions'  => '',
                        'required'      => false,
                        'toggle'        => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                        'callback'      => array(
                            'prepare_field' => function($field){
                                
                                // Exclude post format
                                unset($field['choices']['post_format']);
                                return $field;
                                
                            }
                        ),
                    ),
    
                    array(
                        'name' => 'col-3',
                        'type' => 'acfe_column',
                        'required' => 0,
                        'columns' => 'fill',
                        'endpoint' => 0,
                        'border' => array('column'),
                    ),
            
                    array(
                        'label'         => 'Users',
                        'name'          => 'users',
                        'type'          => 'acfe_user_roles',
                        'instructions'  => '',
                        'required'      => false,
                        'toggle'        => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                    ),
    
                    array(
                        'name' => 'col-4',
                        'type' => 'acfe_column',
                        'required' => 0,
                        'columns' => 'fill',
                        'endpoint' => 0,
                        'border' => array('column'),
                    ),
            
                    array(
                        'label'         => 'Options Pages',
                        'name'          => 'options_pages',
                        'type'          => 'acfe_options_pages',
                        'instructions'  => '',
                        'required'      => false,
                        'toggle'        => true,
                        'field_type'    => 'checkbox',
                        'return_format' => 'name',
                        'callback'      => array(
                            'render_field' => function($field){
        
                                $options_pages = acf_get_options_pages();
        
                                if(empty($options_pages)){
                                    echo '<em>'; _e('No options pages', 'acfe'); echo '</em>';
                                }
        
                            }
                        )
                    ),
        
                ),
    
            ),
    
            array(
                'title'             => 'Cleaner Settings',
                'key'               => 'group_acfe_orphan_meta_cleaner_side',
                'position'          => 'side',
                'label_placement'   => 'top',
                'fields'            => array(
            
                    array(
                        'label'         => 'Items Per Request',
                        'name'          => 'items_per_request',
                        'type'          => 'number',
                        'instructions'  => 'Number of posts/terms/users processed for each request',
                        'required'      => true,
                        'min'           => 1,
                        'default_value' => 50,
                    ),
    
                    array(
                        'label'         => 'Interactive Mode',
                        'name'          => 'interactive',
                        'type'          => 'true_false',
                        'instructions'  => 'Confirm meta cleanup manually',
                        'ui'            => true,
                        'default_value' => 1,
                    ),
        
                ),
    
            ),

        );
        
        $this->data = array(
            'tasks'     => array(),
            'offset'    => -1,
            'orphan'    => 0,
            'deleted'   => 0,
        );
        
    }
    
    /*
     * Admin Head
     */
    function admin_head(){
    
        ?>
        <style>
        .acf-field[data-name="post_types"],
        .acf-field[data-name="taxonomies"],
        .acf-field[data-name="users"],
        .acf-field[data-name="options_pages"]{
            max-height:205px;
            overflow-y:auto;
        }
        </style>
        <?php
    
    }
    
    /*
     * Validate
     */
    function validate(){
        
        // Get fields
        $post_types     = get_field('post_types');
        $taxonomies     = get_field('taxonomies');
        $users          = get_field('users');
        $options_pages  = get_field('options_pages');
        
        // Check not empty
        if(empty($post_types) && empty($taxonomies) && empty($users) && empty($options_pages)){
            
            // Add global error
            acfe_add_validation_error('', 'Select at least one object type to clean: Post Type, Taxonomy, User or Options Page');
        
        }
    
    }
    
    /*
     * Start
     */
    function start(){
    
        // Vars
        $total = 0;
    
        // Get fields
        $post_types     = get_field('post_types');
        $taxonomies     = get_field('taxonomies');
        $users          = get_field('users');
        $options_pages  = get_field('options_pages');
    
        // Post types
        if($post_types){
    
            // Assign task
            $this->data['tasks']['post_types'] = $post_types;
            
            // Add to total
            $total += $this->count_posts($post_types);
        
        }
    
        // Taxonomies
        if($taxonomies){
    
            // Assign task
            $this->data['tasks']['taxonomies'] = $taxonomies;
    
            // Add to total
            $total += $this->count_terms($taxonomies);
        
        }
    
        // Users
        if($users){
            
            // Assign task
            $this->data['tasks']['users'] = $users;
    
            // Add to total
            $total += $this->count_users($users);
        
        }
    
        // Options Pages
        if($options_pages){
    
            $this->data['tasks']['options_pages'] = $options_pages;
    
            // Add to total
            $total += count($options_pages);
        
        }
        
        // Update stats
        $this->stats['total'] = $total;
        $this->stats['left'] = $total;
        
        // Send Response
        $this->send_response(array(
            'message'   => 'Preparing tasks...',
            'status'    => 'success',
        ));
    
    }
    
    /*
     * Stop
     */
    function stop(){
    
        $message = __('Script finished.', 'acfe');
        $meta = $this->data['orphan'] > 0 ? "Total orphan: {$this->data['orphan']}" : __('No orphan meta found', 'acfe');
        $meta .= $this->data['orphan'] > 0 ? " - Total deleted: {$this->data['deleted']}" : "";
    
        // Send response
        $this->send_response(array(
            'message'   => "{$message} {$meta}",
            'status'    => 'success',
        ));
        
    }
    
    /*
     * Request
     */
    function request(){
        
        // Vars
        $tasks = $this->data['tasks'];
        $interactive = get_field('interactive');
        
        // Override confirm if not interactive
        if(!$interactive){
            $this->confirm = true;
        }
        
        // Script finished
        if(!$tasks){
            
            // Send response
            $this->send_response(array(
                'event' => 'stop',
            ));
        
        }
    
        // Process tasks
        foreach(array_keys($tasks) as $task){
        
            // Method
            $method = "process_{$task}";
        
            // Execute
            $this->$method();
        
        }
    
    }
    
    /*
     * Process: Post Types
     */
    function process_post_types(){
        
        // Get task
        $post_types = acf_maybe_get($this->data['tasks'], 'post_types', array());
        $post_type = array_shift($post_types);
        
        // No post type to process
        if(!$post_type) return;
    
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
                'status'    => 'info',
            ));
            
        }
    
        // Items
        $items = get_posts(array(
            'post_type'         => $post_type,
            'post_status'       => 'any',
            'posts_per_page'    => get_field('items_per_request'),
            'fields'            => 'ids',
            'offset'            => $this->data['offset'],
        ));
    
        // Finished
        if(!$items){
            
            // Update data
            $this->data['offset'] = -1;
            $this->data['tasks']['post_types'] = $post_types; // array_shift() already removed current object
        
            // Empty response
            $this->send_response();
        
        }
        
        // Temp vars
        $left = $this->stats['left'];
        $offset = $this->data['offset'];
        $orphan = $this->data['orphan'];
        $deleted = $this->data['deleted'];
        
        // Vars
        $found = array();
        
        // Loop items
        foreach($items as $item_id){
        
            // Vars
            $title = get_the_title($item_id);
            $link = admin_url("post.php?post={$item_id}&action=edit");
            
            // Meta
            $meta = $this->clean_meta($item_id, $this->confirm); // Confirm = true/false/null (confirm/cancel/doesn't exists)
            $count = count($meta);
            
            // Update vars
            $left--;
            $offset++;
            $orphan += $count;
            $deleted += $this->confirm ? $count : 0;
            
            // No orphan found
            if(!$meta) continue;
            
            $found['messages'][] = "{$object->labels->singular_name}: <a href='{$link}' target='_blank'>{$title}</a> - {$count} orphan meta found";
    
            $found['debug'][] = array(
                'post_id'   => $item_id,
                'title'     => $title,
                'orphan'    => $meta
            );
        
        }
        
        // Ask confirmation
        if($found && $this->confirm === null){
    
            $found['messages'][] = "Perform cleanup?";
    
            // Send response
            $this->send_response(array(
                'message'   => $found['messages'],
                'debug'     => $found['debug'],
                'event'     => 'confirm',
                'status'    => 'warning',
            ));
            
        }
    
        // Reset confirm
        $this->confirm = null;
        
        // Update data
        $this->stats['left'] = $left;
        $this->data['offset'] = $offset;
        $this->data['orphan'] = $orphan;
        $this->data['deleted'] = $deleted;
    
        // Empty response
        $this->send_response();
        
    }
    
    /*
     * Process: Taxonomies
     */
    function process_taxonomies(){
    
        // Get task
        $taxonomies = acf_maybe_get($this->data['tasks'], 'taxonomies', array());
        $taxonomy = array_shift($taxonomies);
    
        // Bail early
        if(!$taxonomy) return;
    
        // Get object
        $object = get_taxonomy($taxonomy);
    
        // Processing
        if($this->data['offset'] === -1){
            
            // Update data
            $this->data['offset'] = 0;
    
            // Count
            $count = $this->count_terms($taxonomy);
        
            // Send response
            $this->send_response(array(
                'message'   => "Processing: <strong>{$object->label}</strong> ({$count} term" . ($count > 1 ? 's' : '') . ")",
                'status'    => 'info',
            ));
        
        }
    
        // Items
        $items = get_terms(array(
            'taxonomy'  => $taxonomy,
            'number'    => get_field('items_per_request'),
            'fields'    => 'ids',
            'hide_empty'=> false,
            'offset'    => $this->data['offset']
        ));
    
        // Finished
        if(!$items){
            
            // Update data
            $this->data['offset'] = -1;
            $this->data['tasks']['taxonomies'] = $taxonomies; // array_shift() already removed current object
    
            // Empty response
            $this->send_response();
        
        }
    
        // Temp vars
        $left = $this->stats['left'];
        $offset = $this->data['offset'];
        $orphan = $this->data['orphan'];
        $deleted = $this->data['deleted'];
    
        // Vars
        $found = array();
    
        // Loop items
        foreach($items as $item_id){
        
            // Vars
            $term = get_term($item_id, $taxonomy);
            $title = $term->name;
            $link = admin_url("term.php?taxonomy={$taxonomy}&tag_ID={$item_id}");
            
            // Meta
            $meta = $this->clean_meta("term_{$item_id}", $this->confirm); // Confirm = true/false/null (confirm/cancel/doesn't exists)
            $count = count($meta);
    
            // Update vars
            $left--;
            $offset++;
            $orphan += $count;
            $deleted += $this->confirm ? $count : 0;
    
            // No orphan found
            if(!$meta) continue;
    
            $found['messages'][] = "{$object->labels->singular_name}: <a href='{$link}' target='_blank'>{$title}</a> - {$count} orphan meta found";
    
            $found['debug'][] = array(
                'term_id'   => $item_id,
                'name'      => $title,
                'orphan'    => $meta
            );
        
        }
    
        // Ask confirmation
        if($found && $this->confirm === null){
        
            $found['messages'][] = "Perform cleanup?";
        
            // Send response
            $this->send_response(array(
                'message'   => $found['messages'],
                'debug'     => $found['debug'],
                'event'     => 'confirm',
                'status'    => 'warning',
            ));
        
        }
    
        // Reset confirm
        $this->confirm = null;
    
        // Update data
        $this->stats['left'] = $left;
        $this->data['offset'] = $offset;
        $this->data['orphan'] = $orphan;
        $this->data['deleted'] = $deleted;
    
        // Empty response
        $this->send_response();
        
    }
    
    /*
     * Process: Users
     */
    function process_users(){
    
        // Get task
        $user_roles = acf_maybe_get($this->data['tasks'], 'users', array());
        $user_role = array_shift($user_roles);
    
        // Bail early
        if(!$user_role) return;
    
        // Get object
        global $wp_roles;
        $object = $wp_roles->roles[$user_role];
    
        // Processing
        if($this->data['offset'] === -1){
        
            // Update data
            $this->data['offset'] = 0;
    
            // Count
            $count = $this->count_users($user_role);
        
            // Send response
            $this->send_response(array(
                'message'   => "Processing: <strong>{$object['name']}</strong> ({$count} user" . ($count > 1 ? 's' : '') . ")",
                'status'    => 'info',
            ));
        
        }
        
        // Items
        $items = get_users(array(
            'number'    => get_field('items_per_request'),
            'role'      => $user_role,
            'fields'    => 'ids',
            'offset'    => $this->data['offset'],
        ));
        
        // Finished
        if(!$items){
    
            // Update data
            $this->data['offset'] = -1;
            $this->data['tasks']['users'] = $user_roles; // array_shift() already removed current object
    
            // Empty response
            $this->send_response();
            
        }
    
        // Temp vars
        $left = $this->stats['left'];
        $offset = $this->data['offset'];
        $orphan = $this->data['orphan'];
        $deleted = $this->data['deleted'];
    
        // Vars
        $found = array();
        
        // Loop items
        foreach($items as $item_id){
            
            // Vars
            $user = get_userdata($item_id);
            $title = $user->user_login;
            $link = admin_url("user-edit.php?user_id={$item_id}");
    
            // Meta
            $meta = $this->clean_meta("user_{$item_id}", $this->confirm); // Confirm = true/false/null (confirm/cancel/doesn't exists)
            $count = count($meta);
    
            // Update vars
            $left--;
            $offset++;
            $orphan += $count;
            $deleted += $this->confirm ? $count : 0;
    
            // No orphan found
            if(!$meta) continue;
    
            $found['messages'][] = "{$object['name']}: <a href='{$link}' target='_blank'>{$title}</a> - {$count} orphan meta found";
    
            $found['debug'][] = array(
                'user_id'   => $item_id,
                'login'     => $title,
                'orphan'    => $meta
            );
            
        }
    
        // Ask confirmation
        if($found && $this->confirm === null){
        
            $found['messages'][] = "Perform cleanup?";
        
            // Send Response
            $this->send_response(array(
                'message'   => $found['messages'],
                'debug'     => $found['debug'],
                'event'     => 'confirm',
                'status'    => 'warning',
            ));
        
        }
    
        // Reset confirm
        $this->confirm = null;
    
        // Update data
        $this->stats['left'] = $left;
        $this->data['offset'] = $offset;
        $this->data['orphan'] = $orphan;
        $this->data['deleted'] = $deleted;
    
        // Empty response
        $this->send_response();
        
    }
    
    /*
     * Process: Options Pages
     */
    function process_options_pages(){
    
        // Get task
        $options_pages = acf_maybe_get($this->data['tasks'], 'options_pages', array());
        
        // Bail early
        if(!$options_pages) return;
    
        // Processing
        if($this->data['offset'] === -1){
        
            // Update data
            $this->data['offset'] = 0;
    
            // Count
            $count = count($options_pages);
        
            // Send response
            $this->send_response(array(
                'message'   => "Processing: <strong>Options Pages</strong> ({$count} page" . ($count > 1 ? 's' : '') . ")",
                'status'    => 'info',
            ));
        
        }
    
        // Items
        $items = array_slice($options_pages, $this->data['offset'], get_field('items_per_request'));
        
        // Finished
        if(!$items){
    
            // Update data
            $this->data['offset'] = -1;
            $this->data['tasks']['options_pages'] = array();
    
            // Empty response
            $this->send_response();
            
        }
    
        // Temp vars
        $left = $this->stats['left'];
        $offset = $this->data['offset'];
        $orphan = $this->data['orphan'];
        $deleted = $this->data['deleted'];
    
        // Vars
        $found = array();
        
        foreach($items as $item){
            
            // Vars
            $object = acf_get_options_page($item);
            $item_id = $object['post_id'];
            $title = $object['page_title'];
            $link = admin_url("admin.php?page={$object['menu_slug']}");
    
            // Meta
            $meta = $this->clean_meta($item_id, $this->confirm); // Confirm = true/false/null (confirm/cancel/doesn't exists)
            $count = count($meta);
    
            // Update vars
            $left--;
            $offset++;
            $orphan += $count;
            $deleted += $this->confirm ? $count : 0;
    
            // No orphan found
            if(!$meta) continue;
    
            $found['messages'][] = "Options Page: {$count} orphan meta found";
    
            $found['debug'][] = array(
                'post_id'   => $item_id,
                'title'     => $title,
                'orphan'    => $meta
            );
            
        
        }
    
        // Ask confirmation
        if($found && $this->confirm === null){
        
            $found['messages'][] = "Perform cleanup?";
        
            // Send Response
            $this->send_response(array(
                'message'   => $found['messages'],
                'debug'     => $found['debug'],
                'event'     => 'confirm',
                'status'    => 'warning',
            ));
        
        }
    
        // Reset confirm
        $this->confirm = null;
    
        // Update data
        $this->stats['left'] = $left;
        $this->data['offset'] = $offset;
        $this->data['orphan'] = $orphan;
        $this->data['deleted'] = $deleted;
    
        // Empty response
        $this->send_response();
        
    }
    
    /*
     * Clean Meta
     */
    function clean_meta($post_id, $confirm = false){
        
        // Meta
        $deleted = acfe_delete_orphan_meta($post_id, $confirm);
        
        return $deleted;
        
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
    
    /*
     * Count Terms
     */
    function count_terms($taxonomy){
    
        return wp_count_terms(array(
            'taxonomy'  => acf_get_array($taxonomy),
            'number'    => 1,
            'hide_empty'=> false,
        ));
        
    }
    
    /*
     * Count Users
     */
    function count_users($user_role){
        
        $count = count_users();
        $total = 0;
        
        foreach($count['avail_roles'] as $role => $c){
        
            if(!in_array($role, acf_get_array($user_role))) continue;
            
            $total += $c;
        
        }
        
        return $total;
        
    }
    
}

acfe_register_script('acfe_script_orphan_meta_cleaner');

endif;