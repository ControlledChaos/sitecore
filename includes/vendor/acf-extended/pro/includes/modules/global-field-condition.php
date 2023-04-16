<?php

if(!defined('ABSPATH'))
    exit;

// Check setting
if(!acfe_get_setting('modules/global_field_condition'))
    return;

if(!class_exists('acfe_pro_global_field_condition')):

class acfe_pro_global_field_condition{
    
    public $fields = false;
    public $match = false;
    
    function __construct(){
        
        add_action('acf/render_field_settings', array($this, 'field_settings'), 999);
        
        add_filter('acf/location/rule_types',   array($this, 'rule_types'));
        add_filter('acf/location/rule_values',  array($this, 'rule_values'), 10, 2);
        add_filter('acf/location/rule_match',   array($this, 'rule_match'), 10, 3);
        
        add_filter('acf/validate_field_group',  array($this, 'validate_field_group'), 20, 1);
        add_filter('acf/load_fields',           array($this, 'load_fields'), 10, 2);
        
        add_action('acf/field_group/admin_footer', array($this, 'admin_footer'));
        
    }
    
    function admin_footer(){
        
        $fields = $this->get_fields();
        
        if(empty($fields))
            return;
        
        ?>
        <script>
        (function($){

            if(typeof acf === 'undefined')
                return;
            
            acfe.globalFieldsConditional = <?php echo json_encode($fields); ?>;
            
        })(jQuery);
        </script>
        <?php
        
    }
    
    function field_settings($field){
    
        acf_render_field_setting($field, array(
            'label'             => __('Set as Global Conditional Logic'),
            'name'              => 'acfe_field_group_condition',
            'instructions'      => '',
            'type'              => 'true_false',
            'required'          => false,
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'ui_on_text'        => '',
            'ui_off_text'       => '',
            'conditional_logic' => false,
            'wrapper'           => array(
                'data-after'    => 'conditional_logic'
            )
        ), true);
        
    }
    
    function get_any_fields(&$fields, $field){
        
        // vars
        $sub_fields = acf_maybe_get($field, 'sub_fields');
        $layouts = acf_maybe_get($field, 'layouts');
        
        // unset
        acfe_unset($field, 'sub_fields');
        acfe_unset($field, 'layouts');
        
        // save
        $fields[] = $field;
        
        // Sub Fields
        if($sub_fields){
            
            foreach($sub_fields as $sub_field){
        
                $this->get_any_fields($fields, $sub_field);
                
            }
            
        }
        
        // Layouts
        if($layouts){
        
            foreach($layouts as $layout){
    
                $sub_fields = acf_maybe_get($layout, 'sub_fields');
                
                if(!$sub_fields)
                    continue;
                
                foreach($sub_fields as $sub_field){
    
                    $this->get_any_fields($fields, $sub_field);
                    
                }
            
            }
        
        }
        
        return $fields;
        
    }
    
    function get_fields(){
        
        if($this->fields !== false)
            return $this->fields;
    
        $this->fields = array();
        
        $field_groups = acf_get_field_groups();
    
        if(empty($field_groups))
            return false;
    
        $fields = array();
        $valid_fields = array();
    
        foreach($field_groups as $field_group){
    
            $_fields = acf_get_fields($field_group);
        
            if(empty($_fields))
                continue;
        
            foreach($_fields as $_field){
    
                $this->get_any_fields($fields, $_field);
            
            }
        
        }
    
        if(empty($fields))
            return false;
        
        foreach($fields as $field){
    
            if(!acf_maybe_get($field, 'acfe_field_group_condition'))
                continue;
    
            $valid_fields[] = $field;
            
        }
        
        if(empty($valid_fields))
            return false;
        
        $this->fields = $valid_fields;
    
        return $this->fields;
        
    }
    
    function rule_types($choices){
        
        if(!acf_is_screen('acf-field-group') && !acf_is_ajax())
            return $choices;
        
        $fields = $this->get_fields();
        
        if(empty($fields))
            return $choices;
        
        foreach($fields as $field){
            
            $choices['Global Fields'][$field['key']] = $field['label'] . ' (' . $field['key'] . ')';
            
        }
        
        return $choices;
        
    }
    
    function rule_values($choices, $rule){
        
        if(!acf_is_field_key($rule['param']) || (!acf_is_screen('acf-field-group') && !acf_is_ajax()))
            return $choices;
        
        ob_start();
        
        acf_render_field(array(
            'type'      => 'text',
            'name'      => 'value',
            'prefix'    => 'acf_field_group[location]['.$rule['group'].']['.$rule['id'].']',
            'value'     => (isset($rule['value']) ? $rule['value'] : '')
        ));
        
        return ob_get_clean();
        
    }
    
    
    function rule_match($match, $rule, $screen){
        
        if(!acf_is_field_key($rule['param']))
            return $match;
        
        $this->match = true;
        
        return true;
        
    }
    
    
    function validate_field_group($field_group){
        
        if(!$field_group['location'])
            return $field_group;
        
        // Loop through location groups.
        foreach($field_group['location'] as $k => $group){
            
            // ignore group if no rules.
            if(empty($group))
                continue;
            
            // Do not allow field condition as single location (only use in combination with another rule)
            if(count($group) !== 1)
                continue;
            
            foreach($group as $_k => $rule){
                
                if(!acf_is_field_key($rule['param']))
                    continue;
                
                unset($field_group['location'][$k]);
                
            }
            
        }
        
        return $field_group;
        
    }
    
    function load_fields($fields, $parent){
        
        if(!$this->match)
            return $fields;
        
        if(acfe_is_admin_screen())
            return $fields;
        
        if(!acf_is_field_group_key(acf_maybe_get($parent, 'key')))
            return $fields;
        
        if(empty($parent['location']))
            return $fields;
        
        // Vars
        $conditions = array();
        $i = 0;
        
        // loop
        foreach($parent['location'] as $group_key => $group){
        
            $new_group = false;
        
            foreach($group as $rule){
            
                if(!acf_is_field_key($rule['param']))
                    continue;
            
                $conditions[$i][] = array(
                    'field'     => $rule['param'],
                    'operator'  => $rule['operator'],
                    'value'     => $rule['value'],
                );
    
                $new_group = true;
            
            }
            
            if($new_group)
                $i++;
        
        }
        
        if(empty($conditions))
            return $fields;
        
        $field = array(
            'ID'            => false,
            'parent'        => $parent['ID'],
            'key'           => false,
            'label'         => false,
            'name'          => false,
            'type'          => 'acfe_field_group_condition',
            'instructions'  => false,
            'required'      => false,
            'value'         => false,
            'conditional_logic' => $conditions
        );
    
        array_unshift($fields, $field);
        
        return $fields;
        
    }
    
}

new acfe_pro_global_field_condition();

endif;