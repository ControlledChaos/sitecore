<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_admin_settings')):

class acfe_pro_admin_settings{
    
    public $defaults = array();
    public $updated = array();
    public $fields = array();
    
    function __construct(){
        
        $class = acf_get_instance('acfe_admin_settings_ui');
        
        add_action('acf/init',                      array($this, 'acf_init'), 9);
        add_action('acfe/admin_settings/load',      array($this, 'load'));
        add_action('acfe/admin_settings/html',      array($this, 'html'));
        
        remove_action('acfe/admin_settings/load',   array($class, 'load'));
        remove_action('acfe/admin_settings/html',   array($class, 'html'));
        
    }
    
    /*
     * ACF Init
     */
    function acf_init(){
        
        $settings = acfe_get_settings('settings');
        
        if(empty($settings)){
            return;
        }
        
        foreach($settings as $k => $v){
            acf_update_setting($k, $v);
        }
        
    }
    
    /*
     * Load
     */
    function load(){
        
        $acfe_admin_settings = acf_get_instance('acfe_admin_settings');
        
        $this->defaults = $acfe_admin_settings->defaults;
        $this->updated = $acfe_admin_settings->updated;
        $this->fields = $acfe_admin_settings->fields;
        
        $this->register_fields();
    
        // Enqueue
        acf_enqueue_scripts();
    
        // Submit
        if(acf_verify_nonce('acfe_settings')){
        
            // Validate
            if(acf_validate_save_post(true)){
                
                $this->save_post();
            
                // Redirect
                wp_redirect(add_query_arg(array('message' => 'acfe_settings')));
                exit;
            
            }
        
        }
    
        // Success
        if(acf_maybe_get_GET('message') === 'acfe_settings'){
        
            acf_add_admin_notice('Settings Saved.', 'success');
        
        }
        
    }
    
    /*
     * Save Post
     */
    function save_post(){
        
        $values = acf_maybe_get_POST('acfe_settings', array());
        
        foreach($values as $name => &$value){
            
            $data = $this->get_setting($name);
            
            if($data['format'] === 'array'){
                
                if(empty($value)){
                    $value = array();
                }else{
                    $value = explode(',', $value);
                }
                
            }
            
            $value = wp_unslash($value);
            
        }
        
        // Update Settings
        acfe_update_settings('settings', $values);
        
    }
    
    /*
     * Get Setting
     */
    function get_setting($name){
        
        foreach($this->fields as $category => $rows){
            
            foreach($rows as $row){
                
                if($row['name'] !== $name)
                    continue;
        
                $setting = $row;
                break;
                
            }
            
        }
        
        return $this->validate_setting($setting);
        
    }
    
    /*
     * Validate Setting
     */
    function validate_setting($setting){
    
        $setting = wp_parse_args($setting, array(
            'label'         => '',
            'name'          => '',
            'type'          => '',
            'description'   => '',
            'category'      => '',
            'format'        => '',
            'default'       => '',
            'updated'       => '',
            'value'         => '',
            'class'         => '',
            'buttons'       => '',
            'diff'          => false,
        ));
        
        return $setting;
        
    }
    
    /*
     * Prepare Setting
     */
    function prepare_setting($setting){
    
        // Vars
        $settings = acfe_get_settings('settings');
        
        $name = $setting['name'];
        $type = $setting['type'];
        $format = $setting['format'];
        $default = $this->defaults[$name];
        $updated = $this->updated[$name];
        
        $vars = array(
            'default' => $default,
            'updated' => $updated
        );
        
        foreach($vars as $v => $var){
            
            $result = $var;
            
            if($type === 'true_false'){
    
                $result = $var ? '<span class="dashicons dashicons-saved"></span>' : '<span class="dashicons dashicons-no-alt"></span>';
                
            }elseif($type === 'text'){
    
                $result = '<span class="dashicons dashicons-no-alt"></span>';
    
                if($format === 'array' && empty($var) && $v === 'updated' && $default !== $updated){
                    $var = array('(empty)');
                }
                
                if(!empty($var)){
                    
                    if(!is_array($var)){
                        $var = explode(',', $var);
                    }
        
                    foreach($var as &$r){
                        $r = '<div class="acf-js-tooltip acfe-settings-text" title="' . $r . '"><code>' . $r . '</code></div>';
                    }
    
                    $result = implode('', $var);
        
                }
                
            }
    
            $setting[$v] = $result;
            
        }
        
        // Local Changes
        if($default !== $updated && $updated !== acfe_get_settings("settings.{$name}")){
            
            $setting['updated'] .= '<span style="color:#888; margin-left:7px;vertical-align: 6px;font-size:11px;">(Local code)</span>';
            $setting['diff'] = true;
            
        }
        
        // Value
        $button_edit = $button_default = $class = '';
        $value = acf_maybe_get($settings, $name);
    
        // Value exists
        if($value !== null){
        
            $button_edit = 'acf-hidden';
            $setting['diff'] = true;
        
        }else{
        
            $button_default = 'acf-hidden';
            $class = 'acf-hidden acfe-disabled';
    
            $value = $this->defaults[$name];
            
        }
    
        if(is_array($value)){
            $value = implode(',', $value);
        }
        
        $setting['value'] = $value;
        $setting['class'] = $class;
        $setting['buttons'] = '<a href="#" class="' . $button_default . '" data-acfe-settings-action="default" data-acfe-settings-field="' . $name . '" style="margin-left:2px; padding:6px 0;display:block;">Default</a><a href="#" class="acf-button button ' . $button_edit . '" data-acfe-settings-action="edit" data-acfe-settings-field="' . $name . '">Edit</a>';
        
        return $setting;
        
    }
    
