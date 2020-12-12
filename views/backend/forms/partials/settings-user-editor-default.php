<?php
/**
 * Form fields for user default editor option
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @access     public
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Classes\Core as Core;

?>
<table class="form-table">
	<tr class="classic-editor-user-options">
		<th scope="row"><?php _e( 'Default Editor', SCP_DOMAIN ); ?></th>
		<td>
		<?php wp_nonce_field( 'allow-user-settings', 'classic-editor-user-settings' ); ?>
		<?php Core\Editor_Options :: editor_settings_default(); ?>
		</td>
	</tr>
</table>
<script>jQuery( 'tr.user-rich-editing-wrap' ).before( jQuery( 'tr.classic-editor-user-options' ) );</script>
