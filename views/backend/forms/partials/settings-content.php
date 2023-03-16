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

?>
<div>
	<?php do_action( 'scp_before_content_settings' ); ?>
	<?php
	settings_fields( 'content-settings' );
	do_settings_sections( 'content-settings' );
	?>
	<?php do_action( 'scp_after_content_settings' ); ?>
</div>
