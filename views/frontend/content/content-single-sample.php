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
	'<p>%s</p>',
	__( 'This template is being displayed because the sample content filter class has been instantiated.', 'sitecore' )
);
