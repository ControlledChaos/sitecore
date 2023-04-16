<?php

if(!defined('ABSPATH'))
    exit;

// Check settings
if((!acfe_is_dev() && !acfe_is_super_dev()) || !acf_current_user_can_admin())
    return;

if(!class_exists('acfe_pro_dev')):

class acfe_pro_dev{
    
    /*
     * Construct
     */
    function __construct(){
        
        // remove basic clean metabox
        acf_enable_filter('acfe/dev/clean_metabox');
        
        // wp + acf meta boxes
        add_action('post_submitbox_misc_actions',                       array($this, 'post_submitbox_misc_actions'));
        add_action('acf/options_page/submitbox_before_major_actions',   array($this, 'options_page_submitbox_misc_actions'), 1);
        
        // acfe meta boxes
        add_action('acfe/term/submitbox_before_major_actions',          array($this, 'term_submitbox_misc_actions'));
        add_action('acfe/user/submitbox_before_major_actions',          array($this, 'user_submitbox_misc_actions'));
        add_action('acfe/settings/submitbox_before_major_actions',      array($this, 'settings_submitbox_misc_actions'));
        add_action('acfe/posts/submitbox_before_major_actions',         array($this, 'posts_submitbox_misc_actions'));
        add_action('acfe/terms/submitbox_before_major_actions',         array($this, 'terms_submitbox_misc_actions'));
        add_action('acfe/attachments/submitbox_before_major_actions',   array($this, 'attachments_submitbox_misc_actions'));
        add_action('acfe/users/submitbox_before_major_actions',         array($this, 'users_submitbox_misc_actions'));
        
        // meta boxes
        $this->add_list_meta_boxes();
        
    }
    
    function post_submitbox_misc_actions($post){
        
        // check restricted post types
        if(acfe_is_post_type_reserved_dev($post->post_type)) return;
    
        //vars
        $post_id = $post->ID;
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
        
        // post type label
        $post_type_label = $post->post_type;
    
        // post type object
        if(post_type_exists($post->post_type)){
    
            $post_type_object = get_post_type_object($post->post_type);
            $post_type_label = $post_type_object->labels->singular_name;
            
        }
        
        ?>
        <div class="misc-pub-section misc-pub-acfe-object-id">
            <?php _e('ID', 'acfe'); ?>:
            <strong><?php echo $post_id; ?></strong>
        </div>
        <div class="misc-pub-section misc-pub-acfe-object-type">
            <?php _e('Type', 'acfe'); ?>:
            <strong><?php echo $post_type_label; ?></strong>
        </div>
        <div class="misc-pub-section misc-pub-acfe-object-data">
            <?php _e('Object data', 'acfe'); ?>:
            <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Post Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
        </div>
        <div class="misc-pub-section misc-pub-acfe-object-meta">
            <?php _e('Meta count', 'acfe'); ?>:
            <strong><?php echo $meta_count; ?></strong>
            <?php if($clean): ?>
                <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
            <?php endif; ?>
        </div>
        
        <?php if($single_meta): ?>
        <div class="misc-pub-section misc-pub-acfe-object-single-meta">
            <?php _e('Single meta', 'acfe'); ?>:
            <strong><?php _e('Enabled', 'acfe'); ?></strong>
        </div>
        <?php endif; ?>
        
        <script type="text/javascript">
            (function($) {
                $('.misc-pub-acfe-object-id, .misc-pub-acfe-object-type').prependTo('#misc-publishing-actions');
                $('.misc-pub-acfe-object-data, .misc-pub-acfe-object-meta, .misc-pub-acfe-object-single-meta').insertBefore('.misc-pub-curtime');
            })(jQuery);
        </script>
        <?php
        
        // Add modal in footer
        // Fix issue when sidebar is fixed when post editor is very long
        add_action('admin_footer', function() use($post){
            ?>
            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($post); ?></pre>
                </div>
            </div>
            <?php
        });
        
    }
    
    function term_submitbox_misc_actions($term){
    
        // check restricted taxonomies
        if(acfe_is_taxonomy_reserved_dev($term->taxonomy)) return;
    
        //vars
        $post_id = "term_{$term->term_id}";
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        // taxonomy label
        $taxonomy_label = $term->taxonomy;
        
        // taxonomy object
        if(taxonomy_exists($term->taxonomy)){
    
            $taxonomy_object = get_taxonomy($term->taxonomy);
            $taxonomy_label = $taxonomy_object->labels->singular_name;
            
        }
    
        ?>
        <div id="misc-publishing-actions" style="border-bottom:1px solid #dcdcde;">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $term->term_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-category">
                <?php _e('Taxonomy', 'acfe'); ?>:
                <strong><?php echo $taxonomy_label; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Term Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
    
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($term); ?></pre>
                </div>
            </div>
        </div>
        <?php
    
    }
    
