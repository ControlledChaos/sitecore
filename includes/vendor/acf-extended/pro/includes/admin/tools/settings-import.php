<?php 

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_settings_import')):

class acfe_settings_import extends ACF_Admin_Tool{
    
    function initialize(){
        
        // vars
        $this->name = 'acfe_settings_import';
        $this->title = __('Import Settings');
        
    }
    
    function html(){
        
        ?>
        <p>Import ACF Settings</p>
        
        <div class="acf-fields">
            <?php 
            
            acf_render_field_wrap(array(
                'label'     => __('Select File', 'acf'),
                'type'      => 'file',
                'name'      => 'acf_import_file',
                'value'     => false,
                'uploader'  => 'basic',
            ));
            
            ?>
        </div>
        
        <p class="acf-submit">
            <button type="submit" name="action" class="button button-primary"><?php _e('Import File'); ?></button>
        </p>
        <?php
        
    }
    
    function submit(){
    
        // Validate
        $json = $this->validate_file();
        
        if(!$json)
            return;
        
        // Loop over json
        foreach($json as $name => $value){
        
            // Import
            acfe_update_settings("settings.{$name}", $value);
            
        }
        
        // Add notice
        acf_add_admin_notice(__('Settings imported.'), 'success');
        
    }
    
    function validate_file(){
        
        // Check file size.
        if(empty($_FILES['acf_import_file']['size'])){
            
            acf_add_admin_notice(__("No file selected", 'acf'), 'warning');
            return false;
            
        }
        
        // Get file data.
        $file = $_FILES['acf_import_file'];
        
        // Check errors.
        if($file['error']){
            
            acf_add_admin_notice(__("Error uploading file. Please try again", 'acf'), 'warning');
            return false;
            
        }
        
        // Check file type.
        if(pathinfo($file['name'], PATHINFO_EXTENSION) !== 'json'){
            
            acf_add_admin_notice(__("Incorrect file type", 'acf'), 'warning');
            return false;
            
        }
        
        // Read JSON.
        $json = file_get_contents($file['tmp_name']);
        $json = json_decode($json, true);
        
        // Check if empty.
        if(!$json || !is_array($json)){
            
            acf_add_admin_notice(__("Import file empty", 'acf'), 'warning');
            return false;
            
        }
        
        return $json;
        
    }
    
}

acf_register_admin_tool('acfe_settings_import');

endif;