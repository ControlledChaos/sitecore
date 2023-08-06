<?php
/**
 * ACF content for singular sample post type
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

$object = get_post_type_object( get_post_type( get_the_ID() ) );

if ( $object->labels->singular_name ) {
	$name = $object->labels->singular_name;
} else {
	$name = $object->labels->name;
}

// Sample shortcode text.
$code_text = __( 'This is a sample shortcode, using the <code>[sample]</code> tag, appended to the filtered content using the <code>do_shortcode()</code> function.' );

// Sample shortcode tag.
$code_tag = "[sample wrap_text='yes' text_class='sample-paragraph' text_weight='600']{$code_text}[/sample]";

printf(
	__( '<p>Filtered content for %s #%s</p>', 'sitecore' ),
	$name,
	get_the_ID()
);

printf(
	__( '<p>This template is being displayed because the sample content filter class in the %s plugin has been instantiated. This template is also being displayed because the Advanced Custom Fields plugin is active or the bundled Applied Content Forms files in the %s plugin are included.</p>', 'sitecore' ),
	SCP_NAME,
	SCP_NAME
);

printf(
	__( '<p>The template for this notice is in the %s plugin.</p>', 'sitecore' ),
	SCP_NAME
);

// Append content with shortcode.
if ( shortcode_exists( 'sample' ) ) {
	echo do_shortcode( $code_tag );
}
