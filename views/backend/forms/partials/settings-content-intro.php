<?php
/**
 * Content settings intro tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;

function content_settings_intro( $content = '' ) {

	// If ACF and ACF Extended are active.
	if ( current_user_can( 'develop' ) && class_exists( 'acf' ) && class_exists( 'acfe' ) ) {

		$content = sprintf(
			'<p>%s</p>',
			__( 'ACFE intro.', 'sitecore' )
		);

	} else {

		$content = sprintf(
			'<p>%s</p>',
			__( 'Default intro.', 'sitecore' )
		);
	}

	echo apply_filters( 'scp_content_settings_intro_text', $content );
}
add_action( 'scp_content_settings_intro', __NAMESPACE__ . '\content_settings_intro' );

?>
<div>
	<?php do_action( 'scp_before_content_settings_intro' ); ?>
	<?php do_action( 'scp_content_settings_intro' ); ?>
	<?php do_action( 'scp_after_content_settings_intro' ); ?>
</div>
