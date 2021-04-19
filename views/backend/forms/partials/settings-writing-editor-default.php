<?php
/**
 * Form fields for the default editor option
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Classes\Core as Core;

// Editor settings.
$settings = Core\Editor_Options :: get_settings( 'refresh' );

?>
<div class="tinymce-editor-options">
	<p>
		<input type="radio" name="editor-options-replace" id="editor-options-tinymce" value="tinymce"<?php if ( $settings['editor'] === 'tinymce' ) echo ' checked'; ?> />
		<label for="editor-options-tinymce"><?php _ex( 'Rich text editor', 'Editor Name', 'sitecore' ); ?></label>
	</p>
	<p>
		<input type="radio" name="editor-options-replace" id="editor-options-block" value="block"<?php if ( $settings['editor'] !== 'tinymce' ) echo ' checked'; ?> />
		<label for="editor-options-block"><?php _ex( 'Block editor', 'Editor Name', 'sitecore' ); ?></label>
	</p>
</div>
<script>
jQuery( 'document' ).ready( function( $ ) {
	if ( window.location.hash === '#tinymce-editor-options' ) {
		$( '.tinymce-editor-options' ).closest( 'td' ).addClass( 'highlight' );
	}
} );
</script>
