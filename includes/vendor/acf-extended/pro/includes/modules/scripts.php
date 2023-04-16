<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acf_get_setting('acfe/modules/scripts'))
    return;

if(!class_exists('acfe_pro_scripts')):

class acfe_pro_scripts{
    
    // vars
    var $script;
    var $has_fields = false;
    
    /*
     * Construct
     */
    function __construct(){
        
        // hooks
        add_action('admin_menu',                array($this, 'admin_menu'));
        add_action('wp_ajax_acfe/ajax/script',  array($this, 'ajax_query'));
        add_action('acf/validate_save_post',    array($this, 'validate_save_post'), 1);
        
        // scripts
        acfe_include('pro/includes/modules/scripts/script-orphan-meta-cleaner.php');
        acfe_include('pro/includes/modules/scripts/script-single-meta-converter.php');
        
        // scripts demo
        if(acfe_get_setting('modules/scripts/demo')){
            
            acfe_include('pro/includes/modules/scripts/script-count-posts.php');
            acfe_include('pro/includes/modules/scripts/script-export-posts.php');
            acfe_include('pro/includes/modules/scripts/script-import-posts.php');
            
        }
        
    }
    
    /*
     * Admin Menu
     */
    function admin_menu(){
    
        if(!acf_get_setting('show_admin'))
            return;
        
        // add page
        $page = add_management_page(__('Scripts'), __('Scripts'), acf_get_setting('capability'), 'acfe-scripts', array($this, 'html'));
    
        add_action("load-{$page}", array($this, 'load'));
    
    }
    
    /*
     * HTML
     */
    function html(){
        
        if($this->is_post()){
            
            $this->post_html();
            
        }else{
            
            $this->edit_html();
            
        }
        
    }
    
    /*
     * Load
     */
    function load(){
        
        if($this->is_post()){
            
            // get script
            $this->script = acfe_get_script($_REQUEST['script']);
            
            // bail early if doesn't exists
            if(!$this->script){
                
                wp_die(__('Sorry, you are not allowed to access this page.'), 403);
                
            }
            
            // register field groups
            foreach($this->script->field_groups as $field_group){
                
                acf_add_local_field_group($field_group);
                
                $fields = acf_maybe_get($field_group, 'fields');
                
                if($fields && count($fields)){
                    $this->has_fields = true;
                }
                
            }
            
            // verify and remove nonce
            if(acf_verify_nonce('acfe_scripts') && isset($_POST['save'])){
    
                $acf = acf_maybe_get_POST('acf', array());
    
                // Filter $_POST data for users without the 'unfiltered_html' capability.
                if(!acf_allow_unfiltered_html()){
                    $acf = wp_kses_post_deep($acf);
                }
    
                // Update Settings
                acfe_update_settings("modules.scripts.{$this->script->name}", $acf);
    
                // redirect
                wp_redirect(add_query_arg(array('message' => '1')));
                exit;
        
            }
    
            // enqueue
            acf_enqueue_scripts();
            
            // Filters
            add_action('acf/input/admin_enqueue_scripts',   array($this, 'post_enqueue_scripts'));
            add_action('acf/input/admin_head',              array($this, 'post_head'));
    
            // add columns support
            add_screen_option('layout_columns', array('max' => 2, 'default' => 2));
    
            // Execute script admin_load()
            $this->script->admin_load();
            
        }
    
    }
    
    /*
     * Post Enqueue Scripts
     */
    function post_enqueue_scripts(){
        
        //  enqueue the 'post.js' script which adds support for 'Screen Options' column toggle
        wp_enqueue_script('post');
    
        // Execute script admin_enqueue_scripts()
        $this->script->admin_enqueue_scripts();
        
    }
    
    /*
     * Post Head
     */
    function post_head(){
    
        // notices
        if(acf_maybe_get_GET('message') === '1'){
            acf_add_admin_notice('Settings saved.', 'success');
        }
    
        // add submit div
        add_meta_box('submitdiv', $this->script->title, array($this, 'postbox_submitdiv'), 'acfe_scripts', 'side', 'high');
        add_meta_box('events', 'Events', array($this, 'postbox_events'), 'acfe_scripts', 'normal', 'high');
        
        // get field groups
        $postboxes = array();
        $field_groups = array();
        
        foreach($this->script->field_groups as $field_group){
    
            $field_groups[] = acf_get_field_group($field_group['key']);
            
        }
        
        // loop
        foreach($field_groups as $field_group){
        
            // vars
            $id = "acf-{$field_group['key']}";
            $title = $field_group['title'];
            $context = $field_group['position'];
            $priority = 'high';
            $args = array('field_group' => $field_group);
    
            // Reduce priority for sidebar metaboxes for best position.
            if($context == 'side'){
                $priority = 'core';
            }
    
            // Localize data
            $postboxes[] = array(
                'id'    => $id,
                'key'   => $field_group['key'],
                'style' => $field_group['style'],
                'label' => $field_group['label_placement'],
            );
        
            // add meta box
            add_meta_box($id, acf_esc_html($title), array($this, 'postbox_acf'), 'acfe_scripts', $context, $priority, $args);
        
        }
    
        // Localize postboxes.
        acf_localize_data(array(
            'postboxes' => $postboxes
        ));
        
        // Execute script admin_head()
        $this->script->admin_head();
        
    }
    
