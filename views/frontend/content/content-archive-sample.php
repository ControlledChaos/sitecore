<?php
/**
 * Content for sample post type archive
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
	__( '<p>Filtered content for archived %s #%s</p>', 'sitecore' ),
	$name,
	get_the_ID()
);

printf(
	__( '<p>The template for this notice is in the %s plugin.</p>', 'sitecore' ),
	SCP_NAME
);
