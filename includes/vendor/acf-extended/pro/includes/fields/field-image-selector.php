<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_field_image_selector')):
    
class acfe_field_image_selector extends acf_field{
    
    /*
     * Construct
     */
    function __construct(){
        
        $this->name = 'acfe_image_selector';
        $this->label = __('Image Selector', 'acfe');
        $this->category = 'choice';
        $this->defaults = array(
            'choices'               => array(),
            'default_value'         => '',
            'image_size'            => 'thumbnail',
            'width'                 => '',
            'height'                => '',
            'border'                => 4,
            'return_format'         => 'value',
            'allow_null'            => 0,
            'multiple'              => 0,
            'layout'                => 'horizontal',
        );
        
        parent::__construct();
        
    }
    
    /*
     * Validate Field
     */
    function validate_field($field){
        
        // validate field
        $field = parent::validate_field($field);
    
        // compatibility: removed 'images' setting, use choices instead
        if(isset($field['images'])){
    
            // vars
            $choices = $field['choices'];
            $images = acf_maybe_get($field, 'images');
            $images = acf_get_array($images);
    
            // merge & combine images
            $choices = array_merge($images, $choices);
            $choices = array_combine($choices, $choices);
    
            // assign choices
            $field['choices'] = $choices;
            
        }
        
        // return
        return $field;
        
    }
    