    function user_submitbox_misc_actions($user){
        
        //vars
        $post_id = "user_{$user->ID}";
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
        
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
        
        // user roles
        $user_roles = array_map(function($role){
            
            $role = ucfirst($role);
            return "<strong>{$role}</strong>";
            
        }, $user->roles);
        
        ?>
        <div id="misc-publishing-actions" style="border-bottom:1px solid #dcdcde;">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $user->ID; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-role">
                Role<?php echo count($user_roles) > 1 ? 's' : ''; ?>:
                <strong><?php echo implode(', ', $user_roles); ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('User Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
            
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($user); ?></pre>
                </div>
            </div>
        </div>
        <?php
        
    }
    
    function settings_submitbox_misc_actions($page){
        
        //vars
        $post_id = $page;
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions" style="border-bottom:1px solid #dcdcde;">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
            
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>
        </div>
        <?php
    
    }
    
    function options_page_submitbox_misc_actions($page){
        
        // vars
        $post_id = acf_maybe_get($page, 'post_id', false);
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Options Page Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
    
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($page); ?></pre>
                </div>
            </div>
        </div>
        <?php
    
    }
    
    function posts_submitbox_misc_actions($post_type){
    
        // vars
        $post_id = "{$post_type}_options";
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $object = get_post_type_object($post_type);
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Post Type Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
        
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($object); ?></pre>
                </div>
            </div>
        </div>
        <?php
        
    }
    
    function terms_submitbox_misc_actions($taxonomy){
    
        // vars
        $post_id = "tax_{$taxonomy}_options";
        $acf_post_id = acf_get_valid_post_id($post_id);
    
        $object = get_taxonomy($taxonomy);
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Taxonomy Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
        
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($object); ?></pre>
                </div>
            </div>
        </div>
        <?php
    
    }
    
    function attachments_submitbox_misc_actions(){
    
        // vars
        $post_id = "attachment_options";
        $acf_post_id = acf_get_valid_post_id($post_id);
    
        $object = get_post_type_object('attachment');
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-data">
                <?php _e('Object data', 'acfe'); ?>:
                <a href="#" data-acfe-modal="acfe-wp-object" data-acfe-modal-title="<?php _e('Post Type Object', 'acfe'); ?>" data-acfe-modal-footer="<?php _e('Close', 'acfe'); ?>"><?php _e('View', 'acfe'); ?></a>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
        
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>

            <div class="acfe-modal" data-acfe-modal="acfe-wp-object">
                <div class="acfe-modal-spacer">
                    <pre><?php print_r($object); ?></pre>
                </div>
            </div>
        </div>
        <?php
        
    }
    
    function users_submitbox_misc_actions(){
    
        // vars
        $post_id = "user_options";
        $acf_post_id = acf_get_valid_post_id($post_id);
        
        $meta_count = acfe_dev_count_meta();
        $clean = $meta_count > 0;
    
        // single meta
        $single_meta = acfe_is_single_meta_enabled($acf_post_id);
    
        ?>
        <div id="misc-publishing-actions">
            <div class="misc-pub-section misc-pub-acfe-object-id">
                <?php _e('ID', 'acfe'); ?>:
                <strong><?php echo $acf_post_id; ?></strong>
            </div>
            <div class="misc-pub-section misc-pub-acfe-object-meta">
                <?php _e('Meta count', 'acfe'); ?>:
                <strong><?php echo $meta_count; ?></strong>
                <?php if($clean): ?>
                    <a href="<?php echo add_query_arg(array('acfe_dev_clean' => $post_id, 'acfe_dev_clean_nonce' => wp_create_nonce('acfe_dev_clean'))); ?>"><?php _e('Clean', 'acfe'); ?></a>
                <?php endif; ?>
            </div>
        
            <?php if($single_meta): ?>
                <div class="misc-pub-section misc-pub-acfe-object-single-meta">
                    <?php _e('Single meta', 'acfe'); ?>:
                    <strong><?php _e('Enabled', 'acfe'); ?></strong>
                </div>
            <?php endif; ?>
        </div>
        <?php
        
    }
    
    function add_list_meta_boxes(){
        
        $screens = array(
            array(
                'name'   => 'posts',
                'filter' => 'post_type_list',
            ),
            array(
                'name'   => 'terms',
                'filter' => 'taxonomy_list',
            ),
            array(
                'name'   => 'attachments',
                'filter' => 'attachment_list',
            ),
            array(
                'name'   => 'users',
                'filter' => 'user_list',
            ),
        );
        
        // loop
        foreach($screens as $screen){
            
            // add meta boxes
            add_action("acfe/add_{$screen['name']}_meta_boxes", function($object_name) use($screen){
    
                // check filter
                if(!acf_is_filter_enabled("acfe/{$screen['filter']}")){
                    return;
                }
    
                // check if there are already meta boxes on side
                $submit = acf_is_filter_enabled("acfe/{$screen['filter']}/side");
                $object_label = __('Submit');
                
                if($screen['name'] === 'terms'){
                    
                    $object = get_taxonomy($object_name);
                    $object_label = $object->label;
                    
                }elseif($screen['name'] === 'posts' || $screen['name'] === 'attachments'){
                    
                    $object = get_post_type_object($object_name);
                    $object_label = $object->label;
                    
                }elseif($screen['name'] === 'users'){
    
                    $object_label = __('Users');
                    
                }
    
                // force enable filters
                acf_enable_filter("acfe/{$screen['filter']}/side");
                acf_enable_filter("acfe/{$screen['filter']}/submitdiv");
    
                // Sidebar submit
                add_meta_box('submitdiv', $object_label, array($this, 'render_metabox_submit'), 'edit', 'side', 'high', array('screen' => $screen['name'], 'submit' => $submit));
                
            }, 20);
            
        }
        
    }
    
    function render_metabox_submit($object, $metabox){
    
        // screen
        $screen = $metabox['args']['screen'];
        $submit = $metabox['args']['submit'];
        
        // action
        do_action("acfe/{$screen}/submitbox_before_major_actions", $object);
        
        if($submit):
        ?>
        <div id="major-publishing-actions" style="border-top: 1px solid #dcdcde;">
            <div id="publishing-action">

                <div class="acf-form-submit">
                    <span class="spinner"></span>
                    <input type="submit" class="button button-primary button-large" value="<?php _e('Update', 'acfe'); ?>" />
                </div>
                
            </div>

            <div class="clear"></div>
        </div>
        <?php
        endif;
    }
    
}

new acfe_pro_dev();

endif;