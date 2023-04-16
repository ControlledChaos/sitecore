<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('ACFE_World_Data')):

class ACFE_World_Data{
    
    public $countries;
    public $languages;
    public $currencies;
    
    function __construct(){
        
        // Data
        $this->countries = acfe_include('pro/includes/data/countries.php');
        $this->languages = acfe_include('pro/includes/data/languages.php');
        $this->currencies = acfe_include('pro/includes/data/currencies.php');
    
        // Localize Names
        if(function_exists('locale_get_display_region')){
        
            // Get Locale
            $locale = acf_get_locale();
            
            // Loop
            foreach(array_keys($this->countries) as $code){
                
                $this->countries[$code]['localized'] = locale_get_display_region("-$code", $locale);
                
            }
        
        }
        
    }
    
}

endif;

if(!class_exists('ACFE_World_Query')):

class ACFE_World_Query{
    
    // Vars
    public $type;
    public $args;
    public $data;
    
    /*
     * Construct
     */
    function __construct($args){
        
        // setup
        $this->type = acf_extract_var($args, 'type', 'countries');
        $this->data = acf_get_instance('ACFE_World_Data')->{$this->type};
        $this->args = $args;
        
        // validate
        $this->validate();
        
        // filter
        $this->filter();
        
        // order
        $this->order();
        
    }
    
    /*
     * Validate
     */
    function validate(){
    
        $this->args['orderby'] = (!$this->args['orderby'] && $this->args['field']) ? $this->args['field'] : $this->args['orderby'];
        $this->args['orderby'] = $this->args['orderby'] ? $this->args['orderby'] : 'code';
        
    }
    
    /*
     * Filter
     */
    function filter(){
        
        // vars
        $args = $this->args;
        $data = $this->data;
        
        // generate rules
        $_args = array_keys($args);
        $rules = array();
        
        // loop
        foreach($_args as $rule){
            
            // vars
            $split = explode('__', $rule);
            $key = $split[0];
            
            // filter
            if(acf_maybe_get($split, 1) !== 'in') continue;
            
            // plural keys
            if($key === 'language') $key = 'languages';
            if($key === 'country')  $key = 'countries';
            if($key === 'currency') $key = 'currencies';
            
            // add rule:
            // name == name__in
            $rules[ $key ] = $rule;
            
        }
        
        // loop thru data
        foreach(array_keys($data) as $key){
            
            // vars
            $row = $data[$key];
            $valid = true;
            
            // Loop thru rules
            foreach($rules as $r_key => $rule){
                
                // filter
                if(!$args[$rule]) continue;
                
                $args[$rule] = acf_get_array($args[$rule]);                  // array('us', 'fr', 'de')
                $is_string = isset($row[$r_key]) && !is_array($row[$r_key]); // $data['fr_FR']['locale']
                
                // string
                if($is_string){
                    
                    if(in_array($row[$r_key], $args[$rule])) continue;
                    
                    $valid = false;
                    
                // array
                }else{
                    
                    $found = false;
                    
                    foreach($row[$r_key] as $sub_row){
                        
                        if(!in_array($sub_row, $args[$rule])) continue;
                        
                        $found = true;
                        
                    }
                    
                    if(!$found){
                        $valid = false;
                    }
                    
                }
                
                
            }
            
            // Do nothing
            if($valid) continue;
            
            // unset
            unset($data[$key]);
            
        }
        
        // Set data
        $this->data = $data;
        
    }
    
    /*
     * Order
     */
    function order(){
    
        // Vars
        $args = $this->args;
        $data = $this->data;
        
        // Prepare
        $orderby = $args['orderby'];
        $order = $args['order'];
        $columns = explode('__', $orderby);
        
        // Orderby: key
        if(acf_maybe_get($columns, 1) !== 'in'){
            
            $data = wp_list_sort($data, $orderby, $order, true);
            
        // Orderby: name__in
        }else{
            
            $key = $columns[0];                         // name
            $array = acf_get_array($args[$orderby]);    // array('fr', 'us', 'de')
            
            uasort($data, function($a, $b) use($key, $array, $order){
                
                // ASC
                $value_a = $a[$key];
                $value_b = $b[$key];
                
                // DESC
                if($order === 'DESC'){
                    $value_a = $b[$key];
                    $value_b = $a[$key];
                }
                
                // Position
                $pos_a = array_search($value_a, $array);
                $pos_b = array_search($value_b, $array);
                
                // Calculate
                return $pos_a - $pos_b;
                
            });
            
        }
        
        /*
         * Offset
         */
        if($args['offset'] > 0){
            
            $data = array_slice($data, $args['offset']);
            
        }
        
        /*
         * Limit
         */
        if($args['limit'] > 0){
            
            $data = array_slice($data, 0, $args['limit']);
            
        }
        
        /*
         * Clone
         */
        $_data = $data;
        
        /*
         * Field
         */
        if($args['field']){
            
            $data = wp_list_pluck($data, $args['field']);
    
            // Display
            if($args['display'] !== false){
        
                foreach(array_keys($data) as $code){
            
                    $display = $args['display'];
            
                    if(preg_match_all('/{(.*?)}/', $display, $matches)){
                
                        foreach($matches[1] as $i => $tag){
                            $value = acf_maybe_get($_data[$code], $tag);
                            $display = str_replace('{' . $tag . '}', $value, $display);
                        }
    
                        $display = str_replace('{' . $tag . '}', '', $display);
                
                    }
            
                    $data[$code] = $display;
            
                }
        
            }
            
            // Prepend
            if($args['prepend'] !== false){
                
                foreach(array_keys($data) as $code){
                    
                    $prepend = $args['prepend'];
                    
                    if(preg_match_all('/{(.*?)}/', $prepend, $matches)){
                        
                        foreach($matches[1] as $i => $tag){
                            $value = acf_maybe_get($_data[$code], $tag);
                            $prepend = str_replace('{' . $tag . '}', $value, $prepend);
                        }
                        
                        $prepend = str_replace('{' . $tag . '}', '', $prepend);
                        
                    }
                    
                    $data[$code] = $prepend . $data[$code];
                    
                }
                
            }
            
            // Append
            if($args['append'] !== false){
                
                foreach(array_keys($data) as $code){
                    
                    $append = $args['append'];
                    
                    if(preg_match_all('/{(.*?)}/', $append, $matches)){
                        
                        foreach($matches[1] as $i => $tag){
                            $value = acf_maybe_get($_data[$code], $tag);
                            $append = str_replace('{' . $tag . '}', $value, $append);
                        }
                        
                        $append = str_replace('{' . $tag . '}', '', $append);
                        
                    }
                    
                    $data[$code] = $data[$code] . $append;
                    
                }
                
            }
            
        }
        
        /*
         * Groupby
         */
        if($args['groupby']){
            
            $groups = array();
            
            foreach($_data as $code => $row){
                
                if(!isset($row[ $args['groupby'] ]))
                    break;
                
                $groups[ $row[ $args['groupby'] ] ][ $code ] = $data[$code];
                
            }
            
            if($groups){
                
                // Sort Group ASC
                ksort($groups);
                
                // Assign data
                $data = $groups;
                
            }
            
        }
    
        // Set data
        $this->data = $data;
        
    }
    
    /*
     * Get
     */
    function get(){
        
        return $this->data;
        
    }
    
}

endif;