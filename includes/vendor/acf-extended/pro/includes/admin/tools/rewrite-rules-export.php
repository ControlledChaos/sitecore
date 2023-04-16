<?php 

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_settings_export')):

class acfe_rewrite_rules_export extends ACF_Admin_Tool{
    
    function initialize(){
        
        // vars
        $this->name = 'acfe_rewrite_rules_export';
        $this->title = __('Export Rewrite Rules');
        
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
        <p>Export Rewrite Rules</p>
        
        <?php
    
        $rewrite_rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
        $disabled = !$rewrite_rules ? 'disabled="disabled"' : ''; ?>
        
        <p class="acf-submit">
            
            <button type="submit" name="action" class="button" value="php" <?php echo $disabled; ?>><?php _e('Generate PHP'); ?></button>
            
        </p>
        <?php
        
    }
    
    function html_single(){
        
        ?>
        <div class="acf-postbox-columns">
            <div class="acf-postbox-main">
                
                <p></p>
                
                <?php

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
    
                    // code
                    $code = var_export($this->data, true);

                    // change double spaces to tabs
                    $code = str_replace(array_keys($str_replace), array_values($str_replace), $code);

                    // correctly formats "=> array("
                    $code = preg_replace(array_keys($preg_replace), array_values($preg_replace), $code);

                    // esc_textarea
                    $esc_code = esc_textarea($code);

                    // echo
                    echo $esc_code;
                    
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
        
        // PHP
        if($this->action === 'php'){
    
            // add notice
            if(!empty($this->data)){
                
                acf_add_admin_notice(__('Rewrite rules exported.'), 'success');
        
            }
            
        }
        
    }
    
    function submit(){
        
        $this->action = $this->get_action();
        $this->data = $this->get_data();
        
        // PHP
        if($this->action === 'php'){
            
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
        $rewrite_rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
        
        return $rewrite_rules;
        
    }
    
    function get_action(){
        
        // return
        return 'php';
        
    }
    
}

acf_register_admin_tool('acfe_rewrite_rules_export');

endif;