    function postbox_submitdiv(){
    
        if($this->script->description): ?>

            <div style="padding: 15px 12px 0;">
                <p class="description"><?php echo $this->script->description; ?></p>
            </div>
    
        <?php endif; ?>

        <div style="padding:15px 12px;color:#646970;">
            <div style="margin-bottom:5px;">PHP Max Execution Time: <?php echo ini_get('max_execution_time'); ?></div>
            <div>PHP Memory Limit: <?php echo ini_get('memory_limit'); ?></div>
        </div>

        <div id="major-publishing-actions">
            
            <?php if($this->has_fields): ?>
                <button id="save-post" name="save">Save settings</button>
            <?php endif; ?>
            
            <div id="publishing-action">
                <button class="button button-large" id="stop" disabled="disabled"></button>
                <button class="button button-large" id="pause" disabled="disabled"></button>
                <button class="button button-primary button-large" id="start"></button>
            </div>

            <div class="clear"></div>

        </div>
        <?php
    
    }
    
    function postbox_events(){
        
        ?>
        <div class="events">
        
            <?php $time = current_time('H:i:s'); ?>

            <div class="event" data-status="success" data-type="ready" data-script="" data-i="" data-time="<?php echo $time; ?>">
                <div class="time"><span><?php echo $time; ?></span></div>
                <div class="message">Ready</div>
                <div class="details"></div>
            </div>

        </div>

        <div class="postbox-header postbox-footer">
            <div class="filters">
                <a href="#" class="filter" data-status="error">0</a>
                <a href="#" class="filter" data-status="warning">0</a>
                <a href="#" class="filter" data-status="info">0</a>
                <a href="#" class="filter" data-status="success">1</a>
                <a href="#" id="clear"><?php _e('Clear', 'acfe'); ?></a>
                <a href="#" id="tail" class="disabled">Tail</a>
            </div>
            <div class="stats">
                <span class="total"><?php _e('Total', 'acfe'); ?>: <span>-</span></span>
                <span class="left"><?php _e('Items left', 'acfe'); ?>: <span>-</span></span>
                <span class="timeleft"><?php _e('Time left', 'acfe'); ?>: <span>-</span></span>
                <span class="timer"><?php _e('Timer', 'acfe'); ?>: <span class="time">00:00:00</span></span>
            </div>
        </div>
        <?php
        
    }
    
    function postbox_acf($post, $metabox){
        
        // vars
        $id = $metabox['id'];
        $field_group = $metabox['args']['field_group'];
        
        // get fields
        $fields = acf_get_fields($field_group);
        
        $meta = acfe_get_settings("modules.scripts.{$this->script->name}", array());
        
        acfe_setup_meta($meta, 'acfe/scripts/load', true);
        
            // render fields
            acf_render_fields($fields, 'acfe/scripts/load', 'div', $field_group['instruction_placement']);
        
        acfe_reset_meta();
        
    }
    
    function post_html(){
    
        $atts = array(
            'id'            => 'acfe-scripts',
            'class'         => 'wrap',
            'data-script'   => $this->script->name,
        );
    
        if(!empty($_REQUEST['action']) && $_REQUEST['action'] === 'run'){
            $atts['data-run'] = 1;
        }
        
        ?>
        <div <?php echo acf_esc_attrs($atts); ?>>

            <h1><?php echo $this->script->title; ?></h1>
    
            <form id="post" method="post">
            
                <?php
                
                // render post data
                acf_form_data(array(
                    'screen' => 'acfe_scripts',
                    'script' => $this->script->name,
                ));
        
                ?>
                <div id="poststuff">
        
                    <div id="post-body" class="metabox-holder columns-2">
        
                        <!-- Sidebar -->
                        <div id="postbox-container-1" class="postbox-container">
    
                            <?php do_meta_boxes('acfe_scripts', 'side', $this->script); ?>
        
                        </div>
        
                        <!-- Metabox -->
                        <div id="postbox-container-2" class="postbox-container">
    
                            <?php do_meta_boxes('acfe_scripts', 'acf_after_title', $this->script); ?>
                            
                            <?php do_meta_boxes('acfe_scripts', 'normal', $this->script); ?>
        
                        </div>
        
                    </div>
        
                </div>
    
            </form>

        </div>
        <?php
        
    }
    
    function edit_html(){
    
        ?>
        <div class="wrap" id="acfe-scripts">

            <h1><?php _e('Scripts'); ?></h1>

            <form id="post" method="post">
            
                <?php
                
                $list = new acfe_pro_scripts_list();
                
                $list->prepare_items();
                
                $list->views();
            
                $list->display();
                
                ?>
                
            </form>
            
        </div>
        <?php
    
    }
    
