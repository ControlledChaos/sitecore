<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_google_map')):

class acfe_field_google_map{
    
    function __construct(){
        
        add_action('acf/render_field_settings/type=google_map',     array($this, 'field_group_settings_before'), 5);
        add_action('acf/render_field_settings/type=google_map',     array($this, 'field_group_settings'));
        
        add_filter('acfe/field_wrapper_attributes/type=google_map', array($this, 'field_group_wrapper'), 10, 2);
        add_filter('acf/render_field/type=google_map',              array($this, 'render_field'), 10);
        add_filter('acf/validate_field/type=google_map',            array($this, 'validate_field'));
        add_filter('acf/format_value/type=google_map',              array($this, 'format_value'), 10, 3);
        
        add_filter('acf/prepare_field/name=zoom',                   array($this, 'prepare_zoom'));
        
        $google_map = acf_get_field_type('google_map');
        
        $google_map->default_values = array(
            'height'        => '400',
            'center_lat'    => '46.4519675',
            'center_lng'    => '3.3221324',
            'zoom'            => '2'
        );
        
    }
    
    function format_value($value, $post_id, $field){
        
        // decode JSON string.
        if(is_string($value)){
            
            $value = json_decode(wp_unslash($value), true);
            
        }
        
        $value = acf_get_array($value);
        
        $value = wp_parse_args($value, array(
            'address'           => '',
            'lat'               => 0,
            'lng'               => 0,
            'height'            => 400,
            
            'zoom'              => 2,
            'min_zoom'          => 0,
            'max_zoom'          => 21,
            'marker'            => false,
            'map_type'          => 'roadmap',
            
            'hide_ui'           => false,
            'hide_zoom_control' => false,
            'hide_map_selection'=> false,
            'hide_fullscreen'   => false,
            'hide_streetview'   => false,
            
            'map_style'         => '',
        ));
        
        // Zooms
        $zooms = acf_maybe_get($field, 'acfe_google_map_zooms');
        
        $value['min_zoom'] = acf_maybe_get($zooms, 'min_zoom', 0);
        
        $value['max_zoom'] = acf_maybe_get($zooms, 'max_zoom', 21);
        
        // Marker
        if($marker_icon = acf_maybe_get($field, 'acfe_google_map_marker_icon')){
            
            $marker = array();
            $marker['url'] = wp_get_attachment_url($marker_icon);
            
            // Marker: Height
            if($marker_height = acf_maybe_get($field, 'acfe_google_map_marker_height')){
                
                $marker['height'] = $marker_height;
                
            }
            
            // Marker: Width
            if($marker_width = acf_maybe_get($field, 'acfe_google_map_marker_width')){
                
                $marker['width'] = $marker_width;
                
            }
            
            $value['marker'] = $marker;
            
        }
        
        // View: Map Type
        if($map_type = acf_maybe_get($field, 'acfe_google_map_type')){
            
            $value['map_type'] = $map_type;
            
        }
        
        // View: Disable UI
        $disable_ui = acf_maybe_get($field, 'acfe_google_map_disable_ui');
        
        if($disable_ui){
            
            $value['hide_ui'] = true;
            
        }
        
        // View: Disable Zoom Control
        $zoom_control = acf_maybe_get($field, 'acfe_google_map_disable_zoom_control');
        
        if(acf_maybe_get($field, 'acfe_google_map_disable_zoom_control')){
            
            $value['hide_zoom_control'] = true;
            
        }
        
        // View: Disable Map Selection
        if(acf_maybe_get($field, 'acfe_google_map_disable_map_type')){
            
            $value['hide_map_selection'] = true;
            
        }
        
        // View: Disable Fullscreen
        if(acf_maybe_get($field, 'acfe_google_map_disable_fullscreen')){
            
            $value['hide_fullscreen'] = true;
            
        }
        
        // View: Disable Streeview
        if(acf_maybe_get($field, 'acfe_google_map_disable_streetview')){
            
            $value['hide_streetview'] = true;
            
        }
        
        // View: Map Style
        if($style = acf_maybe_get($field, 'acfe_google_map_style')){
            
            $value['map_style'] = json_encode(json_decode($style));
            
        }
        
        if(!is_numeric($value['zoom']) || $value['zoom'] < $value['min_zoom'] || $value['zoom'] > $value['max_zoom'] || $disable_ui || $zoom_control){
            
            $value['zoom'] = acf_maybe_get($zooms, 'zoom', 2);
            
        }
        
        return $value;
        
    }
    
