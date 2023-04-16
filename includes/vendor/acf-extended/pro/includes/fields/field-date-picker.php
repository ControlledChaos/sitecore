<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_date_picker')):

class acfe_field_date_picker{
    
    function __construct(){
    
        add_action('acf/render_field_settings/type=date_picker',            array($this, 'date_render_field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=date_picker',        array($this, 'date_field_wrapper_attributes'), 10, 2);
    
        add_action('acf/render_field_settings/type=date_time_picker',       array($this, 'date_time_render_field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=date_time_picker',   array($this, 'date_time_field_wrapper_attributes'), 10, 2);
    
        add_action('acf/render_field_settings/type=time_picker',            array($this, 'time_render_field_settings'));
        add_filter('acfe/field_wrapper_attributes/type=time_picker',        array($this, 'time_field_wrapper_attributes'), 10, 2);
        
    }
    
    function date_render_field_settings($field){
    
        acf_render_field_setting($field, array(
            'label'         => __('Placeholder', 'acf'),
            'name'          => 'placeholder',
            'key'           => 'placeholder',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
        ));
    
        acf_render_field_setting($field, array(
            'label'         => __('Date Restriction'),
            'name'          => 'min_date',
            'key'           => 'min_date',
            'instructions'  => 'Enter a date based on the "Display Format" setting. Relative dates must contain value and period pairs; valid periods are <code>y</code> for years, <code>m</code> for months, <code>w</code> for weeks, and <code>d</code> for days.
            <br /><br />
            For example, <code>+1m +7d</code> represents one month and seven days from today. <a href="https://api.jqueryui.com/datepicker/#option-minDate" target="_blank">See documentation</a>',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Min Date',
            'placeholder'   => 'd/m/Y'
        ));
    
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_date',
            'key'           => 'max_date',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Max Date',
            'placeholder'   => 'd/m/Y',
            '_append'       => 'min_date'
        ));
    
        acf_render_field_setting($field, array(
            'label'         => __('No Weekends', 'acf'),
            'name'          => 'no_weekends',
            'key'           => 'no_weekends',
            'instructions'  => '',
            'type'          => 'true_false',
            'ui'            => true,
        ));
        
    }
    
    function date_field_wrapper_attributes($wrapper, $field){
        
        // Min Date
        $min_date = acf_maybe_get($field, 'min_date');
        
        if($min_date){
            $wrapper['data-min_date'] = $min_date;
        }
        
        // Max Date
        $max_date = acf_maybe_get($field, 'max_date');
    
        if($max_date){
            $wrapper['data-max_date'] = $max_date;
        }
        
        // Placeholder
        $placeholder = acf_maybe_get($field, 'placeholder');
    
        if($placeholder){
            $wrapper['data-placeholder'] = $placeholder;
        }
        
        // No Weekends
        $no_weekends = acf_maybe_get($field, 'no_weekends');
    
        if($no_weekends){
            $wrapper['data-no_weekends'] = true;
        }
        
        return $wrapper;
        
    }
    
