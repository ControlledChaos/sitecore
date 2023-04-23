<?php
/**
 * Form fields for network admin settings dashboard tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<?php do_action( 'scp_before_network_admin_dashboard_settings' ); ?>
	<table class="form-table" role="presentation">
		<?php
		settings_fields( 'options-admin' );
		do_settings_fields( 'options-admin', 'scp-settings-section-network-admin-dashboard' );
		?>
	</table>
	<?php do_action( 'scp_after_network_admin_dashboard_settings' ); ?>
</div>
