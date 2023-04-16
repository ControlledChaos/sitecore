<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_dynamic_templates')):

class acfe_dynamic_templates extends acfe_dynamic_module{
    
    /*
     * Initialize
     */
    function initialize(){
        
        $this->active = acf_get_setting('acfe/modules/templates');
        $this->post_type = 'acfe-template';
        $this->label = 'Template Title';
        
        $this->tool = 'acfe_dynamic_templates_export';
        $this->tools = array('php', 'json');
        $this->columns = array(
            'locations'     => __('Locations', 'acf'),
            'field_groups'  => __('Field groups', 'acf'),
            'fields'        => __('Fields', 'acf'),
        );
        
    }
    
    /*
     * Actions
     */
    function actions(){
    
        // Actions
        add_action('acfe/template/save',                        array($this, 'save'), 10, 2);
        add_action('acfe/template/import_fields',               array($this, 'import_fields'), 10, 3);
        add_filter('acf/validate_value',                        array($this, 'validate_values'), 99, 4);
        add_action('acf/admin_head',                            array($this, 'local_template_load_target'));
    
        // Locations
        add_filter('acf/location/rule_types',                   array($this, 'location_types'));
        add_filter('acf/location/rule_operators/acfe_template', array($this, 'location_operators'), 10, 2);
        add_filter('acf/location/rule_values/acfe_template',    array($this, 'location_values'));
        add_filter('acf/location/rule_match/acfe_template',     array($this, 'location_match_target'), 10, 4);
        add_filter('acf/location/rule_match',                   array($this, 'location_match_template'), 99, 4);
    
        // Field Groups
        add_filter('acf/validate_field_group',                  array($this, 'validate_field_group'), 20, 1);
        
    }
    
    /*
     * Get Name
     */
    function get_name($post_id){
        
        return get_post_field('post_name', $post_id);
        
    }
    
