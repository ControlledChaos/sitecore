<?php
/**
 * Form fields for network default editor option
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

// Editor settings.
$editor     = get_network_option( null, 'editor-options-replace' );
$is_checked = ( get_network_option( null, 'tinymce-editor-allow-sites' ) === 'allow' );

?>
<h2 id="tinymce-editor-options"><?php _e( 'Editor Settings', 'sitecore' ); ?></h2>

<table class="form-table">
	<?php wp_nonce_field( 'allow-site-admin-settings', 'tinymce-editor-network-settings' ); ?>
	<tr>
		<th scope="row"><?php _e( 'Default editor for all sites', 'sitecore' ); ?></th>
		<td>
			<p>
				<input type="radio" name="editor-options-replace" id="editor-options-tinymce" value="tinymce"<?php if ( $editor !== 'block' ) echo ' checked'; ?> />
				<label for="editor-options-tinymce"><?php _ex( 'Rich text editor', 'Editor Name', 'sitecore' ); ?></label>
			</p>
			<p>
				<input type="radio" name="editor-options-replace" id="editor-options-block" value="block"<?php if ( $editor === 'block' ) echo ' checked'; ?> />
				<label for="editor-options-block"><?php _ex( 'Block editor', 'Editor Name', 'sitecore' ); ?></label>
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e( 'Change settings', 'sitecore' ); ?></th>
		<td>
			<input type="checkbox" name="tinymce-editor-allow-sites" id="tinymce-editor-allow-sites" value="allow"<?php if ( $is_checked ) echo ' checked'; ?>>
			<label for="tinymce-editor-allow-sites"><?php _e( 'Allow site admins to change settings', 'sitecore' ); ?></label>
			<p class="description"><?php _e( 'By default the block editor is replaced with the rich text editor and users cannot switch editors.', 'sitecore' ); ?></p>
		</td>
	</tr>
</table>
