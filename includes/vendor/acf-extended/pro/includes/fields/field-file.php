<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('acfe_pro_field_file')):

class acfe_pro_field_file{
    
    public $files = array();
    
    function __construct(){
        
        $file = acf_get_field_type('file');
        $media = acf_get_instance('ACF_Media');
        
        // Render Field
        remove_action('acf/render_field/type=file',             array($file, 'render_field'), 9);
        add_action('acf/render_field/type=file',                array($this, 'render_field'), 9);
        
        // Upload Files
        remove_action('acf/save_post',                          array($media, 'save_files'), 5);
        add_action('acf/save_post',                             array($this, 'save_files'), 5);
        
        // Update Value
        remove_filter('acf/update_value/type=file',             array($file, 'update_value'));
        add_filter('acf/update_value/type=file',                array($this, 'update_value'), 10, 3);
        
        // Validate Value
        remove_filter('acf/validate_value/type=file',           array($file, 'validate_value'));
        add_filter('acf/validate_value/type=file',              array($this, 'validate_value'), 10, 4);
        
        // Format Value
        remove_filter('acf/format_value/type=file',             array($file, 'format_value'));
        add_filter('acf/format_value/type=file',                array($this, 'format_value'), 10, 3);
        
        // Upload Prefilter
        add_filter('acfe/upload_dir/type=file',                 array($this, 'upload_dir'), 10, 2);
        
        // Settings
        add_action('acf/render_field_settings/type=file',       array($this, 'field_settings'));
        
    }
    
    function save_files($post_id = 0){
        
        // bail early if no $_FILES data
        if(empty($_FILES['acf']['name'])){
            return;
        }
        
        // upload files
        $this->upload_files();
        
    }
    
    function upload_files($ancestors = array()){
        
        // vars
        $file = array(
            'name'      => '',
            'type'      => '',
            'tmp_name'  => '',
            'error'     => '',
            'size'      => ''
        );
        
        // populate with $_FILES data
        foreach(array_keys($file) as $k){
            
            $file[$k] = $_FILES['acf'][$k];
            
        }
        
        // walk through ancestors
        if(!empty($ancestors)){
            
            foreach($ancestors as $a){
                
                foreach(array_keys($file) as $k){
                    
                    $file[ $k ] = $file[ $k ][ $a ];
                    
                }
                
            }
            
        }
        
        // is array?
        if(is_array($file['name'])){
            
            foreach(array_keys($file['name']) as $k){
                
                $_ancestors = array_merge($ancestors, array($k));
                
                $this->upload_files($_ancestors);
                
            }
            
            return;
            
        }
        
        // bail ealry if file has error (no file uploaded)
        if($file['error']){
            
            return;
            
        }
        
        // Remove numeric ancestor: acf[field_123abc][0]
        foreach($ancestors as $_k => $ancestor){
            
            if(!is_numeric($ancestor))
                continue;
            
            unset($ancestors[$_k]);
            
        }
        
        //acf_log('-------------- Advanced Uploader: Upload ---------------');
        //acf_log('File name:', $file['name']);
        
        // assign global _acfuploader for media validation
        $_POST['_acfuploader'] = end($ancestors);
        
        // file found!
        $attachment_id = acf_upload_file($file);
        
        // Save file globally (to be reused later)
        $this->files[] = $file;
        
        // update $_POST
        array_unshift($ancestors, 'acf');
        
        $this->update_nested_array($_POST, $ancestors, $attachment_id);
        
    }
    
    function update_nested_array(&$array, $ancestors, $value){
        
        // if no more ancestors, update the current var
        if(empty($ancestors)){
            
            //acf_log('-------- Advanced Uploader: Second Pass (empty) --------');
            //acf_log('$array before:', $array);
            
            // Search: array([0] => url=C:/fakepath/image.gif&name=image.gif&size=465547&type=image%2Fgif)
            if(is_array($array)){
                
                foreach($array as &$row){
                    
                    if(!is_string($row) || stripos($row, 'url=') !== 0)
                        continue;
                    
                    $file_parsed = null;
                    parse_str(wp_specialchars_decode($row), $file_parsed);
                    
                    // Get global uploaded files to make sure the order is respected (check name & size)
                    foreach($this->files as $file_uploaded){
    
                        $file_parsed['name'] = wp_unslash($file_parsed['name']);
                        
                        if($file_uploaded['name'] !== $file_parsed['name'] || absint($file_uploaded['size']) !== absint($file_parsed['size']))
                            continue;
                        
                        // Found. Replace with Attachment ID
                        $row = $value;
                        
                        break 2;
                        
                    }
                    
                }
                
            }else{
                
                // Search: 146 (Attachment ID already set before)
                if(is_numeric($array)){
                    
                    // Convert to array for next upload (if any)
                    $array = acf_get_array($array);
                    $array[] = $value;
                    
                    // Other: Native behavior
                }else{
                    
                    $array = $value;
                    
                }
                
            }
            
            //acf_log('$array after:', $array);
            
            // return
            return true;
            
        }
        
        // shift the next ancestor from the array
        $k = array_shift($ancestors);
        
        // if exists
        if(isset($array[$k])){
            
            //acf_log('-------- Advanced Uploader: First Pass (!empty) --------');
            //acf_log('$array['.$k.']', $array[$k]);
            
            return $this->update_nested_array($array[$k], $ancestors, $value);
            
        }
        
        // return
        return false;
        
    }
    
