<?php
/**
 * Content for sample post type archive
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

printf(
	'<p>%s%s</p>',
	__( 'Content for archived post #', SCP_DOMAIN ),
	get_the_ID()
);
