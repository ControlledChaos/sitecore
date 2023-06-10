<?php
/**
 * Meta tags settings tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

?>
<div>
	<p class="description"><?php _e( 'Meta data tags for SEO and embed display.', 'sitecore' ); ?></p>
	<?php do_action( 'scp_before_meta_tags_settings' ); ?>

	<table class="form-table" role="presentation">
		<?php
		settings_fields( 'custom-content' );
		do_settings_fields( 'custom-content', 'scp-options-meta-tags' );
		?>
	</table>
	<?php do_action( 'scp_after_meta_tags_settings' ); ?>
</div>
