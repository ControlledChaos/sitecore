<?php

if(!defined('ABSPATH'))
    exit;

if(!class_exists('ACFE_Updates')):

class ACFE_Updates {
    
    public $license = '';
    public $url     = 'https://www.acf-extended.com';
    public $item    = 'ACF Extended Pro';
    public $updater = false;
    
    /*
     * Construct
     */
    public function __construct(){
        
        // get license
        $this->license = acfe_get_settings('license');
        
        // actions
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'), 5);
        
    }
    
    /*
     * Admin Menu
     */
    function admin_menu(){
    
        // get page
        $page = get_plugin_page_hookname('acf-settings-updates', 'edit.php?post_type=acf-field-group');
        
        // actions
        add_action("load-{$page}",  array($this, 'load'), 20);
        add_action($page,           array($this, 'html'), 20);
        
    }
    
    /*
     * Admin Init
     */
    function admin_init(){
    
        // initialize updater
        $this->updater = new ACFE_Updater($this->url, ACFE_FILE, array(
            'version'   => ACFE_VERSION,
            'license'   => $this->license,
            'item_name' => $this->item,
            'author'    => 'ACF Extended'
        ));
        
    }
    
    /*
     * Load
     */
    function load(){
    
        // activate
        if(acf_verify_nonce('acfe-pro-activate')){
        
            $this->activate();
        
        // deactivate
        }elseif(acf_verify_nonce('acfe-pro-deactivate')){
        
            $this->deactivate();
        
        // check
        }elseif(acf_maybe_get_GET('acfe-pro-check')){
        
            $this->check();
        
        }
    
        // check version
        $this->updater->check_update(true);
        /*
        $version_info = $this->updater->get_cached_version_info();
    
        // check version message
        if(isset($version_info->msg)){
            acf_add_admin_notice($version_info->msg, 'warning');
        }
        */
        
        // action message
        if($message = acf_maybe_get_GET('message')){
            
            $type = $message === 'success' || $message === 'disabled' ? 'success' : 'warning';
            acf_add_admin_notice($this->get_error_message($message), $type);
            
        }
        
        // body class
        add_filter('admin_body_class', array($this, 'admin_body_class'));
        
    }
    
    /*
     * Admin Body Class
     */
    function admin_body_class($classes){
        
        $classes .= ' acf-settings-updates';
        return $classes;
        
    }
    
    /*
     * Activate
     */
    function activate(){
        
        // license
        $license = trim($_POST['acfe_pro_licence']);
        
        // empty license
        if(empty($license)){
    
            wp_redirect(add_query_arg(array('message' => 'missing')));
            exit;
            
        }
        
        // api call
        $response = wp_remote_post($this->url, array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => array(
                'edd_action' => 'activate_license',
                'license'    => $license,
                'item_name'  => urlencode($this->item),
                'url'        => home_url()
            )
        ));
        
        // invalid response
        if(is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200){
            
            wp_redirect(add_query_arg(array('message' => 'error')));
            exit;
            
        }
        
        // license data
        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        // invalid license
        if($license_data->success === false){
            
            wp_redirect(add_query_arg(array('message' => $license_data->error)));
            exit;
        
        }
        
        // valid license
        $this->update_license($license);
        