    /*
     * HTML
     */
    function html(){
        
        ?>
        <div class="wrap" id="acfe-admin-settings">

            <h1><?php _e('Settings'); ?></h1>

            <form id="post" method="post" name="post">
        
                <?php
        
                // render post data
                acf_form_data(array(
                    'screen' => 'acfe_settings',
                ));
                
                ?>
                <div id="poststuff">
            
                    <div id="post-body" class="metabox-holder columns-2">
                        
                        <!-- Sidebar -->
                        <div id="postbox-container-1" class="postbox-container">
                            
                            <div id="side-sortables" class="meta-box-sortables ui-sortable">
                                <div id="submitdiv" class="postbox">
                                    <div class="postbox-header"><h2 class="hndle ui-sortable-handle">Publish</h2></div>
                                    <div class="inside">
                                        
                                        <div id="minor-publishing">

                                            <div id="misc-publishing-actions">
                                                
                                                <div class="misc-pub-section acfe-misc-export">
                                                    <span class="dashicons dashicons-editor-code"></span>
                                                    Export:
                                                    <a href="<?php echo admin_url("edit.php?post_type=acf-field-group&page=acf-tools&tool=acfe_settings_export&action=php"); ?>">PHP</a>
                                                    <a href="<?php echo admin_url("edit.php?post_type=acf-field-group&page=acf-tools&tool=acfe_settings_export&action=json"); ?>">Json</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="major-publishing-actions">
        
                                            <div id="publishing-action">
                                                <span class="spinner"></span>
                                                <input type="submit" accesskey="p" value="<?php _e('Update'); ?>" class="button button-primary button-large" id="publish" name="publish">
                                            </div>
        
                                            <div class="clear"></div>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- Metabox -->
                        <div id="postbox-container-2" class="postbox-container">
            
                            <div class="postbox acf-postbox">
                                
                                <div class="postbox-header">
                                    <h2 class="hndle ui-sortable-handle"><span><?php _e('Settings'); ?></span></h2>
                                </div>
                                <div class="inside acf-fields -left">
                                
                                    <?php $this->render_fields(); ?>
            
                                    <script type="text/javascript">
                                        if(typeof acf !== 'undefined'){
                                            acf.newPostbox({
                                                'id': 'acfe-settings',
                                                'label': 'left'
                                            });
                                        }
                                    </script>
                                </div>
                            </div>
        
                        </div>
                    
                    </div>
                    
                </div>
                
            </form>
            
        </div>
        <?php
    }
    
