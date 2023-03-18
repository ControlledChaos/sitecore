<?php
/**
 * Form fields for admin settings header tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<?php do_action( 'scp_before_admin_header_settings' ); ?>
	<table class="form-table">
		<?php
		settings_fields( 'options-admin' );
		do_settings_fields( 'options-admin', 'scp-settings-section-admin-header' );
		?>
	</table>
	<?php do_action( 'scp_after_admin_header_settings' ); ?>
</div>