    function is_post(){
        
        return isset($_REQUEST['script']) && !empty($_REQUEST['script']);
        
    }
    
    function is_edit(){
        
        return !$this->is_post();
        
    }
    
    /*
     * Ajax Query
     */
    function ajax_query(){
        
        if(!acf_verify_ajax()) die();
        
        // Defaults
        $options = acf_parse_args($_POST, array(
            'script'    => '',
            'index'     => -1,
            'type'      => 'request',
            'data'      => array(),
            'stats'     => array(),
            'confirm'   => '',
            'fields'    => array(),
        ));
        
        // Script
        $script = acfe_get_script($options['script']);
        
        // No action: error 400
        if(!$script){
            wp_send_json(false, 400);
        }
        
        $index = $options['index'];
        $type = $options['type'];
        $data = $options['data'];
        $stats = $options['stats'];
        $confirm = $options['confirm'];
        $fields = $options['fields'];
        
        // Override start data
        if($type === 'start'){
            
            //$data = $script->data;
            $stats = $script->stats;
            
        }
        
        // Orverride confirm
        if(isset($fields['confirm'])){
            $confirm = acf_extract_var($fields, 'confirm');
        }
        
        // Register field groups
        foreach($script->field_groups as $field_group){
            acf_add_local_field_group($field_group);
        }
        
        // Assign script
        $script->index = $index;
        $script->type = $type;
        $script->data = $this->validate_data($data, $script);
        $script->confirm = $this->validate_confirm($confirm, $script);
        $script->stats = $stats;
        
        // Setup Meta
        if($fields){
            acfe_setup_meta($fields, 'acfe/scripts/run', true);
        }
        
        // Execute ajax function (start/request/stop)
        $script->{$script->type}();
        
        // Reset meta
        if($fields){
            acfe_reset_meta();
        }
        
        // fallback response
        $this->ajax_response($script);
        
    }
    
    /*
     * Ajax Default Response
     */
    function ajax_response($script){
        
        // default
        $args = array(
            'type'  => $script->type,
            'data'  => $script->data,
            'stats' => $script->stats,
        );
        
        // default: start
        if($script->type === 'start'){
            
            $args['message'] = 'Start';
            $args['status'] = 'success';
            $args['event'] = 'request';
            
            // default: stop
        }elseif($script->type === 'stop'){
            
            $args['message'] = 'Stop';
            $args['status'] = 'error';
            
            // default: request
        }elseif($script->type === 'request'){
            
            $args['event'] = 'request';
            
            if(!$script->recursive){
                $args['event'] = 'stop';
            }
            
        }
        
        wp_send_json($args);
        
    }
    
    /*
     * Validate Data
     */
    function validate_data($data, $script){
        
        // Default data
        $data = wp_parse_args($data, $script->data);
        
        // validate data
        foreach(array_keys($data) as $key){
            
            // key not set in initial data
            if(!isset($script->data[ $key ])) continue;
            
            // get initial data
            $_data_key = $script->data[ $key ];
            
            // cast array
            if(is_array($_data_key)){
                
                $data[$key] = acf_get_array($data[$key]);
                
                // cast int
            }elseif(is_int($_data_key)){
                
                $data[$key] = (int) $data[$key];
                
                // cast bool
            }elseif(is_bool($_data_key)){
                
                if($data[$key] === 'false'){
                    $data[$key] = false;
                }elseif($data[$key] === 'true'){
                    $data[$key] = true;
                }
                
                $data[$key] = (bool) $data[$key];
                
                // cast string
            }elseif(is_string($_data_key)){
                
                $data[$key] = (string) $data[$key];
                
            }
            
        }
        
        return $data;
        
    }
    
    /*
     * Validate Confirm
     */
    function validate_confirm($confirm, $script){
        
        if($confirm === ''){
            
            $confirm = null;
            
        }else{
            
            $confirm = filter_var($confirm, FILTER_VALIDATE_BOOLEAN);
            
        }
        
        return $confirm;
        
    }
    
    /*
     * Validate Save Post
     */
    function validate_save_post(){
        
        // validate screen
        if(acf_maybe_get_POST('_acf_screen') !== 'acfe_scripts') return;
        
        // validate post
        if(!isset($_POST['acf'])) return;
        
        // retrieve script
        $script = acf_maybe_get_POST('_acf_script');
        $script = acfe_get_script($script);
        
        // validate script
        if(!$script) return;
        
        // register field groups
        foreach($script->field_groups as $field_group){
            
            acf_add_local_field_group($field_group);
            
        }
        
        // Setup Meta
        acfe_setup_meta($_POST['acf'], 'acfe/validate_script', true);
        
        $script->validate();
        
        // Reset meta
        acfe_reset_meta();
        
    }
    
}

acf_new_instance('acfe_pro_scripts');

endif;