    function date_time_render_field_settings($field){
        
        acf_render_field_setting($field, array(
            'label'         => __('Placeholder', 'acf'),
            'name'          => 'placeholder',
            'key'           => 'placeholder',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
        ));
        
        // Date
        acf_render_field_setting($field, array(
            'label'         => __('Date Restriction'),
            'name'          => 'min_date',
            'key'           => 'min_date',
            'instructions'  => 'Enter a date based on the "Display Format" setting. Relative dates must contain value and period pairs; valid periods are <code>y</code> for years, <code>m</code> for months, <code>w</code> for weeks, and <code>d</code> for days.
            <br /><br />
            For example, <code>+1m +7d</code> represents one month and seven days from today. <a href="https://api.jqueryui.com/datepicker/#option-minDate" target="_blank">See documentation</a>',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Min Date',
            'placeholder'   => 'd/m/Y'
        ));
        
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_date',
            'key'           => 'max_date',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Max Date',
            'placeholder'   => 'd/m/Y',
            '_append'       => 'min_date'
        ));
    
        // Min Time
        acf_render_field_setting($field, array(
            'label'         => __('Time Restriction'),
            'name'          => 'min_time',
            'key'           => 'min_time',
            'instructions'  => 'String of the minimum time allowed. <code>11:00</code> will restrict to times after 11am. <a href="https://trentrichardson.com/examples/timepicker/" target="_blank">See documentation</a>',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Min Time',
            'placeholder'   => '09:00'
        ));
    
        // Max Time
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_time',
            'key'           => 'max_time',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Max Time',
            'placeholder'   => '18:00',
            '_append'       => 'min_time'
        ));
    
        acf_render_field_setting($field, array(
            'label'         => __('No Weekends', 'acf'),
            'name'          => 'no_weekends',
            'key'           => 'no_weekends',
            'instructions'  => '',
            'type'          => 'true_false',
            'ui'            => true,
        ));
        
        // Hour
        acf_render_field_setting($field, array(
            'label'         => __('Hour Restriction'),
            'name'          => 'min_hour',
            'key'           => 'min_hour',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Hour',
            'placeholder'   => ''
        ));
        
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_hour',
            'key'           => 'max_hour',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Hour',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        // Min
        acf_render_field_setting($field, array(
            'label'         => __('Minutes Restriction'),
            'name'          => 'min_min',
            'key'           => 'min_min',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Min.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
    
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_min',
            'key'           => 'max_min',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Min.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        // Sec
        acf_render_field_setting($field, array(
            'label'         => __('Seconds Restriction'),
            'name'          => 'min_sec',
            'key'           => 'min_sec',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Sec.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
    
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_sec',
            'key'           => 'max_sec',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Sec.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
    }
    
    function date_time_field_wrapper_attributes($wrapper, $field){
        
        // Min Date
        $min_date = acf_maybe_get($field, 'min_date');
        
        if($min_date){
            $wrapper['data-min_date'] = $min_date;
        }
        
        // Max Date
        $max_date = acf_maybe_get($field, 'max_date');
        
        if($max_date){
            $wrapper['data-max_date'] = $max_date;
        }
        
        // Placeholder
        $placeholder = acf_maybe_get($field, 'placeholder');
        
        if($placeholder){
            $wrapper['data-placeholder'] = $placeholder;
        }
    
        // No Weekends
        $no_weekends = acf_maybe_get($field, 'no_weekends');
    
        if($no_weekends){
            $wrapper['data-no_weekends'] = true;
        }
    
        // Hour
        $min_hour = acf_maybe_get($field, 'min_hour');
    
        if($min_hour){
            $wrapper['data-min_hour'] = $min_hour;
        }
    
        $max_hour = acf_maybe_get($field, 'max_hour');
    
        if($max_hour){
            $wrapper['data-max_hour'] = $max_hour;
        }
    
        // Min
        $min_min = acf_maybe_get($field, 'min_min');
    
        if($min_min){
            $wrapper['data-min_min'] = $min_min;
        }
    
        $max_min = acf_maybe_get($field, 'max_min');
    
        if($max_min){
            $wrapper['data-max_min'] = $max_min;
        }
    
        // Sec
        $min_sec = acf_maybe_get($field, 'min_sec');
    
        if($min_sec){
            $wrapper['data-min_sec'] = $min_sec;
        }
    
        $max_sec = acf_maybe_get($field, 'max_sec');
    
        if($max_sec){
            $wrapper['data-max_sec'] = $max_sec;
        }
        
        // Min Time
        $min_time = acf_maybe_get($field, 'min_time');
    
        if($min_time){
            $wrapper['data-min_time'] = $min_time;
        }
    
        // Max Time
        $max_time = acf_maybe_get($field, 'max_time');
    
        if($max_time){
            $wrapper['data-max_time'] = $max_time;
        }
        
        return $wrapper;
        
    }
    
    function time_render_field_settings($field){
        
        acf_render_field_setting($field, array(
            'label'         => __('Placeholder', 'acf'),
            'name'          => 'placeholder',
            'key'           => 'placeholder',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
        ));
        
        // Min Time
        acf_render_field_setting($field, array(
            'label'         => __('Time Restriction'),
            'name'          => 'min_time',
            'key'           => 'min_time',
            'instructions'  => 'String of the minimum time allowed. <code>11:00</code> will restrict to times after 11am. <a href="https://trentrichardson.com/examples/timepicker/" target="_blank">See documentation</a>',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Min Time',
            'placeholder'   => '09:00'
        ));
        
        // Max Time
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_time',
            'key'           => 'max_time',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Max Time',
            'placeholder'   => '18:00',
            '_append'       => 'min_time'
        ));
        
        // Hour
        acf_render_field_setting($field, array(
            'label'         => __('Hour Restriction'),
            'name'          => 'min_hour',
            'key'           => 'min_hour',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Hour',
            'placeholder'   => ''
        ));
        
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_hour',
            'key'           => 'max_hour',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Hour',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        // Min
        acf_render_field_setting($field, array(
            'label'         => __('Minutes Restriction'),
            'name'          => 'min_min',
            'key'           => 'min_min',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Min.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_min',
            'key'           => 'max_min',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Min.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        // Sec
        acf_render_field_setting($field, array(
            'label'         => __('Seconds Restriction'),
            'name'          => 'min_sec',
            'key'           => 'min_sec',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Sec.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_sec',
            'key'           => 'max_sec',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Sec.',
            'placeholder'   => '',
            '_append'       => 'min_hour'
        ));
        
    }
    
    function time_field_wrapper_attributes($wrapper, $field){
        
        // Placeholder
        $placeholder = acf_maybe_get($field, 'placeholder');
        
        if($placeholder){
            $wrapper['data-placeholder'] = $placeholder;
        }
        
        // Hour
        $min_hour = acf_maybe_get($field, 'min_hour');
        
        if($min_hour){
            $wrapper['data-min_hour'] = $min_hour;
        }
        
        $max_hour = acf_maybe_get($field, 'max_hour');
        
        if($max_hour){
            $wrapper['data-max_hour'] = $max_hour;
        }
        
        // Min
        $min_min = acf_maybe_get($field, 'min_min');
        
        if($min_min){
            $wrapper['data-min_min'] = $min_min;
        }
        
        $max_min = acf_maybe_get($field, 'max_min');
        
        if($max_min){
            $wrapper['data-max_min'] = $max_min;
        }
        
        // Sec
        $min_sec = acf_maybe_get($field, 'min_sec');
        
        if($min_sec){
            $wrapper['data-min_sec'] = $min_sec;
        }
        
        $max_sec = acf_maybe_get($field, 'max_sec');
        
        if($max_sec){
            $wrapper['data-max_sec'] = $max_sec;
        }
        
        // Min Time
        $min_time = acf_maybe_get($field, 'min_time');
        
        if($min_time){
            $wrapper['data-min_time'] = $min_time;
        }
        
        // Max Time
        $max_time = acf_maybe_get($field, 'max_time');
        
        if($max_time){
            $wrapper['data-max_time'] = $max_time;
        }
        
        return $wrapper;
        
    }
    
}

new acfe_field_date_picker();

endif;