<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_location_post_date_time')):

class acfe_location_post_date_time extends acfe_location{
    
    function initialize(){
        
        $this->name     = 'post_date_time';
        $this->label    = __('Post Date Time', 'acf');
        $this->category = 'post';
        
    }
    
    function rule_values($choices, $rule){
    
        if(!acf_is_screen('acf-field-group') && !acf_is_ajax('acf/field_group/render_location_rule')){
            
            $value = acf_format_date($rule['value'], 'd/m/Y H:i:s');
        
            return array(
                $rule['value'] => $value
            );
        
        }
    
        ob_start();

        acf_render_field_wrap(array(
            'type'      => 'date_time_picker',
            'name'      => 'value',
            'prefix'    => 'acf_field_group[location]['.$rule['group'].']['.$rule['id'].']',
            'value'     => (isset($rule['value']) ? $rule['value'] : '')
        ));
    
        ?>
        <script>
        (function($){

            if(typeof acf === 'undefined')
                return;
            
            acf.doAction('acfe/field_group/rule_refresh');

        })(jQuery);
        </script>
        <?php
    
        return ob_get_clean();
        
    }
    
    function rule_operators($choices, $rule){
    
        $choices['<']   = __('is less than', 'acf');
        $choices['<=']  = __('is less or equal to', 'acf');
        $choices['>']   = __('is greater than', 'acf');
        $choices['>=']  = __('is greater or equal to', 'acf');
    
        return $choices;
        
    }
    
    function rule_match($result, $rule, $screen){
    
        // Vars
        $post_id = acf_maybe_get($screen, 'post_id');
        $post_type = acf_maybe_get($screen, 'post_type');
    
        // Bail early
        if(!$post_id || !$post_type)
            return false;
    
        $post_date = get_post_field('post_date', $post_id);
    
        if(!$post_date)
            return false;
        
        $post_date = acf_format_date($post_date, 'U');
        $rule['value'] = acf_format_date($rule['value'], 'U');
    
        // Compare
        return $this->compare_advanced($post_date, $rule);
        
    }
    
}

acf_register_location_rule('acfe_location_post_date_time');

endif;