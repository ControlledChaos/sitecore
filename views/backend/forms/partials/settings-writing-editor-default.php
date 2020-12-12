<?php
/**
 * Form fields for the default editor option
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
		<input type="radio" name="classic-editor-replace" id="classic-editor-classic" value="classic"<?php if ( $settings['editor'] === 'classic' ) echo ' checked'; ?> />
		<label for="classic-editor-classic"><?php _ex( 'Rich text editor', 'Editor Name', SCP_DOMAIN ); ?></label>
	</p>
	<p>
		<input type="radio" name="classic-editor-replace" id="classic-editor-block" value="block"<?php if ( $settings['editor'] !== 'classic' ) echo ' checked'; ?> />
		<label for="classic-editor-block"><?php _ex( 'Block editor', 'Editor Name', SCP_DOMAIN ); ?></label>
	</p>
</div>
<script>
jQuery( 'document' ).ready( function( $ ) {
	if ( window.location.hash === '#classic-editor-options' ) {
		$( '.classic-editor-options' ).closest( 'td' ).addClass( 'highlight' );
	}
} );
</script>
