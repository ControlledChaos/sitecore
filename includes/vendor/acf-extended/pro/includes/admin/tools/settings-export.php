<?php 

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_settings_export')):

class acfe_settings_export extends ACF_Admin_Tool{
    
    function initialize(){
        
        // vars
        $this->name = 'acfe_settings_export';
        $this->title = __('Export Settings');
        
    }
    
    function html(){
        
        // Single
        if($this->is_active()){
            
            $this->html_single();
            
        // Archive
        }else{
            
            $this->html_archive();
            
        }
        
    }
    
    function html_archive(){
        
        ?>
        <p>Export ACF Settings</p>
        
        <?php
        
        $settings = acfe_get_settings('settings');
        $disabled = empty($settings) ? 'disabled="disabled"' : ''; ?>
        
        <p class="acf-submit">

            <button type="submit" name="action" class="button button-primary" value="json" <?php echo $disabled; ?>><?php _e('Export File'); ?></button>
            <button type="submit" name="action" class="button" value="php" <?php echo $disabled; ?>><?php _e('Generate PHP'); ?></button>
            
        </p>
        <?php
        
    }
    
    function html_single(){
        
        ?>
        <div class="acf-postbox-columns">
            <div class="acf-postbox-main">
                
                <p><?php _e("You can copy and paste the following code to your theme's functions.php file or include it within an external file.", 'acf'); ?></p>
                
                <?php

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
                
                ?>
                
                <div id="acf-admin-tool-export">
                    <textarea id="acf-export-textarea" readonly="true"><?php
                    echo "add_action('acf/init', 'my_acf_settings');" . "\r\n";
                    echo "function my_acf_settings(){" . "\r\n" . "\r\n";

                    foreach($this->data as $name => $value){
    
                        // code
                        $code = var_export($value, true);
    
                        // change double spaces to tabs
                        $code = str_replace(array_keys($str_replace), array_values($str_replace), $code);
    
                        // correctly formats "=> array("
                        $code = preg_replace(array_keys($preg_replace), array_values($preg_replace), $code);
    
                        // esc_textarea
                        $esc_code = esc_textarea($code);
    
                        if($code === "'1'"){
                            $esc_code = 'true';
                        }elseif($code === "'0'"){
                            $esc_code = 'false';
                        }
    
                        // echo
                        echo "    acf_update_setting('{$name}', {$esc_code});" . "\r\n";
    
                    }

                    echo "\r\n" . "}";
                    ?></textarea>
                </div>
                
                <p class="acf-submit">
                    <a class="button" id="acf-export-copy"><?php _e( 'Copy to clipboard', 'acf' ); ?></a>
                </p>
                
                <script type="text/javascript">
                (function($){
                    
                    var $a = $('#acf-export-copy');
                    var $textarea = $('#acf-export-textarea');
                    
                    if(!document.queryCommandSupported('copy')){
                        return $a.remove();
                    }
                    
                    $a.on('click', function(e){
                        
                        e.preventDefault();
                        
                        $textarea.get(0).select();
                        
                        try{
                            
                            // copy
                            var copy = document.execCommand('copy');
                            if(!copy)
                                return;
                            
                            // tooltip
                            acf.newTooltip({
                                text:       "<?php _e('Copied', 'acf' ); ?>",
                                timeout:    250,
                                target:     $(this),
                            });
                            
                        }catch(err){
                            // do nothing
                        }
                        
                    });
                
                })(jQuery);
                </script>
            </div>
        </div>
        <?php
    
    }
    
    function load(){
        
        if(!$this->is_active())
            return;
            
        $this->action = $this->get_action();
        $this->data = $this->get_data();
        
        // Json
        if($this->action === 'json'){
            
            $this->submit();
            
        }
        
        // PHP
        elseif($this->action === 'php'){
    
            // add notice
            if(!empty($this->data)){
                
                acf_add_admin_notice(__('Settings exported.'), 'success');
        
            }
            
        }
        
    }
    
    function submit(){
        
        $this->action = $this->get_action();
        $this->data = $this->get_data();
        
        // Json
        if($this->action === 'json'){
            
            // Date
            $date = date('Y-m-d');
            
            // file
            $file_name = 'acfe-export-settings-' .  $date . '.json';
            
            // headers
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename={$file_name}");
            header("Content-Type: application/json; charset=utf-8");
            
            // return
            echo acf_json_encode($this->data);
        
        }
        
        // PHP
        elseif($this->action === 'php'){
            
            // url
            $url = add_query_arg(array(
                'action' => 'php'
            ), $this->get_url());
            
            // redirect
            wp_redirect($url);
            
        }
    
        exit;
        
    }
    
    function get_data(){
        
        // export
        $data = acfe_get_settings('settings', array());
        
        return $data;
        
    }
    
    function get_action(){
        
        // vars
        $default = 'json';
        $action = acfe_maybe_get_REQUEST('action', $default);
        
        // check allowed
        if(!in_array($action, array('json', 'php')))
            $action = $default;
        
        // return
        return $action;
        
    }
    
}

acf_register_admin_tool('acfe_settings_export');

endif;