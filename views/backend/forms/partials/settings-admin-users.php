<?php
/**
 * Form fields for admin settings users tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<?php do_action( 'scp_before_admin_users_settings' ); ?>
	<table class="form-table">
		<?php
		settings_fields( 'options-admin' );
		do_settings_fields( 'options-admin', 'scp-settings-section-admin-users' );
		?>
	</table>
	<?php do_action( 'scp_after_admin_users_settings' ); ?>
</div>
