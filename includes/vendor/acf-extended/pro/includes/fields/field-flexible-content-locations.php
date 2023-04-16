<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_flexible_content_layout_locations')):

class acfe_field_flexible_content_layout_locations{
    
    function __construct(){
    
        // Hooks
        add_filter('acfe/flexible/defaults_field',              array($this, 'defaults_field'), 4);
        add_filter('acfe/flexible/defaults_layout',             array($this, 'defaults_layout'), 4);
        
        add_action('acfe/flexible/render_field_settings',       array($this, 'render_field_settings'), 4);
        add_action('acfe/flexible/render_layout_settings',      array($this, 'render_layout_settings'), 22, 3);
    
        add_filter('acf/update_field/type=flexible_content',    array($this, 'update_field'));
        add_filter('acf/prepare_field/type=flexible_content',   array($this, 'prepare_field'));
        add_filter('acf/validate_value/type=flexible_content',  array($this, 'validate_value'), 11, 4);
        add_action('wp_ajax_acfe/layout/render_location_rule',  array($this, 'ajax_render_location_rule'));
        
    }
    
    function update_field($field){
    
        if(!acf_maybe_get($field, 'acfe_flexible_layouts_locations')) return $field;
        
        if(empty($field['layouts'])) return $field;
    
        foreach($field['layouts'] as &$layout){
            
            // validate
            if(!acf_maybe_get($layout, 'acfe_layout_locations')) continue;
    
            // Remove empty values and convert to associated array.
            $layout['acfe_layout_locations'] = array_filter($layout['acfe_layout_locations']);
            $layout['acfe_layout_locations'] = array_values($layout['acfe_layout_locations']);
            $layout['acfe_layout_locations'] = array_map('array_filter', $layout['acfe_layout_locations']);
            $layout['acfe_layout_locations'] = array_map('array_values', $layout['acfe_layout_locations']);
    
        }
        
        return $field;
        
    }
    
    function prepare_field($field){
        
        // check setting
        if(!acf_maybe_get($field, 'acfe_flexible_layouts_locations')) return $field;
        
        // bail early
        if(empty($field['layouts'])) return $field;
    
        // get screen
        $screen = acf_get_form_data('location');
    
        // validate args
        if(!$screen) return $field;
        
        // show all layouts on templates module
        if(acf_maybe_get($screen, 'post_type') === 'acfe-template'){
            return $field;
        }
        
        foreach($field['layouts'] as $i => $layout){
            
            // validate setting
            if(!$layout['acfe_layout_locations']) continue;
            
            // match location rules
            if(acfe_match_location_rules($layout['acfe_layout_locations'], $screen)) continue;
            
            // unset
            unset($field['layouts'][$i]);
            
        }
        
        // do not display field if no layout
        if(empty($field['layouts'])){
            return false;
        }
        
        // return
        return $field;
        
    }
    
    function validate_value($valid, $value, $field, $input){
    
        // check setting
        if(!acf_maybe_get($field, 'acfe_flexible_layouts_locations')) return $valid;
    
        // bail early
        if(empty($field['layouts'])) return $valid;
    
        // vars
        $count = 0;
    
        // check if is value (may be empty string)
        if(is_array($value)){
        
            // remove acfcloneindex
            if(isset($value['acfcloneindex'])){
                unset($value['acfcloneindex']);
            }
        
            // count
            $count = count($value);
            
        }
    
        // find layouts
        $layouts = array();
    
        foreach(array_keys($field['layouts']) as $i){
        
            // vars
            $layout = $field['layouts'][ $i ];
        
            // add count
            $layout['count'] = 0;
        
            // append
            $layouts[ $layout['name'] ] = $layout;
        
        }
    
        // validate value
        if($count){
        
            // loop rows
            foreach($value as $i => $row){
            
                // get layout
                $l = $row['acf_fc_layout'];
            
                // bail if layout doesn't exist
                if(!isset($layouts[ $l ])){
                    continue;
                }
            
                // increase count
                $layouts[ $l ]['count']++;
            
            }
            
        }
    
        // get screen
        $screen = acf_get_form_data('location');
    
        foreach($layouts as $i => $layout){
        
            // validate setting
            if(!$layout['acfe_layout_locations']) continue;
        
            // match location rules
            if(acfe_match_location_rules($layout['acfe_layout_locations'], $screen)) continue;
        
            // unset
            unset($layouts[$i]);
        
        }
    
        // validate layouts
        foreach($layouts as $layout){
        
            // validate min / max
            $min = (int) $layout['min'];
            $count = $layout['count'];
            $label = $layout['label'];
        
            if($min && $count < $min){
            
                // vars
                $error = __('This field requires at least {min} {label} {identifier}', 'acf');
                $identifier = _n('layout', 'layouts', $min);
            
                // replace
                $error = str_replace('{min}', $min, $error);
                $error = str_replace('{label}', '"' . $label . '"', $error);
                $error = str_replace('{identifier}', $identifier, $error);
            
                // return
                return $error;
                
            }
            
        }
        
        $text = __('This field requires at least {min} {label} {identifier}', 'acf');
        
        if(acfe_starts_with($valid, substr($text, 0, 20))){
            $valid = true;
        }
        
        // return
        return $valid;
        
    }
    
