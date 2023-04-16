<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_group_hide_on_screen')):

class acfe_pro_field_group_hide_on_screen{
 
    function __construct(){
        
        add_action('acf/field_group/admin_head',    array($this, 'admin_head'));
        add_filter('acf/get_field_group_style',     array($this, 'get_field_group_style'), 10, 2);
        
    }
    
    function admin_head(){
        
        add_filter('acf/prepare_field/name=hide_on_screen', array($this, 'prepare_hide_on_screen'), 20);
        
    }
    
    /*
     * Hide on screen: Settings
     */
    function prepare_hide_on_screen($field){
    
        $field['choices']['title']              = 'Title';
        $field['choices']['save_draft']         = 'Save Draft';
        $field['choices']['preview']            = 'Preview';
        $field['choices']['post_status']        = 'Post Status';
        $field['choices']['visibility']         = 'Post Visibility';
        $field['choices']['publish_date']       = 'Publish Date';
        $field['choices']['trash']              = 'Move to trash';
        $field['choices']['publish']            = 'Publish/Update';
        $field['choices']['minor_publish']      = 'Minor Publishing Actions';
        $field['choices']['misc_publish']       = 'Misc Publishing Actions';
        $field['choices']['major_publish']      = 'Major Publishing Actions';
        $field['choices']['publish_metabox']    = 'Publish Metabox';
        
        // Sort ASC
        asort($field['choices']);
    
        return $field;
        
    }
    
    /*
     * Hide on screen: Styles
     */
    function get_field_group_style($style, $field_group){
    
        $elements = array(
            'title'             => '#titlediv > #titlewrap',
            'save_draft'        => '#minor-publishing-actions > #save-action',
            'preview'           => '#minor-publishing-actions > #preview-action',
            'post_status'       => '#misc-publishing-actions > .misc-pub-post-status',
            'visibility'        => '#misc-publishing-actions > .misc-pub-visibility',
            'publish_date'      => '#misc-publishing-actions > .misc-pub-curtime',
            'trash'             => '#major-publishing-actions > #delete-action',
            'publish'           => '#major-publishing-actions > #publishing-action',
            'minor_publish'     => '#minor-publishing-actions',
            'misc_publish'      => '#misc-publishing-actions',
            'major_publish'     => '#major-publishing-actions',
            'publish_metabox'   => '#submitdiv',
        );
    
        if(!is_array($field_group['hide_on_screen']))
            return $style;
    
        $hide = array();
    
        foreach($field_group['hide_on_screen'] as $k){
        
            if(!isset($elements[$k]))
                continue;
        
            $id = $elements[$k];
            $hide[] = $id;
        
        }
    
        if(empty($hide))
            return $style;
    
        $style .= implode(', ', $hide) . ' {display: none;}';
    
        return $style;
        
    }
    
}

// initialize
new acfe_pro_field_group_hide_on_screen();

endif;