<?php
/**
 * Form fields for admin settings menu tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;
use SiteCore\Classes\Admin as Admin;


settings_fields( 'scp-site-admin-menu' );
do_settings_sections( 'scp-site-admin-menu' );

