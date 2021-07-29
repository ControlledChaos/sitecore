<?php
/**
 * Content settings sample tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;
use SiteCore\Classes\Admin as Admin;

// Instance of the Manage_Website_Page class.
$page = new Admin\Content_Settings;

?>
<div>
	<p><?php _e( 'Sample tab content.', 'sitecore' ); ?></p>
</div>