    /*
     * File Upload Dir
     */
    function upload_dir($uploads, $field){
        
        // vars
        $upload_folder = acf_maybe_get($field, 'upload_folder');
        
        // check setting
        if(!$upload_folder){
            return $uploads;
        }
    
        // vars
        $folder = trim($upload_folder);
        $folder = ltrim($folder, '/\\');
        $folder = rtrim($folder, '/\\');
    
        // template tags
        if(stripos($folder, '{year}') !== false || stripos($folder, '{month}') !== false){
        
            $time = current_time('mysql');
            $year = substr($time, 0, 4);
            $month = substr($time, 5, 2);
        
            $folder = str_replace('{year}', $year, $folder);
            $folder = str_replace('{month}', $month, $folder);
        
        }
    
        // change path
        $uploads['path'] = "{$uploads['basedir']}/{$folder}";
        $uploads['url'] = "{$uploads['baseurl']}/{$folder}";
        $uploads['subdir'] = '';
        
        // return
        return $uploads;
        
    }
    
    function field_settings($field){
        
        // Preview Style
        acf_render_field_setting($field, array(
            'label'         => __('Preview Style','acf'),
            'instructions'  => '',
            'name'          => 'preview_style',
            'type'          => 'select',
            'choices'       => array(
                'default'   => 'Default',
                'inline'    => 'Inline',
                'select2'   => 'Select',
            ),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'basic',
                    ),
                    array(
                        'field'     => 'stylised_button',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'basic',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'wp',
                    ),
                )
            ),
            'wrapper' => array(
                'data-after' => 'return_format'
            )
        ));
        
        // Placeholder
        acf_render_field_setting($field, array(
            'label'             => __('Placeholder','acf'),
            'instructions'      => '',
            'name'              => 'placeholder',
            'type'              => 'text',
            'default_value'     => __('Select', 'acf'),
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'preview_style',
                        'operator'  => '==',
                        'value'     => 'inline',
                    ),
                ),
                array(
                    array(
                        'field'     => 'preview_style',
                        'operator'  => '==',
                        'value'     => 'select2',
                    ),
                ),
            ),
            'wrapper' => array(
                'data-after' => 'preview_style'
            )
        ));
        
        $upload_dir = wp_upload_dir();
        $upload_dir_url = $upload_dir['baseurl'];
        $upload_dir_url = trailingslashit(str_replace(home_url(), '', $upload_dir_url));
        
        // Upload folder
        acf_render_field_setting($field, array(
            'label'         => __('Upload Folder','acf'),
            'instructions'  => 'Leave blank to use the native upload folder. Available template tags: <code>{year}</code> <code>{month}</code>',
            'name'          => 'upload_folder',
            'type'          => 'text',
            'prepend'       => $upload_dir_url,
        ));
        
        // Button Label
        acf_render_field_setting($field, array(
            'label'             => __('Button Label','acf'),
            'instructions'      => '',
            'name'              => 'button_label',
            'default_value'     => __('Add File','acf'),
            'type'              => 'text',
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'stylised_button',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'wp',
                    ),
                ),
            )
        ));
        
        // Stylised button
        acf_render_field_setting($field, array(
            'label'             => __('Stylised Button','acf'),
            'instructions'      => '',
            'name'              => 'stylised_button',
            'type'              => 'true_false',
            'ui'                => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'basic',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '!=',
                        'value'     => '1',
                    ),
                )
            )
        ));
        
        // File Count
        acf_render_field_setting($field, array(
            'label'             => __('File Count','acf'),
            'instructions'      => '',
            'name'              => 'file_count',
            'type'              => 'true_false',
            'ui'                => 1,
            'conditional_logic' => array(
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'basic',
                    ),
                    array(
                        'field'     => 'stylised_button',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'basic',
                    ),
                    array(
                        'field'     => 'multiple',
                        'operator'  => '==',
                        'value'     => '1',
                    ),
                ),
                array(
                    array(
                        'field'     => 'uploader',
                        'operator'  => '==',
                        'value'     => 'wp',
                    ),
                ),
            )
        ));
        
        // Multiple upload
        acf_render_field_setting($field, array(
            'label'         => __('Allow multiple files','acf'),
            'instructions'  => '',
            'name'          => 'multiple',
            'type'          => 'true_false',
            'ui'            => 1,
        ));
        
    }
    
    function render_field($field){
        
        // uploader setting
        $uploader = acf_get_setting('uploader');
        
        // uploader field
        $uploader = acf_maybe_get($field, 'uploader', $uploader);
        
        // preview style
        $preview_style = acf_maybe_get($field, 'preview_style', 'default');
        
        // placeholder
        $placeholder = acf_maybe_get($field, 'placeholder');
        
        // stylised button
        $stylised_button = acf_maybe_get($field, 'stylised_button');
        
        // file count
        $file_count = acf_maybe_get($field, 'file_count');
        
        // multiple
        $multiple = acf_maybe_get($field, 'multiple');
        
        // min/max
        $min = acf_maybe_get($field, 'min');
        $max = acf_maybe_get($field, 'max');
        
        if($multiple || $uploader === 'wp'){
            $stylised_button = true;
        }
        
        // enqueue
        if($uploader === 'wp'){
            acf_enqueue_uploader();
        }
        
        $div = array(
            'class'             => 'acf-file-uploader',
            'data-library'      => $field['library'],
            'data-mime_types'   => $field['mime_types'],
            'data-uploader'     => $uploader,
        );
        
        $field_name = $field['name'];
        
        if($multiple){
            
            $div['data-multiple'] = $multiple;
            
            $field_name .= '[]';
            
        }
        
        if($min){
            
            $div['data-min'] = $min;
            
        }
        
        if($max){
            
            $div['data-max'] = $max;
            
        }
        
        if(!$stylised_button){
            
            $div['data-basic'] = true;
            
        }
        
        if($preview_style){
            $div['class'] .= " -{$preview_style}";
        }
        
        if($placeholder){
            $div['class'] .= ' has-placeholder';
        }
        
        $rows = array(
            'acfcloneindex' => array(
                'value'     => '',
                'icon'      => esc_url(wp_mime_type_icon()),
                'title'     => '',
                'url'       => '',
                'filename'  => '',
                'filesize'  => ''
            )
        );
        
        $has_value = false;
        
        // has value?
        if(!empty($field['value'])){
            
            $values = acf_get_array($field['value']);
            $i = 0;
            foreach($values as $value){
                
                $attachment = acf_get_attachment($value);
                
                if($attachment){
                    
                    $i++;
                    $has_value = true;
                    
                    // Only one value if not multiple
                    if(!$multiple && $i > 1)
                        break;
                    
                    // update
                    $rows[] = array(
                        'value'     => $value,
                        'icon'      => $attachment['icon'],
                        'title'     => $attachment['title'],
                        'url'       => $attachment['url'],
                        'filename'  => $attachment['filename'],
                        'filesize'  => $attachment['filesize'] ? size_format($attachment['filesize']) : '',
                    );
                    
                }
                
            }
            
        }
        
        // has value
        if($has_value){
            
            $div['class'] .= ' has-value';
            
        }
        
        ?>
        <div <?php acf_esc_attr_e($div); ?>>
            
            <?php
            acf_hidden_input(array(
                'name' => $field['name'],
                'value' => ''
            ));
            ?>

            <div class="values show-if-value">
                
                <?php if($placeholder){ ?>
                    <span class="-placeholder"><?php echo $placeholder; ?></span>
                <?php } ?>
                
                <?php foreach($rows as $i => $row){ ?>
                    
                    <?php
                    $wrap = array(
                        'class' => 'file-wrap'
                    );
                    
                    if($i === 'acfcloneindex'){
                        
                        $wrap['class'] .= ' acf-clone';
                        $wrap['data-id'] = 'acfcloneindex';
                        
                    }
                    
                    ?>

                    <div <?php acf_esc_attr_e($wrap); ?>>
                        
                        <?php
                        acf_hidden_input(array(
                            'name'      => $field_name,
                            'value'     => $row['value']
                        ));
                        ?>

                        <div class="file-icon">
                            <img data-name="icon" src="<?php echo esc_url($row['icon']); ?>" alt=""/>
                        </div>
                        <div class="file-info">
                            <p>
                                <strong data-name="title"><?php echo esc_html($row['title']); ?></strong>
                            </p>
                            <p>
                                <strong><?php _e('File name', 'acf'); ?>:</strong>
                                <a data-name="filename" href="<?php echo esc_url($row['url']); ?>" target="_blank"><?php echo esc_html($row['filename']); ?></a>
                            </p>
                            <p>
                                <strong><?php _e('File size', 'acf'); ?>:</strong>
                                <span data-name="filesize"><?php echo esc_html($row['filesize']); ?></span>
                            </p>
                        </div>

                        <div class="acf-actions -hover">
                            <?php if($uploader === 'wp' && $i !== 'acfcloneindex'){ ?>
                                <a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php _e('Edit', 'acf'); ?>"></a>
                            <?php } ?>
                            <a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php _e('Remove', 'acf'); ?>"></a>
                        </div>

                    </div>
                
                
                <?php } ?>

            </div>
            
            <?php
            
            $wrapper = array(
                'class'     => 'acf-uploader-wrapper'
            );
            
            if(!$multiple){
                $wrapper['class'] .= ' hide-if-value';
            }
            
            $button_label = acf_maybe_get($field, 'button_label');
            if(empty($button_label))
                $button_label = __('Add File','acf');
            ?>

            <div <?php acf_esc_attr_e($wrapper); ?>>
                
                <?php if($uploader == 'basic'): ?>

                    <div class="acf-uploader" data-id="<?php echo uniqid(); ?>">
                        
                        <?php if($stylised_button){ ?>

                            <a data-name="basic-add" class="acf-button button" href="#">
                                <?php echo $button_label; ?>
                                <?php if($file_count){ ?>
                                    <span class="count" data-count="0"></span>
                                <?php }?>
                            </a>
                        
                        <?php } ?>
                        
                        <?php
                        
                        $args = array(
                            'name'      => $field_name,
                            'id'        => $field['id'],
                            'accept'    => $field['mime_types']
                        );
                        
                        if($multiple){
                            $args['multiple'] = '';
                        }
                        
                        acf_file_input($args);
                        
                        ?>

                    </div>
                
                <?php else: ?>

                    <div class="acf-uploader">

                        <a data-name="add" class="acf-button button" href="#">
                            <?php echo $button_label; ?>
                            <?php if($file_count){ ?>
                                <span class="count" data-count="0"></span>
                            <?php }?>
                        </a>

                    </div>
                
                <?php endif; ?>

            </div>
        </div>
        <?php
        
    }
    
    function update_value($value, $post_id, $field){
        
        // Bail early if no value.
        if(empty($value)){
            return $value;
        }
    
        // Bail early if local meta
        if(acfe_is_local_post_id($post_id)){
            return $value;
        }
        
        $values = acf_get_array($value);
        $return = array();
        
        foreach($values as $attachment_id){
            
            // Parse value for id.
            $attachment_id = acf_idval($attachment_id);
            
            // Connect attacment to post.
            acf_connect_attachment_to_post($attachment_id, $post_id);
            
            if(!empty($attachment_id)){
                $return[] = $attachment_id;
            }
            
        }
        
        // Empty
        if(empty($return)){
            return false;
        }
        
        // First value
        if(count($return) === 1){
            return array_shift($return);
        }
        
        // Array
        return $return;
        
    }
    
    function validate_value($valid, $value, $field, $input){
        
        $values = acf_get_array($value);
        $errors = array();
        
        // Required
        if($field['required']){
            
            $empty = true;
            
            foreach($values as $value){
                
                if(empty($value)) continue;
                
                $empty = false;
                break;
                
            }
            
            if($empty){
                $valid = false;
            }
            
        }
        
        // Check files errors
        foreach($values as $value){
            
            // bail early if empty
            if(empty($value)) continue;
            
            // bail ealry if is numeric
            if(is_numeric($value)) continue;
            
            // bail ealry if not basic string
            if(!is_string($value)) continue;
            
            // decode value
            $file = null;
            parse_str($value, $file);
            
            // bail early if no attachment
            if(empty($file)) continue;
            
            // Get file errors
            $file_errors = acf_validate_attachment($file, $field, 'basic_upload');
            
            if(!empty($file_errors)){
                $errors[] = implode("\n", $file_errors);
            }
            
        }
        
        // Get all errors
        if(!empty($errors)){
            $valid = implode("\n", $errors);
        }
        
        // return
        return $valid;
        
    }
    
    function format_value($value, $post_id, $field){
        
        $values = acf_get_array($value);
        $return = array();
        
        foreach($values as $_value){
            
            if(!is_numeric($_value)) continue;
            
            $_value = intval($_value);
            
            // format
            if($field['return_format'] == 'url'){
                
                $return[] = wp_get_attachment_url($_value);
                
            }elseif($field['return_format'] == 'array'){
                
                $return[] = acf_get_attachment($_value);
                
            }elseif($field['return_format'] == 'id'){
                
                $return[] = $_value;
                
            }
            
        }
        
        if(!acf_maybe_get($field, 'multiple')){
    
            $return = acfe_unarray($return);
            
            if(empty($return)){
                $return = false;
            }
            
        }
        
        return $return;
        
    }
    
}

new acfe_pro_field_file();

endif;