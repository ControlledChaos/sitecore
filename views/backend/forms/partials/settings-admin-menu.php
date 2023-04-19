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
	<?php do_action( 'scp_before_admin_menu_settings' ); ?>
	<table class="form-table" role="presentation">
		<tbody>
			<tr class="admin-field">
				<th scope="row"><?php _e( 'Link Positions', 'sitecore' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Manage Link Position Options', 'sitecore' ); ?></legend>
						<?php $settings->admin_menu_menus_top_callback(); ?><br />
						<?php $settings->admin_menu_widgets_top_callback(); ?>
					</fieldset>
				</td>
			</tr>
			<?php if ( ! is_classicpress() && $show_blocks ) : ?>
			<tr class="admin-field">
				<th scope="row"><?php _e( 'Content Types', 'sitecore' ); ?></th>
				<td>
					<p><?php _e( 'These content types are normally hidden from the admin menu.', 'sitecore' ); ?></p>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Show Hidden Content Types', 'sitecore' ); ?></legend>
						<?php $settings->admin_menu_nav_blocks_callback(); ?><br />
						<?php $settings->admin_menu_reuse_blocks_callback(); ?>
					</fieldset>
				</td>
			</tr>
			<?php endif; ?>
			<tr class="admin-field">
				<th scope="row"><?php _e( 'Custom Order', 'sitecore' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><?php _e( 'Custom Admin Menu Order', 'sitecore' ); ?></legend>
						<?php $settings->admin_menu_custom_order_callback(); ?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<?php do_action( 'scp_after_admin_menu_settings' ); ?>
</div>