    function validate_field($field){
        
        $value = json_decode(acf_maybe_get($field, 'acfe_google_map_preview'), true);
        
        if($value){
        
            $field['default_value'] = json_decode(acf_maybe_get($field, 'acfe_google_map_preview'), true);
        
        }
        
        $field['zooms']['zoom'] = 2;
        $field['zooms']['min_zoom'] = 0;
        $field['zooms']['max_zoom'] = 21;
        
        return $field;
        
    }
    
    function prepare_zoom($field){
        
        // Hide default zoom
        if(strpos($field['prefix'], 'zooms') === false)
            return false;
        
        return $field;
        
    }
    
    function field_group_settings_before($field){
        
        // Preview
        acf_render_field_setting($field, array(
            'label'                                 => __('Map Preview'),
            'name'                                  => 'acfe_google_map_preview',
            'instructions'                          => '',
            'type'                                  => 'google_map',
            
            'height'                                => acf_maybe_get($field, 'height'),
            'center_lat'                            => acf_maybe_get($field, 'center_lat'),
            'center_lng'                            => acf_maybe_get($field, 'center_lng'),
            'zoom'                                  => acf_maybe_get($field, 'zoom'),
            
            'acfe_google_map_zooms'                 => acf_maybe_get($field, 'acfe_google_map_zooms'),
            
            'acfe_google_map_marker_icon'           => acf_maybe_get($field, 'acfe_google_map_marker_icon'),
            'acfe_google_map_marker_height'         => acf_maybe_get($field, 'acfe_google_map_marker_height'),
            'acfe_google_map_marker_width'          => acf_maybe_get($field, 'acfe_google_map_marker_width'),
            
            'acfe_google_map_type'                  => acf_maybe_get($field, 'acfe_google_map_type'),
            'acfe_google_map_disable_ui'            => acf_maybe_get($field, 'acfe_google_map_disable_ui'),
            'acfe_google_map_disable_zoom_control'  => acf_maybe_get($field, 'acfe_google_map_disable_zoom_control'),
            'acfe_google_map_disable_map_type'      => acf_maybe_get($field, 'acfe_google_map_disable_map_type'),
            'acfe_google_map_disable_fullscreen'    => acf_maybe_get($field, 'acfe_google_map_disable_fullscreen'),
            'acfe_google_map_disable_streetview'    => acf_maybe_get($field, 'acfe_google_map_disable_streetview'),
            'acfe_google_map_style'                 => acf_maybe_get($field, 'acfe_google_map_style'),
            'acfe_google_map_key'                   => acf_maybe_get($field, 'acfe_google_map_key'),
            'value'                                 => json_decode(acf_maybe_get($field, 'acfe_google_map_preview'), true)
        ));
        
    }
    
