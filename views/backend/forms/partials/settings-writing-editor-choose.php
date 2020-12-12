<?php
/**
 * Form fields for allow editor choice option
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @access     public
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Classes\Core as Core;

// Editor settings.
$settings = Core\Editor_Options :: get_settings( 'refresh' );

?>
<div class="classic-editor-options">
	<p>
		<input type="radio" name="classic-editor-allow-users" id="classic-editor-allow" value="allow"<?php if ( $settings['allow-users'] ) echo ' checked'; ?> />
		<label for="classic-editor-allow"><?php _e( 'Yes', SCP_DOMAIN ); ?></label>
	</p>
	<p>
		<input type="radio" name="classic-editor-allow-users" id="classic-editor-disallow" value="disallow"<?php if ( ! $settings['allow-users'] ) echo ' checked'; ?> />
		<label for="classic-editor-disallow"><?php _e( 'No', SCP_DOMAIN ); ?></label>
	</p>
</div>