    /*
     * Render Fields
     */
    function render_fields(){
    
        foreach(array('ACF', 'ACFE', 'AutoSync', 'Modules', 'Fields') as $tab){
    
            // Category
            $category = sanitize_title($tab);
            
            if(isset($this->fields[$category])){
                
                $fields = array();
                $count = 0;
                
                foreach($this->fields[$category] as $field){
                    
                    // Prepare
                    $field = $this->validate_setting($field);
                    $field = $this->prepare_setting($field);
                    
                    $fields[] = $field;
                    
                }
                
                foreach($fields as $field){
                    
                    if(!$field['diff']) continue;
                    $count++;
                    
                }
                
                $class = $count > 0 ? 'acfe-tab-badge' : 'acfe-tab-badge acf-hidden';
                $tab .= ' <span class="' . $class . '">' . $count . '</span>';
                
                // Tab
                acf_render_field_wrap(array(
                    'type'  => 'tab',
                    'label' => $tab,
                    'key'   => 'field_acfe_settings_tabs',
                    'wrapper' => array(
                        'data-no-preference' => true,
                    ),
                ));
    
                // Thead
                acf_render_field_wrap(array(
                    'type'  => 'acfe_dynamic_render',
                    'label' => '',
                    'key'   => 'field_acfe_settings_thead_' . $category,
                    'wrapper' => array(
                        'class' => 'acfe-settings-thead'
                    ),
                    'render' => function($field){
                        ?>
                        <div>Default</div>
                        <div>Registered</div>
                        <div>Edit</div>
                        <?php
                    }
                ));
    
                foreach($fields as $field){ ?>

                    <div class="acf-field">
                        <div class="acf-label">
                            <label><span class="acf-js-tooltip dashicons dashicons-info" title="<?php echo $field['name']; ?>"></span><?php echo $field['label']; ?></label>
                            <?php if($field['description']){ ?>
                                <p class="description"><?php echo $field['description']; ?></p>
                            <?php } ?>
                        </div>
                        <div class="acf-input">

                            <div><?php echo $field['default']; ?></div>
                            
                            <div><?php echo $field['updated']; ?></div>

                            <div>

                                <div><?php echo $field['buttons']; ?></div>

                                <div>
                                    <?php
                                    acf_render_field_wrap(array(
                                        'instructions'  => '',
                                        'type'          => $field['type'],
                                        'ui'            => true,
                                        'key'           => $field['name'],
                                        'name'          => $field['name'],
                                        'prefix'        => 'acfe_settings',
                                        'value'         => $field['value'],
                                        'wrapper'       => array(
                                            'class'                     => $field['class'],
                                            'style'                     => 'margin:0;',
                                            'data-acfe-settings-field'  => 1
                                        )
                                    ));
                                    ?>
                                </div>

                            </div>

                        </div>
                    </div>
            
                    <?php
                }
        
            }
            
        }
        
    }
    
    /*
     * Register Fields
     */
    function register_fields(){
    
        $this->fields['modules'][] = array(
            'label'         => 'Classic Editor',
            'name'          => 'acfe/modules/classic_editor',
            'description'   => 'Enable the Classic Editor module. Defaults to false',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Field Group UI',
            'name'          => 'acfe/modules/field_group_ui',
            'description'   => 'Enable the enhanced Field Group UI module. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Force Sync',
            'name'          => 'acfe/modules/force_sync',
            'description'   => 'Enable the Force Sync module. Defaults to false',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Force Sync: Delete',
            'name'          => 'acfe/modules/force_sync/delete',
            'description'   => 'Sync deleted field groups files. Force Sync must be enabled. Defaults to false',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Forms: Shortcode Preview',
            'name'          => 'acfe/modules/forms/shortcode_preview',
            'type'          => 'text',
            'description'   => 'Display <code>[acfe_form]</code> shortcode preview in editors. Defaults to false',
            'category'      => 'modules',
            'format'        => 'array',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Global Field Condition',
            'name'          => 'acfe/modules/global_field_condition',
            'description'   => 'Enable the Global Field Condition module. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        $this->fields['modules'][] = array(
            'label'         => 'Rewrite Rules',
            'name'          => 'acfe/modules/rewrite_rules',
            'description'   => 'Enable the Rewrite Rules UI. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
        
        $this->fields['modules'][] = array(
            'label'         => 'Screen Layouts',
            'name'          => 'acfe/modules/screen_layouts',
            'description'   => 'Enable the Columns Screen Layouts. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
        
        $this->fields['modules'][] = array(
            'label'         => 'Scripts',
            'name'          => 'acfe/modules/scripts',
            'description'   => 'Enable the Scripts UI. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
        
        $this->fields['modules'][] = array(
            'label'         => 'Templates',
            'name'          => 'acfe/modules/templates',
            'description'   => 'Enable the Templates module. Defaults to true',
            'type'          => 'true_false',
            'category'      => 'modules',
        );
    
        usort($this->fields['modules'], function($a, $b){
            return strcmp($a['label'], $b['label']);
        });
    
    }
    
}

acf_new_instance('acfe_pro_admin_settings');

endif;