    /*
     * Init
     */
    function init(){
    
        register_post_type($this->post_type, array(
            'label'                 => __('Templates', 'acfe'),
            'description'           => __('Templates', 'acfe'),
            'labels'                => array(
                'name'          => __('Templates', 'acfe'),
                'singular_name' => __('Template', 'acfe'),
                'menu_name'     => __('Templates', 'acfe'),
                'edit_item'     => __('Edit Template', 'acfe'),
                'add_new_item'  => __('New Template', 'acfe'),
            ),
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=acf-field-group',
            'menu_icon'             => 'dashicons-feedback',
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => false,
            'has_archive'           => false,
            'rewrite'               => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capabilities'          => array(
                'publish_posts'         => acf_get_setting('capability'),
                'edit_posts'            => acf_get_setting('capability'),
                'edit_others_posts'     => acf_get_setting('capability'),
                'delete_posts'          => acf_get_setting('capability'),
                'delete_others_posts'   => acf_get_setting('capability'),
                'read_private_posts'    => acf_get_setting('capability'),
                'edit_post'             => acf_get_setting('capability'),
                'delete_post'           => acf_get_setting('capability'),
                'read_post'             => acf_get_setting('capability'),
            ),
            'acfe_admin_orderby'    => 'title',
            'acfe_admin_order'      => 'ASC',
            'acfe_admin_ppp'        => 999,
        ));
    
    }
    
    /*
     * Validate Values
     */
    function validate_values($valid, $value, $field, $input){
        
        $post_id = acfe_get_post_id();
        
        if(!$post_id || get_post_type($post_id) !== $this->post_type)
            return $valid;
        
        return true;
        
    }
    
    /*
     * Post New Load
     */
    function post_new_load(){
    
        $this->add_post_metaboxes();
        
    }
    
    /*
     * Post Load
     */
    function post_load(){
    
        $this->add_post_metaboxes();
    
        // Disable Required for Templates
        add_filter('acf/prepare_field', function($field){
            
            $field['required'] = 0;
            return $field;
        
        });
        
        // Disable Field Groups Styles
        add_filter('acf/get_field_group_style', '__return_empty_string', 10, 2);
        
    }
    
    /*
     * Post Metaboxes
     */
    function add_post_metaboxes(){
    
        // After Title
        add_action('edit_form_after_title', array($this, 'edit_form_after_title'));
    
        // Metaboxes
        add_action('acf/add_meta_boxes', array($this, 'add_metaboxes'), 10, 3);
        
    }
    
    /*
     * Edit Form After Title
     */
    function edit_form_after_title(){
        
        echo '<div class="notice notice-warning inline"><p>' . __('You are currently editing a Dynamic Template.', 'acfe') . '</p></div>';
        
    }
    
    /*
     * Add Metaboxes
     */
    function add_metaboxes($post_type, $post, $field_groups){
        
        // Bypass Sidebar Settings
        foreach($field_groups as $k => $field_group){
            
            if($field_group['key'] !== 'group_acfe_dynamic_template_side')
                continue;
            
            unset($field_groups[$k]);
            break;
            
        }
        
        $postboxes = array();
        
        // No Field Groups
        if(empty($field_groups)){
            
            // vars
            $id = 'acfe-template-no-field-group';
            $title = __('Instructions', 'acfe');
            $context = 'normal';
            $priority = 'default';
            
            // Localize data
            $postboxes[] = array(
                'id'    => $id,
                'key'   => '',
                'style' => 'default',
                'label' => 'left'
            );
            
            // Add Instructions
            add_meta_box($id, $title, array($this, 'render_meta_box_instructions'), $post_type, $context, $priority, array());
            
        // Field Groups
        }else{
            
            foreach($field_groups as $field_group){
                
                // vars
                $id = "acfe-template-rules-{$field_group['key']}";
                $title = $field_group['title'];
    
                // Rules
                $postboxes[] = array(
                    'id'    => $id,
                    'key'   => '',
                    'style' => 'default',
                    'label' => 'top',
                    'edit'  => acf_get_field_group_edit_link($field_group['ID'])
                );
    
                add_meta_box($id, $title, array($this, 'render_meta_box_rules'), $post_type, 'side', 'core', array('field_groups' => array($field_group)));
                
            }
            
        }
        
        // Add Postbox Javascript
        if($postboxes){
            
            $data = acf_get_instance('ACF_Assets')->data;
            $acf_postboxes = acf_maybe_get($data, 'postboxes', array());
            $acf_postboxes = array_merge($acf_postboxes, $postboxes);
            
            // Localize postboxes.
            acf_localize_data(array(
                'postboxes' => $acf_postboxes
            ));
            
        }
        
    }
    
    /*
     * Render Metabox: Rules
     */
    function render_meta_box_rules($post, $metabox){
        
        // vars
        $post_id = $this->validate_post_id($post->ID);
        $field_groups = $metabox['args']['field_groups'];
        $groups = $this->get_target_locations($field_groups, $post_id);
        
        ?>
        
        <?php foreach($groups as $group){ ?>
            <div class="acf-field">
                <div class="acf-input">
                    
                    <?php foreach($group as $rule){ ?>
    
                        <ul style="list-style:square inside;margin:0;">
                            
                            <?php
                            // Location
                            $location = acf_get_location_rule($rule['param']);
                            $location = isset($location->label) ? $location->label : ucfirst(str_replace(array('_', '-'), ' ', $rule['param']));
                            
                            // Operator
                            $operators = acf_get_location_rule_operators($rule);
                            $operator = $operators[$rule['operator']];
                            
                            // Value
                            $values = acf_get_location_rule_values($rule);
                            $value = isset($values[$rule['value']]) ? $values[$rule['value']] : $rule['value'];
                            ?>
    
                            <li style="margin:0;"><strong><?php echo $location; ?></strong> <?php echo $operator; ?> <strong><?php echo $value; ?></strong><br /></li>
    
    
                        </ul>
                        
                    <?php } ?>

                </div>
            </div>
        <?php } ?>
        
        <?php
        
    }
    
    /*
     * Render Metabox Instructions
     */
    function render_meta_box_instructions($post, $metabox){
        
        ?>
        <div class="acf-field">
            <div class="acf-label">
                <label>How it works</label>
            </div>
            <div class="acf-input">

                <p style="margin-top:0;">The Dynamic Templates module let you manage default ACF values in an advanced way. In order to start, you need to connect a field group to a specific template. Head over the Field Groups administration, select the field group of your choice and scroll down to the location settings. To connect a field group to a template, choose a classic location (like Post Type = Post) and add a new rule using the “AND” operator. Select the rule "Dynamic Template" under "Forms", then choose your template and save the field group.</p>

                <p>You can now fill up the template page, values will be automatically loaded for the location it is tied to if the user never saved anything. In this screenshot, there is a different template for the "Post Type: Page" & the "Post Type: Post" while using the same field group.</p>

                <p>The Dynamic Template design is smart enough to fulfill complex scenarios. For example, one single template can be used in conjunction with as many field group location as needed. It is also possible to add multiple field groups into a single template to keep things organized.</p>

                <p><u>Note:</u> Template values will be loaded when the user haven't saved any data related to the said values. Typically in a "New Post" situation. If the user save a value, even an empty one, the template won't be loaded.</p>

                <div style="margin-top:25px;">
                    <img src="<?php echo acfe_get_url('pro/assets/images/dynamic-template-instructions.jpg'); ?>" style="width:100%; height:auto;" />
                </div>

            </div>
        </div>
        <?php
        
    }
    
    /*
     * Edit Columns HTML
     */
    function edit_columns_html($column, $post_id){
    
        $post_id = $this->validate_post_id($post_id);
        
        switch($column){
            
            // Field Groups
            case 'field_groups':
                
                $return = '—';
                
                $field_groups = acf_get_field_groups(array(
                    'post_id'    => $post_id,
                    'post_type'    => $this->post_type
                ));
    
                if($field_groups){
                    
                    $html = array();
        
                    foreach($field_groups as $field_group){
                        $html[] = '<a href="' . admin_url('post.php?post=' . $field_group['ID'] . '&action=edit') . '">' . $field_group['title'] . '</a>';
                    }
    
                    $return = implode(', ', $html);
    
                }
                
                echo $return;
                
                break;
                
            // Locations
            case 'locations':
    
                $return = '—';
    
                $field_groups = acf_get_field_groups(array(
                    'post_id'    => $post_id,
                    'post_type'    => $this->post_type
                ));
    
                if($field_groups){
    
                    $groups = $this->get_target_locations($field_groups, $post_id);
                    
                    $html = array();
                    
                    foreach($groups as $group){
    
                        $group = acfe_get_locations_array($group);
                        
                        foreach($group as $rule){
                            $html[] = $rule['html'];
                        }
                        
                    }
                    
                    $return = implode('', $html);
                    
                }
    
                echo $return;
                
                break;
                
            // Fields
            case 'fields':
    
                $return = '—';
    
                $field_groups = acf_get_field_groups(array(
                    'post_id'    => $post_id,
                    'post_type'    => $this->post_type
                ));
    
                if($field_groups){
    
                    $count = 0;
                    foreach($field_groups as $field_group){
                        $count += acf_get_field_count($field_group);
                    }
    
                    $return = $count;
                    
                }
                
                echo $return;
                
                break;
            
        }
        
    }
    
    /*
     * ACF Save post
     */
    function save_post($post_id){
        
        // Get Name
        $name = get_post_field('post_name', $post_id);
        
        // Actions
        do_action("acfe/template/save",                 $name, $post_id);
        do_action("acfe/template/save/name={$name}",    $name, $post_id);
        do_action("acfe/template/save/id={$post_id}",   $name, $post_id);
        
    }
    
    /*
     * Save
     */
    function save($name, $post_id){
        
        // Active
        $active = get_field('acfe_template_active', $post_id);
        $active = $active === null ? true : $active;
        
        // Update post
        wp_update_post(array(
            'ID'            => $post_id,
            'post_status'   => $active ? 'publish' : 'acf-disabled',
        ));
        
    }
    
    /*
     * Import
     */
    function import($name, $args){
        
        // Vars
        $title = acf_extract_var($args, 'title');
        $name = $args['name'];
        $active = $args['active'] ? true : false;
        
        // Already exists
        if(get_page_by_path($name, OBJECT, $this->post_type)){
            return new WP_Error('acfe_template_import_already_exists', __("Template \"{$title}\" already exists. Import aborted."));
        }
        
        // Import Post
        $post_id = false;
        
        $post = array(
            'post_title'    => $title,
            'post_name'     => $name,
            'post_type'     => $this->post_type,
            'post_status'   => $active ? 'publish' : 'acf-disabled'
        );
        
        $post = apply_filters("acfe/template/import_post",                 $post, $name);
        $post = apply_filters("acfe/template/import_post/name={$name}",    $post, $name);
        
        if($post !== false){
            $post_id = wp_insert_post($post);
        }
        
        if(!$post_id || is_wp_error($post_id)){
            return new WP_Error('acfe_template_import_error', __("Something went wrong with the template \"{$title}\". Import aborted."));
        }
        
        $args = acf_maybe_get($args, 'values', array());
        
        // Import Args
        $args = apply_filters("acfe/template/import_args",                  $args, $name, $post_id);
        $args = apply_filters("acfe/template/import_args/name={$name}",     $args, $name, $post_id);
        $args = apply_filters("acfe/template/import_args/id={$post_id}",    $args, $name, $post_id);
        
        if($args === false)
            return $post_id;
        
        // Import Fields
        acf_enable_filter('local');
        
        do_action("acfe/template/import_fields",               $name, $args, $post_id);
        do_action("acfe/template/import_fields/name={$name}",  $name, $args, $post_id);
        do_action("acfe/template/import_fields/id={$post_id}", $name, $args, $post_id);
        
        acf_disable_filter('local');
        
        return $post_id;
        
    }
    
    /*
     * Import Fields
     */
    function import_fields($name, $args, $post_id){
        
        // Update
        acf_update_values($args, $post_id);
        
        if(get_post_status($post_id) === 'publish'){
            update_field('acfe_template_active', 1, $post_id);
        }else{
            update_field('acfe_template_active', 0, $post_id);
        }
        
    }
    
    /*
     * Export: Choices
     */
    function export_choices(){
        
        $choices = array();
        
        $get_posts = get_posts(array(
            'post_type'         => 'acfe-template',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'post_status'       => 'any'
        ));
        
        if(!$get_posts)
            return $choices;
        
        foreach($get_posts as $post_id){
            
            $name = $this->get_name($post_id);
            $choices[$name] = esc_html(get_the_title($post_id));
            
        }
        
        return $choices;
        
    }
    
    /*
     * Export: Data
     */
    function export_data($name){
        
        if(!$template = get_page_by_path($name, OBJECT, $this->post_type))
            return false;
        
        acf_enable_filter('local');
        
        $field_groups = acf_get_field_groups(array(
            'post_id'   => $template->ID,
            'post_type' => $this->post_type
        ));
        
        // Location
        $location = array();
        
        if($field_groups){
            $location = $this->get_target_locations($field_groups, $template->ID);
        }
        
        // Values
        $values = acfe_get_fields($template->ID);
        acfe_unset($values, 'field_acfe_template_active');
        
        // Args
        $args = array(
            'title'     => get_the_title($template->ID),
            'name'      => $this->get_name($template->ID),
            'active'    => get_field('acfe_template_active', $template->ID),
            'values'    => $values,
            'location'  => $location
        );
        
        // Filters
        $args = apply_filters("acfe/template/export_args",                 $args, $name);
        $args = apply_filters("acfe/template/export_args/name={$name}",    $args, $name);
        
        acf_disable_filter('local');
        
        return $args;
        
    }
    
    /*
     * Export: PHP
     */
    function export_php($data){
        
        // prevent default translation and fake __() within string
        acf_update_setting('l10n_var_export', true);
        
        $str_replace = array(
            "  "            => "\t",
            "'!!__(!!\'"    => "__('",
            "!!\', !!\'"    => "', '",
            "!!\')!!'"      => "')",
            "array ("       => "array("
        );
        
        $preg_replace = array(
            '/([\t\r\n]+?)array/'   => 'array',
            '/[0-9]+ => array/'     => 'array'
        );
        
        echo "add_action('acf/init', 'my_acfe_local_template');" . "\r\n";
        echo "function my_acfe_local_template(){" . "\r\n" . "\r\n";
        
        foreach($data as $name => $args){
            
            // code
            $code = var_export($args, true);
            
            // change double spaces to tabs
            $code = str_replace(array_keys($str_replace), array_values($str_replace), $code);
            
            // correctly formats "=> array("
            $code = preg_replace(array_keys($preg_replace), array_values($preg_replace), $code);
            
            // esc_textarea
            $code = esc_textarea($code);
            
            // echo
            echo "acfe_add_local_template({$code});" . "\r\n" . "\r\n";
            
        }
        
        echo "}";
        
    }
    
    /*
     * Location: Type
     */
    function location_types($choices){
        
        $name = __('Forms', 'acf');
        $choices[$name] = acfe_array_insert_after('options_page', $choices[$name], 'acfe_template', __('Dynamic Template', 'acfe'));
        
        return $choices;
        
    }
    
    /*
     * Location: Operators
     */
    function location_operators($operators, $rule){
        
        $operators = array(
            '==' => __("is equal to",'acf'),
        );
        
        return $operators;
        
    }
    
    /*
     * Location: Values
     */
    function location_values($choices){
        
        $get_posts = get_posts(array(
            'post_type'         => $this->post_type,
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'post_status'       => 'any',
            'suppress_filters'  => true, // WPML: All langs
            'lang'              => '', // Polylang: All langs
        ));
        
        $choices = array();
        
        if(!empty($get_posts)){
            
            foreach($get_posts as $pid){
                
                $choices[$pid] = get_the_title($pid);
                
            }
            
        }else{
            
            $choices[''] = __('No template pages found', 'acfe');
            
        }
        
        return $choices;
        
    }
    
    /*
     * Match: Post | Term | User Target Screen
     */
    function location_match_target($match, $rule, $screen, $field_group){
        
        $post_type = acf_maybe_get($screen, 'post_type');
        
        // Do not match Template Post Type
        if($post_type === $this->post_type)
            return $match;
        
        // Vars
        global $pagenow;
        
        // Check screen
        if(!in_array($pagenow, array('post.php', 'post-new.php', 'profile.php', 'user-edit.php', 'user-new.php', 'edit-tags.php', 'term.php')))
            return true;
        
        // Retrieve template
        $template_id = $rule['value'];
        $template_id = acf_get_valid_post_id($template_id);
        $template_id = acfe_get_post_translated($template_id);
        
        // Check template status
        if(get_post_status($template_id) !== 'publish')
            return true;

        // Get values
        $values = acfe_get_fields($template_id);

        // Apply values
        $this->apply_values($values);
        
        return true;
        
    }
    
    /*
     * Match: Template
     */
    function location_match_template($match, $rule, $screen, $field_group){
        
        $post_type = acf_maybe_get($screen, 'post_type');
        $template_id = acf_maybe_get($screen, 'post_id');
        $template_id = $this->validate_post_id($template_id);
        
        if(!$template_id || $post_type !== $this->post_type || !$field_group || $field_group['key'] === 'group_acfe_dynamic_template_side')
            return $match;
        
        // Check if active.
        if(!$field_group['active'])
            return false;
        
        if($field_group['location']){
            
            // Loop through location groups.
            foreach($field_group['location'] as $group){
                
                // ignore group if no rules.
                if(empty($group))
                    continue;
                
                // Loop over rules and determine if all rules match.
                $match_group = false;
                
                foreach($group as $rule){
                    
                    if($rule['param'] === 'acfe_template' && (int) $rule['value'] === $template_id){
                        
                        $match_group = true;
                        break;
                        
                    }
                    
                }
                
                if($match_group)
                    return true;
                
            }
            
        }
        
        // Return default.
        return false;
        
    }
    
    /*
     * Local Template: Load Post | Term | User Target Screen
     */
    function local_template_load_target(){
        
        // Globals
        global $pagenow, $post;
        
        // Check screen
        if(!in_array($pagenow, array('post.php', 'post-new.php', 'profile.php', 'user-edit.php', 'user-new.php', 'edit-tags.php', 'term.php'))){
            return;
        }
        
        // Do not apply on Template post type
        if(acfe_maybe_get($post, 'post_type') === $this->post_type && in_array($pagenow, array('post.php', 'post-new.php'))){
            return;
        }
        
        // Get local templates
        $templates = acfe_get_local_templates();
        
        if(!$templates) return;
    
        // get screen
        $screen = acf_get_form_data('location');
        
        // Loop
        foreach($templates as $template){
    
            // Check active
            if(!$template['active']) continue;
            
            // Check setting
            if(!$template['location']) continue;
            
            // Match screen
            if(!acfe_match_location_rules($template['location'], $screen)) continue;
            
            // Apply
            $this->apply_values($template['values']);
            
        }
        
    }
    
    /*
     * Apply Values
     */
    function apply_values($template){
        
        // Empty template values
        if(empty($template))
            return;
        
        // Pre load value
        add_filter('acf/pre_load_value', function($null, $post_id, $field) use($template){
            
            $field_key = $field['key'];
            $field_name = $field['name'];
            
            // Check if key is in the template
            if(!isset($template[ $field_key ]))
                return $null;
    
            // Get store
            $store = acf_get_store('values');
    
            // Check store
            if($store->has("$post_id:$field_name"))
                return $null;
    
            // Load value from database.
            $value = acf_get_metadata($post_id, $field_name);
    
            // Value already exists
            if($value !== null)
                return $null;
    
            return $template[ $field_key ];
    
        }, 10, 3);
        
    }
    
    /*
     * Get Target Locations
     */
    function get_target_locations($field_groups, $post_id){
        
        $groups = array();
        
        foreach($field_groups as $field_group){
            
            if(!$field_group['location'])
                continue;
                
            // Loop through location groups.
            foreach($field_group['location'] as $group){
                
                // ignore group if no rules.
                if(empty($group))
                    continue;
                
                $found = false;
                
                foreach($group as $rule){
                    
                    if($rule['param'] === 'acfe_template' && (int) $rule['value'] === $post_id){
                        $found = true;
                    }
                    
                }
                
                if($found){
                    
                    $groups[] = $group;
                    
                }
                
            }
            
        }
        
        foreach($groups as &$group){
            
            foreach($group as $i => $rule){
                
                if($rule['param'] !== 'acfe_template')
                    continue;
                
                unset($group[$i]);
                
            }
            
        }
        
        return $groups;
        
    }
    
    /*
     * Validate Post ID
     */
    function validate_post_id($post_id){
    
        $post_id = acf_get_valid_post_id($post_id);
        $post_id = acfe_get_post_translated_default($post_id);
        $post_id = (int) $post_id;
        
        return $post_id;
        
    }
    
    /*
     * Validate Field Group Conditions
     */
    function validate_field_group($field_group){
        
        if(!$field_group['location'])
            return $field_group;
        
        // Loop through location groups.
        foreach($field_group['location'] as $k => $group){
            
            // ignore group if no rules.
            if(empty($group))
                continue;
            
            // Do not allow Template as single location (only use in combination with another rule)
            if(count($group) !== 1)
                continue;
            
            foreach($group as $_k => $rule){
                
                if($rule['param'] !== 'acfe_template')
                    continue;
                
                unset($field_group['location'][$k]);
                
            }
            
        }
        
        return $field_group;
        
    }
    
    /*
     * Add Local Field Group
     */
    function add_local_field_group(){
    
        acf_add_local_field_group(array(
            'key' => 'group_acfe_dynamic_template_side',
            'title' => 'Templates: Side',
            'acfe_display_title' => 'Active',
            'fields' => array(
                array(
                    'key' => 'field_acfe_template_active',
                    'label' => '',
                    'name' => 'acfe_template_active',
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

acf_new_instance('acfe_dynamic_templates');

endif;