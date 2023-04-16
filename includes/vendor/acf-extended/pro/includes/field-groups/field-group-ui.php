<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acfe_get_setting('modules/field_group_ui'))
    return;

if(!class_exists('acfe_field_group_ui')):

class acfe_field_group_ui{
    
    function __construct(){
    
        add_action('acf/field_group/admin_head',        array($this, 'admin_head'), 5);
        add_action('acf/field_group/admin_head',        array($this, 'prepare_data'));
        add_action('acf/field_group/admin_head',        array($this, 'prepare_meta'));
        add_action('acf/render_field_group_settings',   array($this, 'render_settings'));
        
    }
    
    /*
     * Admin Head
     */
    function admin_head(){
    
        global $field_group;
    
        if(!acf_maybe_get($field_group, 'acfe_form')){
            return;
        }
        
        acf_enable_filter('acfe/field_group/advanced');
        
    }
    
    function prepare_data(){
    
        add_action('acf/render_field/name=acfe_data', array($this, 'render_data'));
        
    }
    
    /*
     * Render Settings
     */
    function render_settings($field_group){
    
        // General
        acf_render_field_wrap(array(
            'label' => 'General',
            'type'  => 'tab',
            'key'  => 'general',
            'wrapper' => array(
                'data-no-preference' => true,
                'data-before' => 'active'
            )
        ));
        
        // Form settings
        acf_render_field_wrap(array(
            'label'         => __('Advanced settings', 'acfe'),
            'name'          => 'acfe_form',
            'prefix'        => 'acf_field_group',
            'type'          => 'true_false',
            'ui'            => 1,
            'instructions'  => __('Enable advanced fields settings & validation'),
            'value'         => (isset($field_group['acfe_form'])) ? $field_group['acfe_form'] : '',
            'required'      => false,
            'wrapper'       => array(
                'data-after' => 'active'
            )
        ));
    
        /*
         * Display Title
         */
        acf_render_field_wrap(array(
            'label'         => __('Display title', 'acfe'),
            'instructions'  => __('Render this title on edit post screen', 'acfe'),
            'type'          => 'text',
            'name'          => 'acfe_display_title',
            'prefix'        => 'acf_field_group',
            'value'         => acf_maybe_get($field_group, 'acfe_display_title'),
            'placeholder'   => '',
            'prepend'       => '',
            'append'        => '',
            'wrapper'       => array(
                'data-before' => 'menu_order'
            )
        ));
    
        // Hide on screen
        acf_render_field_wrap(array(
            'label' => 'Screen',
            'type'  => 'tab',
            'key'   => 'screen',
            'wrapper'       => array(
                'data-before' => 'acfe_display_title'
            )
        ));
    
        if(acf_maybe_get($field_group, 'acfe_permissions') || acf_is_filter_enabled('acfe/field_group/advanced')){
    
            // Permission
            acf_render_field_wrap(array(
                'label' => 'Permissions',
                'type'  => 'tab',
                'key'   => 'permissions'
            ));
    
            /*
             * Permissions
             */
            acf_render_field_wrap(array(
                'label'         => __('Permissions'),
                'name'          => 'acfe_permissions',
                'prefix'        => 'acf_field_group',
                'type'          => 'checkbox',
                'instructions'  => __('Select user roles that are allowed to view and edit this field group in post edition'),
                'required'      => false,
                'default_value' => false,
                'choices'       => acfe_get_roles(),
                'value'         => acf_maybe_get($field_group, 'acfe_permissions', array()),
                'layout'        => 'vertical'
            ));
            
        }
    
        // Advanced
        acf_render_field_wrap(array(
            'label' => 'Data',
            'type'  => 'tab',
            'key'   => 'advanced'
        ));
    
        // Meta
        acf_render_field_wrap(array(
            'label'         => __('Custom meta data'),
            'name'          => 'acfe_meta',
            'key'           => 'acfe_meta',
            'instructions'  => __('Add custom meta data to the field group.'),
            'prefix'        => 'acf_field_group',
            'type'          => 'repeater',
            'button_label'  => __('+ Meta'),
            'required'      => false,
            'layout'        => 'table',
            'value'         => (isset($field_group['acfe_meta'])) ? $field_group['acfe_meta'] : array(),
            'wrapper'       => array(
                'data-enable-switch' => true
            ),
            'sub_fields'    => array(
                array(
                    'ID'            => false,
                    'label'         => __('Key'),
                    'name'          => 'acfe_meta_key',
                    'key'           => 'acfe_meta_key',
                    'prefix'        => '',
                    '_name'         => '',
                    '_prepare'      => '',
                    'type'          => 'text',
                    'instructions'  => false,
                    'required'      => false,
                    'wrapper'       => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                ),
                array(
                    'ID'            => false,
                    'label'         => __('Value'),
                    'name'          => 'acfe_meta_value',
                    'key'           => 'acfe_meta_value',
                    'prefix'        => '',
                    '_name'         => '',
                    '_prepare'      => '',
                    'type'          => 'text',
                    'instructions'  => false,
                    'required'      => false,
                    'wrapper'       => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                ),
            )
        ));
    
        // Data
        acf_render_field_wrap(array(
            'label'         => __('Field group data'),
            'instructions'  => __('View raw field group data, for development use'),
            'type'          => 'acfe_dynamic_render',
            'name'          => 'acfe_data',
            'prefix'        => 'acf_field_group',
            'value'         => $field_group['key'],
        ));
    
        // Note
        acf_render_field_wrap(array(
            'label' => 'Note',
            'type'  => 'tab',
            'key'   => 'note'
        ));
    
        // Note
        acf_render_field_wrap(array(
            'label'         => __('Note'),
            'name'          => 'acfe_note',
            'prefix'        => 'acf_field_group',
            'type'          => 'textarea',
            'instructions'  => __('Add personal note. Only visible to administrators'),
            'value'         => (isset($field_group['acfe_note'])) ? $field_group['acfe_note'] : '',
            'required'      => false
        ));
        
    }
    
    /*
     * Render: Data button
     */
    function render_data($field){
        
        $field_group = acf_get_field_group($field['value']);
        
        if(!$field_group){
            
            echo '<a href="#" class="button disabled" disabled>' . __('Data') . '</a>';
            return;
            
        }
    
        $raw_field_group = get_post($field_group['ID']);
    
        ?>
        <a href="#" class="acf-button button" data-acfe-modal data-acfe-modal-title="<?php echo $field_group['title']; ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('Data', 'acfe'); ?></a>
        <div class="acfe-modal">
            <div class="acfe-modal-spacer">
                <pre style="margin-bottom:15px;"><?php print_r($field_group); ?></pre>
                <pre><?php print_r($raw_field_group); ?></pre>
            </div>
        </div>
        <?php
        
    }
    
    /*
     * Prepare Meta
     */
    function prepare_meta(){
        
        $names = array('acfe_meta', 'acfe_meta_key', 'acfe_meta_value');
        
        foreach($names as $name){
            
            add_filter("acf/prepare_field/name={$name}", function($field){
                
                $field['prefix'] = str_replace('row-', '', $field['prefix']);
                $field['name'] = str_replace('row-', '', $field['name']);
                
                return $field;
                
            });
            
        }
        
    }
    
}

// initialize
new acfe_field_group_ui();

endif;