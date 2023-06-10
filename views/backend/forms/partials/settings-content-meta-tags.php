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
	<?php
	// settings_fields( 'meta-tags' );
	// do_settings_sections( 'meta-tags' );
	?>
	<?php do_action( 'scp_after_meta_tags_settings' ); ?>
</div>