    /*
     * Render Field Settings
     */
    function render_field_settings($field){
        
        // Choices
        $field['choices'] = acf_encode_choices($field['choices']);
        
        // Default Value
        $field['default_value'] = acf_encode_choices($field['default_value'], false);
        
        // Choices
        acf_render_field_setting($field, array(
            'label'         => __('Choices','acf'),
            'instructions'  => __('Enter each choice on a new line. Image can be an URL or an attachment ID. For example:','acf') . '<br /><br />' . __('choice1 : 895<br/>choice2 : /image.jpg','acf'),
            'type'          => 'textarea',
            'name'          => 'choices',
        ));
        
        // default_value
        acf_render_field_setting($field, array(
            'label'         => __('Default Value','acf'),
            'instructions'  => __('Enter each default value on a new line','acf'),
            'name'          => 'default_value',
            'type'          => 'textarea',
        ));
        
        $image_sizes = acfe_get_registered_image_sizes();
        unset($image_sizes['full']);
        
        $image_sizes = wp_list_pluck($image_sizes, 'name');
        
        // image_size
        acf_render_field_setting($field, array(
            'label'         => __('Images Size','acf'),
            'instructions'  => '',
            'type'          => 'acfe_image_sizes',
            'field_type'    => 'select',
            'image_sizes'   => $image_sizes,
            'display_format'=> 'name_size',
            'name'          => 'image_size'
        ));
        
        // width
        acf_render_field_setting($field, array(
            'label'         => __('Container','acf'),
            'instructions'  => '',
            'type'          => 'text',
            'name'          => 'width',
            'prepend'       => 'width',
        ));
        
        // height
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'type'          => 'text',
            'name'          => 'height',
            'prepend'       => 'height',
            '_append'       => 'width',
        ));
        
        // height
        acf_render_field_setting($field, array(
            'label'         => '',
            'instructions'  => '',
            'type'          => 'number',
            'min'           => 0,
            'name'          => 'border',
            'prepend'       => 'border',
            'append'        => 'px',
            '_append'       => 'width',
        ));
        
        // return_format
        acf_render_field_setting($field, array(
            'label'         => __('Return Value', 'acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'return_format',
            'layout'        => 'horizontal',
            'choices'       => array(
                'value' => __('Value', 'acfe'),
                'array' => __('Array', 'acfe'),
                'image' => __('Image', 'acfe'),
            ),
        ));
        
        // Allow null
        acf_render_field_setting($field, array(
            'label'         => __('Allow Null?','acf'),
            'instructions'  => '',
            'name'          => 'allow_null',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
        
        // Multiple
        acf_render_field_setting($field, array(
            'label'         => __('Select multiple values?','acf'),
            'instructions'  => '',
            'name'          => 'multiple',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
        
        // Layout
        acf_render_field_setting($field, array(
            'label'         => __('Layout','acf'),
            'instructions'  => '',
            'type'          => 'radio',
            'name'          => 'layout',
            'layout'        => 'horizontal',
            'choices'       => array(
                'horizontal'    => __("Horizontal",'acf'),
                'vertical'      => __("Vertical",'acf'),
            )
        ));
        
    }
    
    /*
     * Update Field
     */
    function update_field($field){
        
        // Choices
        $field['choices'] = acf_decode_choices($field['choices']);
        
        // Default value
        $field['default_value'] = acf_decode_choices($field['default_value'], true);
        
        if(!$field['multiple']){
            $field['default_value'] = acfe_unarray($field['default_value']);
        }
        
        return $field;
        
    }
    
    /*
     * Render Field
     */
    function render_field($field){
        
        // Set Field Type
        $field['type'] = $field['multiple'] ? 'checkbox' : 'radio';
        $field['toggle'] = false;
        $field['allow_custom'] = false;
        $field['other_choice'] = false;
        
        if(!$field['multiple']){
            $field['value'] = acfe_unarray($field['value']);
        }
        
        // Vars
        $choices = array();
        $data = acfe_get_registered_image_sizes($field['image_size']);
        
        // Data
        $width = $data['width'];
        $height = $data['height'] ? $data['height'] : $width;
        
        $width = $field['width'] ? $field['width'] : $width;
        $height = $field['height'] ? $field['height'] : $height;
        
        // Border
        $border = $field['border'] ? $field['border'] : 0;
        
        if($field['choices']){
            
            foreach($field['choices'] as $value => $image){
                
                // url
                $url = $image;
                
                // attachment
                if(is_numeric($image)){
                    
                    // get src
                    $src = wp_get_attachment_image_src($image, $field['image_size']);
                    
                    // invalid image
                    if(!$src) continue;
                    
                    $url = $src[0];
                    
                }
                
                // Extension
                $path = pathinfo($url);
                $ext = acf_maybe_get($path, 'extension');
                
                // Style
                $style = "background-image:url({$url}); width:{$width}px; height:{$height}px; border-width:{$border}px";
                
                // Atts
                $atts = array(
                    'class' => "image {$ext}",
                    'style' => $style
                );
                
                // Choice
                $choices[$value] = '<div ' . acf_esc_attrs($atts) . '></div>';
                
            }
            
        }
        
        $field['choices'] = $choices;
        
        $atts = array(
            'class'             => "acfe-image-selector {$field['class']}",
            'data-allow_null'   => $field['allow_null'],
            'data-multiple'     => $field['multiple'],
        );
        
        ?>
        <div <?php echo acf_esc_attrs($atts); ?>>
            <?php acf_render_field($field); ?>
        </div>
        <?php
        
    }
    
    /*
     * Format Value
     */
    function format_value($value, $post_id, $field){
        
        // bail early
        if(empty($value)) return $value;
        
        // vars
        $is_array = is_array($value);
        $value = acf_get_array($value);
        
        // loop
        foreach($value as &$v){
            
            // retrieve image
            $image = acf_maybe_get($field['choices'], $v, $v);
    
            // value
            if($field['return_format'] == 'value'){
        
                // do nothing
        
            // label
            }elseif($field['return_format'] == 'image'){
    
                $v = $image;
        
            // array
            }elseif($field['return_format'] == 'array'){
    
                $v = array(
                    'value' => $v,
                    'image' => $image
                );
        
            }
            
        }
        
        // Do not return array
        if(!$is_array){
            $value = acfe_unarray($value);
        }
        
        // Return
        return $value;
        
    }
    
}

// initialize
acf_register_field_type('acfe_field_image_selector');

endif;