    function field_group_settings($field){
        
        $zoom = acf_maybe_get($field, 'zoom');
        
        if(acf_is_empty($zoom))
            $zoom = 2;
        
        // Zoom
        acf_render_field_setting($field, array(
            'label'         => __('Zoom', 'acfe'),
            'instructions'  => '',
            'prepend'       => '',
            'append'        => '',
            'type'          => 'group',
            'name'          => 'acfe_google_map_zooms',
            'sub_fields'    => array(
                array(
                    'label'         => '',
                    'name'          => 'zoom',
                    'key'           => 'zoom',
                    'type'          => 'range',
                    'prepend'       => '',
                    'append'        => '',
                    'default_value' => $zoom,
                    'required'      => false,
                    'min'           => 0,
                    'max'           => 21,
                    'wrapper'       => array(
                        'width' => 33,
                        'class' => '',
                        'id'    => '',
                    ),
                ),
                array(
                    'label'         => '',
                    'name'          => 'min_zoom',
                    'key'           => 'min_zoom',
                    'prepend'       => 'min',
                    'append'        => '',
                    'type'          => 'range',
                    'required'      => false,
                    'min'           => 0,
                    'max'           => 21,
                    'wrapper'       => array(
                        'width' => 33,
                        'class' => '',
                        'id'    => '',
                    ),
                ),
                array(
                    'label'         => '',
                    'name'          => 'max_zoom',
                    'key'           => 'max_zoom',
                    'prepend'       => 'max',
                    'append'        => '',
                    'default_value' => 21,
                    'type'          => 'range',
                    'required'      => false,
                    'min'           => 0,
                    'max'           => 21,
                    'wrapper'       => array(
                        'width' => 33,
                        'class' => '',
                        'id'    => '',
                    ),
                ),
            )
        ));
        
        // Marker: Image
        acf_render_field_setting($field, array(
            'label'         => __('Marker: Image', 'acfe'),
            'instructions'  => '',
            'type'          => 'image',
            'name'          => 'acfe_google_map_marker_icon'
        ));
        
        // Marker: Height
        acf_render_field_setting($field, array(
            'label'         => __('Marker: Size', 'acfe'),
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'acfe_google_map_marker_height',
            'default_value' => 50,
            'prepend'       => 'height',
            'append'        => 'px',
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_marker_icon',
                        'operator'  => '!=empty',
                    ),
                )
            )
        ));
        
        // Marker: Width
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'type'          => 'number',
            'name'          => 'acfe_google_map_marker_width',
            'default_value' => 50,
            'prepend'       => 'width',
            'append'        => 'px',
            '_append'       => 'acfe_google_map_marker_height',
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_marker_icon',
                        'operator'  => '!=empty',
                    ),
                )
            )
        ));
        
        // View: Map Type
        acf_render_field_setting($field, array(
            'label'             => __('View: Map Type'),
            'name'              => 'acfe_google_map_type',
            'instructions'      => '',
            'type'              => 'select',
            'choices'           => array(
                'roadmap'   => 'Map',
                'terrain'   => 'Map + Terrain',
                'satellite' => 'Satellite',
                'hybrid'    => 'Satellite + Labels',
            ),
            'default_value'     => 'roadmap',
        ));
        
        // View: Disable UI
        acf_render_field_setting($field, array(
            'label'         => __('View: Hide UI'),
            'name'          => 'acfe_google_map_disable_ui',
            'instructions'  => '',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true
        ));
        
        // View: Disable Map Type Selection
        acf_render_field_setting($field, array(
            'label'         => __('View: Hide Zoom Control'),
            'name'          => 'acfe_google_map_disable_zoom_control',
            'instructions'  => '',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_disable_ui',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
        // View: Disable Map Type Selection
        acf_render_field_setting($field, array(
            'label'         => __('View: Hide Map Selection'),
            'name'          => 'acfe_google_map_disable_map_type',
            'instructions'  => '',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_disable_ui',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
        // View: Disable Fullscreen
        acf_render_field_setting($field, array(
            'label'         => __('View: Hide Fullscreen'),
            'name'          => 'acfe_google_map_disable_fullscreen',
            'instructions'  => '',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_disable_ui',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
        // View: Disable Streetview
        acf_render_field_setting($field, array(
            'label'         => __('View: Hide Streetview'),
            'name'          => 'acfe_google_map_disable_streetview',
            'instructions'  => '',
            'type'              => 'true_false',
            'message'           => '',
            'default_value'     => false,
            'ui'                => true,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'acfe_google_map_disable_ui',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
        // View: Map Style
        acf_render_field_setting($field, array(
            'label'         => __('View: Map Style'),
            'name'          => 'acfe_google_map_style',
            'instructions'  => 'Find map styles on <a href="https://snazzymaps.com/" target="_blank">Snazzy Maps</a>',
            'type'          => 'acfe_code_editor',
            'mode'          => 'javascript',
            'rows'          => 8,
            'max_rows'      => 8,
        ));
        
        // Google Map API Key
        acf_render_field_setting($field, array(
            'label'         => __('API Key'),
            'name'          => 'acfe_google_map_key',
            'instructions'  => '<a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank">Google Map API Console</a>',
            'type'          => 'text',
        ));
        
    }
    
    function field_group_wrapper($wrapper, $field){
        
        // Zooms
        $zooms = acf_maybe_get($field, 'acfe_google_map_zooms');

        // Zoom
        $zoom = acf_maybe_get($zooms, 'zoom');
        
        if(acf_not_empty($zoom)){
            
            $wrapper['data-acfe-zoom'] = $zoom;
            
        }
        
        // Zoom: Min
        $min_zoom = acf_maybe_get($zooms, 'min_zoom');
        
        if(acf_not_empty($min_zoom)){
            
            $wrapper['data-acfe-min-zoom'] = $min_zoom;
            
        }
        
        // Zoom: Max
        $max_zoom = acf_maybe_get($zooms, 'max_zoom');
        
        if(acf_not_empty($max_zoom)){
            
            $wrapper['data-acfe-max-zoom'] = $max_zoom;
            
        }
        
        // Marker: Image
        if($marker_icon = acf_maybe_get($field, 'acfe_google_map_marker_icon')){
            
            $marker = array();
            $marker['url'] = wp_get_attachment_url($marker_icon);
            
            // Marker: Height
            if($marker_height = acf_maybe_get($field, 'acfe_google_map_marker_height')){
                
                $marker['height'] = $marker_height;
                
            }
            
            // Marker: Width
            if($marker_width = acf_maybe_get($field, 'acfe_google_map_marker_width')){
                
                $marker['width'] = $marker_width;
                
            }
            
            $wrapper['data-acfe-marker'] = $marker;
            
        }
        
        // View: Map Type
        if($map_type = acf_maybe_get($field, 'acfe_google_map_type')){
            
            $wrapper['data-acfe-map-type'] = $map_type;
            
        }
        
        // View: Disable UI
        $disable_ui = acf_maybe_get($field, 'acfe_google_map_disable_ui');
        
        if($disable_ui){
            
            $wrapper['data-acfe-disable-ui'] = 1;
            
        }
        
        // View: Disable Zoom Control
        $zoom_control = acf_maybe_get($field, 'acfe_google_map_disable_zoom_control');
        
        if($zoom_control){
            
            $wrapper['data-acfe-disable-zoom-control'] = 1;
            
        }
        
        // View: Disable Map Selection
        if(acf_maybe_get($field, 'acfe_google_map_disable_map_type')){
            
            $wrapper['data-acfe-disable-map-type'] = 1;
            
        }
        
        // View: Disable Fullscreen
        if(acf_maybe_get($field, 'acfe_google_map_disable_fullscreen')){
            
            $wrapper['data-acfe-disable-fullscreen'] = 1;
            
        }
        
        // View: Disable Streeview
        if(acf_maybe_get($field, 'acfe_google_map_disable_streetview')){
            
            $wrapper['data-acfe-disable-streetview'] = 1;
            
        }
        
        // View: Map Style
        if($style = acf_maybe_get($field, 'acfe_google_map_style')){
            
            $wrapper['data-acfe-style'] = json_encode(json_decode($style));
            
        }
        
        // Parse values
        $value = $field['value'];
        
        // Value: Zoom
        $value_zoom = acf_maybe_get($value, 'zoom');
        
        if(is_numeric($value_zoom) && $value_zoom >= $min_zoom && $value_zoom <= $max_zoom && ($disable_ui || !$zoom_control)){
            
            $wrapper['data-acfe-zoom'] = $value_zoom;
            
        }
        
        return $wrapper;
        
    }
    
    function render_field($field){
        
        if(!acf_maybe_get($field, 'acfe_google_map_key'))
            return;
        
        // bail early if no enqueue
        if(!acf_get_setting('enqueue_google_maps'))
            return;
        
        // vars
        $api = array(
            'key'       => acf_get_setting('google_api_key'),
            'client'    => acf_get_setting('google_api_client'),
            'libraries' => 'places',
            'ver'       => 3,
            'callback'  => '',
            'language'  => acf_get_locale()
        );
        
        // filter
        $api = apply_filters('acf/fields/google_map/api', $api);
        
        $api['key'] = $field['acfe_google_map_key'];
        
        // remove empty
        if(empty($api['key']))      unset($api['key']);
        if(empty($api['client']))   unset($api['client']);
        
        // construct url
        $url = add_query_arg($api, 'https://maps.googleapis.com/maps/api/js');
        
        // localize
        acf_localize_data(array(
            'google_map_api' => $url
        ));
        
    }
    
}

new acfe_field_google_map();

endif;