        // redirect
        wp_redirect(add_query_arg(array('message' => 'success')));
        exit;
        
    }
    
    /*
     * Deactivate
     */
    function deactivate(){
    
        // api call
        $response = wp_remote_post($this->url, array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => array(
                'edd_action' => 'deactivate_license',
                'license'    => $this->license,
                'item_name'  => urlencode($this->item),
                'url'        => home_url()
            )
        ));
    
        // invalid response
        if(is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200){
    
            wp_redirect(add_query_arg(array('message' => 'error')));
            exit;
            
        }
        
        // valid response
        $this->update_license();
        
        // redirect
        wp_redirect(add_query_arg(array('message' => 'disabled')));
        exit;
        
    }
    
    /*
     * Check
     */
    function check(){
    
        $this->refresh();
    
        wp_redirect(remove_query_arg(array('acfe-pro-check', 'message')));
        exit;
        
    }
    
    /*
     * Update License
     */
    function update_license($key = ''){
    
        acfe_update_settings('license', $key);
        $this->refresh();
        
    }
    
    /*
     * Refresh
     */
    function refresh(){
        
        delete_site_transient('update_plugins');
        delete_option('acfe_plugin_updates');
        
    }
    
    /*
     * HTML
     */
    function html(){
        
        // License
        $license = $this->license;
        $active = $license ? true : false;
        $nonce = $active ? 'acfe-pro-deactivate' : 'acfe-pro-activate';
        $button = $active ? __('Deactivate License', 'acf') : __('Activate License', 'acf');
        $readonly = $active ? 1 : 0;
        
        // Update
        $updater = $this->updater->check_update(true);
        $name = plugin_basename(ACFE_FILE);
        $current_version = ACFE_VERSION;
        
        $no_update = (bool) isset($updater->no_update[$name]);
        
        if($no_update){
            
            $info = $updater->no_update[$name];
            $remote_version = isset($info->new_version) ? $info->new_version : false;
            
        }else{
    
            $info = $updater->response[$name];
            $remote_version = isset($info->new_version) ? $info->new_version : false;
            
        }
        
        // update vailable
        $update_available = (bool) acf_version_compare($remote_version, '>', $current_version);
        
        if(!$remote_version){
            $remote_version = 'None';
            $update_available = false;
        }
        
        // changelog
        $changelog = false;
        
        if(isset($info->sections->changelog) && $update_available){
            $changelog = acf_get_instance('ACF_Admin_Updates')->get_changelog_changes($info->sections->changelog, $remote_version);
        }
        
        ?>
        <div class="acf-box" id="acfe-license-information">
            <div class="title">
                <h3><?php _e('ACF Extended: License Information', 'acf'); ?></h3>
            </div>
            <div class="inner">

                <p><?php printf(__('To unlock updates, please enter your license key below. If you don\'t have a licence key, please see <a href="%s" target="_blank">details & pricing</a>.','acf'), esc_url('https://www.acf-extended.com/pro')); ?></p>
                
                <form action="" method="post">
                    
                    <?php acf_nonce_input($nonce); ?>
                    
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="acfe_pro_licence"><?php _e('License Key', 'acf'); ?></label>
                            </th>
                            <td>
                                <?php
                                
                                // render field
                                acf_render_field(array(
                                    'type'      => 'text',
                                    'name'      => 'acfe_pro_licence',
                                    'value'     => str_repeat('*', strlen($license)),
                                    'readonly'  => $readonly,
                                ));
                                
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input type="submit" value="<?php echo esc_attr($button); ?>" class="button button-primary">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
        
            </div>
    
        </div>

        <div class="acf-box" id="acfe-update-information">
            <div class="title">
                <h3><?php _e('ACF Extended: Update Information', 'acf'); ?></h3>
            </div>
            <div class="inner">
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th>
                            <label><?php _e('Current Version', 'acf'); ?></label>
                        </th>
                        <td>
                            <?php echo esc_html($current_version); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            <label><?php _e('Latest Version', 'acf'); ?></label>
                        </th>
                        <td>
                            <?php echo esc_html($remote_version); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            <label><?php _e('Update Available', 'acf'); ?></label>
                        </th>
                        <td>
                            <?php if($update_available): ?>

                                <span style="margin-right: 5px;"><?php _e('Yes', 'acf'); ?></span>
                
                                <?php if($active): ?>
                                    <a class="button button-primary" href="<?php echo esc_attr( admin_url('plugins.php?s=Advanced+Custom+Fields:+Extended+PRO') ); ?>">
                                        <?php _e('Update Plugin', 'acf'); ?>
                                    </a>
                                <?php else: ?>
                                    <a class="button" disabled="disabled" href="#"><?php _e('Please enter your license key above to unlock updates', 'acf'); ?></a>
                                <?php endif; ?>
            
                            <?php else: ?>

                                <span style="margin-right: 5px;"><?php _e('No', 'acf'); ?></span>
                                <a class="button" href="<?php echo esc_attr(add_query_arg('acfe-pro-check', 1)); ?>"><?php _e('Check Again', 'acf'); ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <?php if($changelog): ?>
                        <tr>
                            <th>
                                <label><?php _e('Changelog', 'acf'); ?></label>
                            </th>
                            <td class="changelog">
                                <?php echo acf_esc_html($changelog); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                    </tbody>
                </table>

            </div>

        </div>
        <script>
        (function($){
            
            // Insert After
            $('#acfe-license-information, #acfe-update-information').insertAfter('#acf-update-information');
            
            // Wrap ACF
            $('#acf-license-information').wrapAll('<div class="acf-updates-wrap" />');
            $('#acf-update-information').insertAfter('#acf-license-information');
            
            // Wrap ACFE
            $('#acfe-license-information').wrapAll('<div class="acfe-updates-wrap" />');
            $('#acfe-update-information').insertAfter('#acfe-license-information');
            
            $('#acf-license-information h3, #acf-update-information h3').prepend('ACF: ');

        })(jQuery);
        </script>
        <?php
        
    }
    
    function get_error_message($error){
        
        // default
        $message = __('An error occurred, please try again.', 'acfe');
        
        if($error === 'expired'){
            
            $message = __('Your license key has expired.', 'acfe');
            
        }elseif($error === 'disabled' || $error === 'revoked'){
            
            $message = __('Your license key has been disabled.', 'acfe');
            
        }elseif($error === 'missing'){
            
            $message = __('Licence key invalid.', 'acf');
            
        }elseif($error === 'invalid' || $error === 'site_inactive'){
            
            $message = __('Your license is not active for this URL.', 'acfe');
            
        }elseif($error === 'item_name_mismatch'){
            
            $message = __('This appears to be an invalid license key for ACF Extended Pro.', 'acfe');
            
        }elseif($error === 'no_activations_left'){
            
            $message = __('Your license key has reached its activation limit.', 'acfe');
            
        }elseif($error === 'success'){
            
            $message = __('<b>Licence key activated</b>. Updates are now enabled.', 'acf');
            
        }
        
        return $message;
        
    }

}

acf_new_instance('ACFE_Updates');

endif;