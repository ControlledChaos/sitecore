<?php
/**
 * Form fields for developer content settings tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<?php do_action( 'scp_before_dev_content_settings' ); ?>
	<table class="form-table">
		<?php
		settings_fields( 'developer-tools' );
		do_settings_fields( 'developer-tools', 'scp-options-developer-content' );
		?>
	</table>
	<?php do_action( 'scp_after_dev_content_settings' ); ?>
</div>