    function ajax_render_location_rule() {
        
        // validate
        if(!acf_verify_ajax()) die();
        
        // validate rule
        $rule = acf_validate_location_rule($_POST['rule']);
        
        // prefix
        $prefix = $_POST['prefix'];
    
        // render rule
        $this->render_location_rule($rule, $prefix);
        
        // die
        die();
        
    }
    
    function defaults_field($field){
        
        $field['acfe_flexible_layouts_locations'] = false;
        
        return $field;
        
    }
    
    function defaults_layout($layout){
    
        $layout['acfe_layout_locations'] = array();
        
        return $layout;
        
    }
    
    function render_field_settings($field){
    
        acf_render_field_setting($field, array(
            'label'         => __('Layouts Locations Rules', 'acfe'),
            'name'          => 'acfe_flexible_layouts_locations',
            'key'           => 'acfe_flexible_layouts_locations',
            'instructions'  => __('Define custom locations rules for each layouts', 'acfe') . '. ' . '<a href="https://www.acf-extended.com/features/fields/flexible-content/location-rules" target="_blank">' . __('See documentation', 'acfe') . '</a>',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'ui_on_text'        => '',
            'ui_off_text'       => '',
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_flexible_advanced',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
    }
    
    function render_layout_settings($flexible, $layout, $prefix){
        
        if(!acf_maybe_get($flexible, 'acfe_flexible_layouts_locations'))
            return;
        
        // default location
        if(empty($layout['acfe_layout_locations'])){
            
            // get field group
            $field_group = acfe_get_field_group_from_field($flexible);
            
            // apply field group locations as default location
            $layout['acfe_layout_locations'] = $field_group['location'];
            
        }
        
        // Close <li>
        echo '</li>';
    
        ?>
        <div class="acf-field">
            <div class="acf-input">
                <div class="acfe-layout-locations rule-groups">
                
                    <?php $this->render_location_group($layout, $prefix); ?>

                </div>
            </div>
        </div>
        <?php
        
    }
    
    function render_location_group($layout, $l_prefix){
        
        foreach($layout['acfe_layout_locations'] as $i => $group):
            
            // bail early if no group
            if(empty($group)) return;
            
            $group_id = "group_{$i}";
        
            ?>
            <div class="rule-group" data-id="<?php echo $group_id; ?>">

                <h4><?php echo ($group_id == 'group_0') ? __("Location Rules",'acf') : __("or",'acf'); ?></h4>

                <table class="acf-table -clear">
                    <tbody>
                    
                    <?php
                    foreach($group as $i => $rule){
    
                        // validate rule
                        $rule = acf_validate_location_rule($rule);
    
                        // append id and group
                        $rule['id'] = "rule_{$i}";
                        $rule['group'] = $group_id;
    
                        // prefix
                        $prefix = $l_prefix.'[acfe_layout_locations]['.$rule['group'].']['.$rule['id'].']';
                        
                        // render rule
                        $this->render_location_rule($rule, $prefix);
                        
                    }
                    ?>
                    
                    </tbody>
                </table>

            </div>
        <?php
    
        endforeach; ?>

        <a href="#" class="button add-location-group"><?php _e("Add rule group",'acf'); ?></a>
        <?php
        
    }
    
    function render_location_rule($rule, $prefix){
        ?>
        <tr data-id="<?php echo $rule['id']; ?>">
            <td class="param">
                <?php
                
                // vars
                $choices = acf_get_location_rule_types();
                
                // array
                if(is_array($choices)){
                    
                    // remove global conditions
                    foreach($choices as $optgroup => &$optchoices){
                        
                        foreach($optchoices as $key => $optchoice){
                            
                            if(strpos($key, 'field_') !== 0) continue;
                            
                            unset($optchoices[$key]);
                            
                        }
                        
                        unset($choices['Global Fields']);
                        
                    }
                    
                    acf_render_field(array(
                        'type'      => 'select',
                        'name'      => 'param',
                        'prefix'    => $prefix,
                        'value'     => $rule['param'],
                        'choices'   => $choices,
                        'class'     => 'refresh-location-rule'
                    ));
                    
                }
                
                ?>
            </td>
            <td class="operator">
                <?php
                
                // vars
                $choices = acf_get_location_rule_operators($rule);
                
                // array
                if(is_array($choices)){
                    
                    acf_render_field(array(
                        'type'      => 'select',
                        'name'      => 'operator',
                        'prefix'    => $prefix,
                        'value'     => $rule['operator'],
                        'choices'   => $choices
                    ));
                    
                // custom
                }else{
                    
                    echo $choices;
                    
                }
                
                ?>
            </td>
            <td class="value">
                <?php
                
                // vars
                $choices = acf_get_location_rule_values($rule);
                
                // array
                if(is_array($choices)){
                    
                    acf_render_field(array(
                        'type'      => 'select',
                        'name'      => 'value',
                        'prefix'    => $prefix,
                        'value'     => $rule['value'],
                        'choices'   => $choices
                    ));
                    
                // custom
                }else{
                    
                    echo $choices;
                    
                }
                
                ?>
            </td>
            <td class="add">
                <a href="#" class="button add-location-rule"><?php _e("and",'acf'); ?></a>
            </td>
            <td class="remove">
                <a href="#" class="acf-icon -minus remove-location-rule"></a>
            </td>
        </tr>
        <?php
    }
    
}

acf_new_instance('acfe_field_flexible_content_layout_locations');

endif;