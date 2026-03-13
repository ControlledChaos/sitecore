<?php
/**
 * Content features tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<?php do_action( 'scp_before_features_settings' ); ?>
	<?php
	settings_fields( 'custom-content' );
	do_settings_sections( 'custom-content', 'scp-options-features' );
	?>
	<?php do_action( 'scp_after_features_settings' ); ?>
</div>
