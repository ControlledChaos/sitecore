<?php
/**
 * Form fields for network admin settings menu tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

use SiteCore\Classes\Settings as Settings;
use function SiteCore\Core\is_classicpress;

$settings = new Settings\Settings_Fields_Admin_Menu;

// Determine whether blocks are used.
$editor_replace     = get_option( 'editor-options-replace' );
$editor_allow_users = get_option( 'editor-options-allow-users' );
$show_blocks        = false;

if ( 'block' == $editor_replace || ( 'tinymce' == $editor_replace && 'allow' == $editor_allow_users ) ) {
	$show_blocks = true;
}

settings_fields( 'options-admin' );

?>
<div>
	<?php do_action( 'scp_before_network_admin_menu_settings' ); ?>
	<table class="form-table" role="presentation">
		<?php
		settings_fields( 'options-admin' );
		do_settings_fields( 'options-admin', 'scp-settings-section-network-admin-menu' );
		?>
	</table>
	<?php do_action( 'scp_after_network_admin_menu_settings' ); ?>
</div>

