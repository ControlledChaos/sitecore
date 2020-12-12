<?php
/**
 * Form fields for network default editor option
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @access     public
 * @since      1.0.0
 */

// Editor settings.
$editor     = get_network_option( null, 'default-editor-replace' );
$is_checked = ( get_network_option( null, 'tinymce-editor-allow-sites' ) === 'allow' );

?>
<h2 id="tinymce-editor-options"><?php _e( 'Editor Settings', SCP_DOMAIN ); ?></h2>

<table class="form-table">
	<?php wp_nonce_field( 'allow-site-admin-settings', 'tinymce-editor-network-settings' ); ?>
	<tr>
		<th scope="row"><?php _e( 'Default editor for all sites', SCP_DOMAIN ); ?></th>
		<td>
			<p>
				<input type="radio" name="default-editor-replace" id="classic-editor-classic" value="tinymce"<?php if ( $editor !== 'block' ) echo ' checked'; ?> />
				<label for="classic-editor-classic"><?php _ex( 'Rich text editor', 'Editor Name', SCP_DOMAIN ); ?></label>
			</p>
			<p>
				<input type="radio" name="default-editor-replace" id="classic-editor-block" value="block"<?php if ( $editor === 'block' ) echo ' checked'; ?> />
				<label for="classic-editor-block"><?php _ex( 'Block editor', 'Editor Name', SCP_DOMAIN ); ?></label>
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e( 'Change settings', SCP_DOMAIN ); ?></th>
		<td>
			<input type="checkbox" name="tinymce-editor-allow-sites" id="tinymce-editor-allow-sites" value="allow"<?php if ( $is_checked ) echo ' checked'; ?>>
			<label for="tinymce-editor-allow-sites"><?php _e( 'Allow site admins to change settings', SCP_DOMAIN ); ?></label>
			<p class="description"><?php _e( 'By default the block editor is replaced with the rich text editor and users cannot switch editors.', SCP_DOMAIN ); ?></p>
		</td>
	</tr>
</table>
