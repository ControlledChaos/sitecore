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

$settings = new Settings\Settings_Fields_Admin_Menu;

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
		</tbody>
	</table>
	<?php do_action( 'scp_after_admin_menu_settings' ); ?>
</div>

