<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_admin_menu')):

class acfe_pro_admin_menu{
    
    /*
     * Construct
     */
    function __construct(){
        
        add_action('admin_menu', array($this, 'admin_menu'), 1000);
        
    }
    
    function admin_menu(){
        
        global $submenu;
        
        if(!acf_maybe_get($submenu, 'edit.php?post_type=acf-field-group')){
            return;
        }
        
        $array = $submenu['edit.php?post_type=acf-field-group'];
        
        foreach($array as $k => $item){
            
            // Forms
            if($item[2] === 'edit.php?post_type=acfe-template'){
                
                acfe_array_move($submenu['edit.php?post_type=acf-field-group'], $k, 7);
                
            }
            
        }
        
    }
    
}

new acfe_pro_admin_menu();

endif;