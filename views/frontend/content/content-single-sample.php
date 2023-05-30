<?php
/**
 * Content for singular sample post type
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

printf(
	__( '<p>Filtered content for %s #%s</p>', 'sitecore' ),
	$name,
	get_the_ID()
);

printf(
	__( '<p>This template is being displayed because the sample content filter class in the %s plugin has been instantiated.</p>', 'sitecore' ),
	SCP_NAME
);

printf(
	__( '<p>The template for this notice is in the %s plugin.</p>', 'sitecore' ),
	SCP_NAME
);
