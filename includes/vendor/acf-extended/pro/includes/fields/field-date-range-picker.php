<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_date_range_picker')):

class acfe_field_date_range_picker extends acf_field{
    
    var $sub_fields;
    
    function initialize(){
        
        $this->name = 'acfe_date_range_picker';
        $this->label = __('Date Range Picker', 'acfe');
        $this->category = 'jquery';
        $this->defaults = array(
            'display_format'    => 'd/m/Y',
            'return_format'     => 'd/m/Y',
            'first_day'         => 1,
            'placeholder'       => '',
            'separator'         => '-',
            'default_start'     => '',
            'default_end'       => '',
            'min_days'          => '',
            'max_days'          => '',
            'min_date'          => '',
            'max_date'          => '',
            'custom_ranges'     => array(),
            'no_weekends'       => false,
            'auto_close'        => false,
            'allow_null'        => false,
        );
    
        $this->sub_fields = array('start', 'end');
        
    }
    
    function render_field_settings($field){
    
        // global
        global $wp_locale;
    
        // vars
        $d_m_Y = date_i18n('d/m/Y');
        $m_d_Y = date_i18n('m/d/Y');
        $F_j_Y = date_i18n('F j, Y');
        $Ymd = date_i18n('Ymd');
        
        // display format
        acf_render_field_setting($field, array(
            'label'         => __('Display Format','acf'),
            'instructions'  => __('The format displayed when editing a post','acf'),
            'type'          => 'radio',
            'name'          => 'display_format',
            'other_choice'  => 1,
            'choices'       => array(
                'd/m/Y'         => '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
                'm/d/Y'         => '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
                'F j, Y'        => '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
                'other'         => '<span>' . __('Custom:','acf') . '</span>'
            )
        ));
        
        // return format
        acf_render_field_setting($field, array(
            'label'         => __('Return Format','acf'),
            'instructions'  => __('The format returned via template functions','acf'),
            'type'          => 'radio',
            'name'          => 'return_format',
            'other_choice'  => 1,
            'choices'       => array(
                'd/m/Y'         => '<span>' . $d_m_Y . '</span><code>d/m/Y</code>',
                'm/d/Y'         => '<span>' . $m_d_Y . '</span><code>m/d/Y</code>',
                'F j, Y'        => '<span>' . $F_j_Y . '</span><code>F j, Y</code>',
                'Ymd'           => '<span>' . $Ymd . '</span><code>Ymd</code>',
                'other'         => '<span>' . __('Custom:','acf') . '</span>'
            )
        ));
        
        // first day
        acf_render_field_setting($field, array(
            'label'         => __('Week Starts On','acf'),
            'instructions'  => '',
            'type'          => 'select',
            'name'          => 'first_day',
            'choices'       => array_values($wp_locale->weekday)
        ));
    
        // placeholder
        acf_render_field_setting($field, array(
            'label'         => __('Placeholder','acf'),
            'instructions'  => '',
            'name'          => 'placeholder',
            'type'          => 'text',
        ));
    
        // separator
        acf_render_field_setting($field, array(
            'label'         => __('Separator','acf'),
            'instructions'  => '',
            'name'          => 'separator',
            'type'          => 'text',
        ));
        
        // Default Start
        acf_render_field_setting($field, array(
            'label'         => __('Default Date'),
            'name'          => 'default_start',
            'key'           => 'default_start',
            'placeholder'   => $field['display_format'],
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Default Start',
            'append'        => 'date',
        ));
        
        // Default End
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'default_end',
            'key'           => 'default_end',
            'placeholder'   => $field['display_format'],
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Default End',
            'append'        => 'date',
            '_append'       => 'default_start'
        ));
        
        // Min Days
        acf_render_field_setting($field, array(
            'label'         => __('Range Restriction'),
            'name'          => 'min_days',
            'key'           => 'min_days',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Min Range',
            'append'        => 'days',
        ));
        
        // Max Days
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_days',
            'key'           => 'max_days',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'default_value' => '',
            'prepend'       => 'Max Range',
            'append'        => 'days',
            '_append'       => 'min_days'
        ));
        
        // Min Date
        acf_render_field_setting($field, array(
            'label'         => __('Date Restriction'),
            'name'          => 'min_date',
            'key'           => 'min_date',
            'placeholder'   => $field['display_format'],
            'instructions'  => 'Enter a date based on the "Display Format" setting. Relative dates must be compatible with <code>strtotime()</code> PHP function.
            <br /><br />
            For example, <code>+1 month +7 days</code> represents one month and seven days from today. <a href="https://www.php.net/manual/en/datetime.formats.relative.php" target="_blank">See documentation</a>',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Min Date',
            'append'        => 'date',
        ));
        
        // max Date
        acf_render_field_setting($field, array(
            'label'         => '',
            'name'          => 'max_date',
            'key'           => 'max_date',
            'instructions'  => '',
            'type'          => 'text',
            'default_value' => '',
            'prepend'       => 'Max Date',
            'append'        => 'date',
            'placeholder'   => $field['display_format'],
            '_append'       => 'min_date'
        ));
    
        // Custom Ranges
        acf_render_field_setting($field, array(
            'label'         => __('Custom Ranges','acf'),
            'instructions'  => '',
            'type'          => 'checkbox',
            'name'          => 'custom_ranges',
            'layout'        => 'horizontal',
            'choices'       => array(
                'Today'         => 'Today',
                'Yesterday'     => 'Yesterday',
                'Last 7 Days'   => 'Last 7 Days',
                'Last 30 Days'  => 'Last 30 Days',
                'This Month'    => 'This Month',
                'Last Month'    => 'Last Month',
            )
        ));
        
        // No weekends
        acf_render_field_setting($field, array(
            'label'         => __('No Weekends', 'acf'),
            'name'          => 'no_weekends',
            'key'           => 'no_weekends',
            'instructions'  => '',
            'type'          => 'true_false',
            'ui'            => true,
        ));
        
        // Auto Close
        acf_render_field_setting($field, array(
            'label'         => __('Auto Close on Selection', 'acf'),
            'name'          => 'auto_close',
            'key'           => 'auto_close',
            'instructions'  => '',
            'type'          => 'true_false',
            'ui'            => true,
        ));
    
        // Allow null
        acf_render_field_setting($field, array(
            'label'         => __('Allow null', 'acf'),
            'name'          => 'allow_null',
            'key'           => 'allow_null',
            'instructions'  => '',
            'type'          => 'true_false',
            'ui'            => true,
        ));
    
    }
    
    function input_admin_enqueue_scripts(){
        
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        
        // register
        wp_register_script('acfe-date-range-picker', acfe_get_url('pro/assets/inc/daterangepicker/daterangepicker' . $suffix . '.js'), array('acf-input', 'moment'), '3.1');
        wp_register_style('acfe-date-range-picker', acfe_get_url('pro/assets/inc/daterangepicker/daterangepicker' . $suffix . '.css'), array(), '3.1');
        
        // enqueue if gutenberg
        if(acfe_is_block_editor()){
            
            wp_enqueue_script('moment');
            wp_enqueue_script('acfe-date-range-picker');
            wp_enqueue_style('acfe-date-range-picker');
            
        }
        
    }
    
    function prepare_field($field){
        
        // Value already exists or default not set
        if($field['value'] !== null || empty($field['default_start']) || empty($field['default_end'])){
            return $field;
        }
        
        // vars
        $default_start = $field['default_start'];
        $default_end = $field['default_end'];
        
        if(!empty($field['default_start'])){
            
            $is_date = DateTime::createFromFormat($field['display_format'], $field['default_start']);
        
            if(!$is_date){
            
                $date = strtotime($field['default_start']);
                $default_start = date_i18n('Ymd', $date);
            
            }
        
        }
    
        if(!empty($field['default_end'])){
        
            $is_date = DateTime::createFromFormat($field['display_format'], $field['default_end']);
        
            if(!$is_date){
            
                $date = strtotime($field['default_end']);
                $default_end = date_i18n('Ymd', $date);
            
            }
        
        }
        
        $field['value'] = $default_start . '-' . $default_end;
        
        return $field;
        
    }
    
    function render_field($field){
        
        // Enqueue
        wp_enqueue_script('moment');
        wp_enqueue_script('acfe-date-range-picker');
        wp_enqueue_style('acfe-date-range-picker');
        
        // Vars
        $value = $field['value'];
        $separator = $field['separator'] ? ' ' . $field['separator'] . ' ' : ' ';
        
        // Input
        $hidden_value = '';
        $display_value = '';
        
        if(acf_maybe_get($value, 'start') && acf_maybe_get($value, 'end')){
    
            $hidden_value = acf_format_date($value['start'], 'Ymd') . '-' . acf_format_date($value['end'], 'Ymd');
            $display_value = acf_format_date($value['start'], $field['display_format']) . $separator . acf_format_date($value['end'], $field['display_format']);
        
        }
        
        // Elements
        $div = array(
            'class'                 => "acfe-date-range-picker acf-input-wrap {$field['class']}",
            'data-display_format'   => $this->convert_php_to_momentjs_format($field['display_format']),
            'data-first_day'        => $field['first_day'],
            'data-separator'        => $field['separator'],
            'data-min_days'         => $field['min_days'],
            'data-max_days'         => $field['max_days'],
            'data-custom_ranges'    => $field['custom_ranges'],
            'data-no_weekends'      => $field['no_weekends'],
            'data-auto_close'       => $field['auto_close'],
            'data-allow_null'       => $field['allow_null'],
        );
        
        if($field['min_date']){
    
            $div['data-min_date'] = $field['min_date'];
            
            $is_date = DateTime::createFromFormat($field['display_format'], $field['min_date']);
            
            if(!$is_date){
    
                $date = strtotime($field['min_date']);
                $div['data-min_date'] = date_i18n($field['display_format'], $date);
                
            }
            
        }
        
        if($field['max_date']){
    
            $div['data-max_date'] = $field['max_date'];
    
            $is_date = DateTime::createFromFormat($field['display_format'], $field['max_date']);
    
            if(!$is_date){
        
                $date = strtotime($field['max_date']);
                $div['data-max_date'] = date_i18n($field['display_format'], $date);
        
            }
            
        }
    
        $hidden_input = array(
            'id'                => $field['id'],
            'name'              => $field['name'],
            'value'             => $hidden_value,
        );
        
        $text_input = array(
            'class'             => 'input',
            'inputmode'         => 'none',
            'placeholder'       => $field['placeholder'],
            'value'             => $display_value,
        );
        
        // html
        ?>
        <div <?php echo acf_esc_attrs($div); ?>>
            
            <?php acf_hidden_input($hidden_input); ?>
            <?php acf_text_input($text_input); ?>
            
        </div>
        <?php
        
    }
    
    function update_value($value, $post_id, $field){
    
        // update sub field value
        if($this->is_sub_field($field)){
            return $value;
        }
        
        // input value
        if(!is_array($value)){
            
            $values = explode('-', $value);
    
            // re-create value array
            $value = array(
                'start' => acf_maybe_get($values, 0), // first
                'end'   => acf_maybe_get($values, 1), // second
            );
            
        }
    
        // clone
        $sub_field = $field;
    
        // loop sub fields
        foreach($this->sub_fields as $name){
    
            // allow partial array update
            if(!isset($value[ $name ])) continue;
        
            // assign new name "{group_date_picker}_{start}"
            $sub_field['name'] = "{$field['name']}_{$name}";
            $sub_value = acf_maybe_get($value, $name);
        
            // update sub field
            acf_update_value($sub_value, $post_id, $sub_field);
        
        }
        
        // save empty
        return false;
        
    }
    
    function load_value($value, $post_id, $field){
    
        // load sub field value
        if($this->is_sub_field($field)){
            return $value;
        }
    
        // clone
        $sub_field = $field;
        
        // default values
        $values = array(
            'start' => '',
            'end'   => '',
        );
    
        // loop sub fields
        foreach($this->sub_fields as $name){
        
            // assign new name "{group_date_picker}_{start}"
            $sub_field['name'] = "{$field['name']}_{$name}";
            $sub_value = acf_get_value($post_id, $sub_field);
        
            if($sub_value){
                $values[ $name ] = $sub_value;
            }
        
        }
        
        return $values;
        
    }
    
    function format_value($value, $post_id, $field){
    
        // empty
        if(empty($value)){
            return $value;
        }
    
        // format sub field value
        if($this->is_sub_field($field)){
            
            return acf_format_date($value, $field['return_format']);
            
        }
        
        // format parent value
        $value['start'] = acf_format_date($value['start'], $field['return_format']);
        $value['end'] = acf_format_date($value['end'], $field['return_format']);
        
        // return
        return $value;
        
    }
    
    function delete_value($post_id, $field_name, $field){
        
        // sub field
        if($this->is_sub_field($field)){
            return;
        }
        
        // clone
        $sub_field = $field;
        
        // loop sub fields
        foreach($this->sub_fields as $name){
            
            // assign new name "{group_date_picker}_{start}"
            $sub_field['name'] = "{$field['name']}_{$name}";
            
            // delete
            acf_delete_value($post_id, $sub_field);
            
        }
        
    }
    
    /*
     * Convert PHP Date to MomentJS format
     * https://stackoverflow.com/a/55173613
     */
    function convert_php_to_momentjs_format($php_date){
        
        $replacements = array(
            'A' => 'A',      // for the sake of escaping below
            'a' => 'a',      // for the sake of escaping below
            'B' => '',       // Swatch internet time (.beats), no equivalent
            'c' => 'YYYY-MM-DD[T]HH:mm:ssZ', // ISO 8601
            'D' => 'ddd',
            'd' => 'DD',
            'e' => 'zz',     // deprecated since version 1.6.0 of moment.js
            'F' => 'MMMM',
            'G' => 'H',
            'g' => 'h',
            'H' => 'HH',
            'h' => 'hh',
            'I' => '',       // Daylight Saving Time? => moment().isDST();
            'i' => 'mm',
            'j' => 'D',
            'L' => '',       // Leap year? => moment().isLeapYear();
            'l' => 'dddd',
            'M' => 'MMM',
            'm' => 'MM',
            'N' => 'E',
            'n' => 'M',
            'O' => 'ZZ',
            'o' => 'YYYY',
            'P' => 'Z',
            'r' => 'ddd, DD MMM YYYY HH:mm:ss ZZ', // RFC 2822
            'S' => 'o',
            's' => 'ss',
            'T' => 'z',      // deprecated since version 1.6.0 of moment.js
            't' => '',       // days in the month => moment().daysInMonth();
            'U' => 'X',
            'u' => 'SSSSSS', // microseconds
            'v' => 'SSS',    // milliseconds (from PHP 7.0.0)
            'W' => 'W',      // for the sake of escaping below
            'w' => 'e',
            'Y' => 'YYYY',
            'y' => 'YY',
            'Z' => '',       // time zone offset in minutes => moment().zone();
            'z' => 'DDD',
        );
        
        // Converts escaped characters.
        foreach($replacements as $from => $to){
            
            $replacements['\\' . $from] = '[' . $from . ']';
            
        }
        
        return strtr($php_date, $replacements);
        
    }
    
    function is_sub_field($field){
        
        // try to retrieve real field name
        $_name = acf_maybe_get($field, '_name');
        
        // loop sub fields
        foreach($this->sub_fields as $sub_field){
            
            // ends with "{my_field}_{sub_field}"
            if(acfe_ends_with($field['name'], "{$_name}_{$sub_field}")){
                return $sub_field;
            }
            
        }
        
        return false;
        
    }
    
}

// initialize
acf_register_field_type('acfe_field_date_range_picker');

endif;