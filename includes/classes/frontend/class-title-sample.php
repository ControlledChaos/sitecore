<?php
/**
 * Sample post title filter
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Title_Sample extends Title_Filter {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$types = [
			'post',
			'sample_type'
		];

		$title = [];

		parent :: __construct( $types, $title, 10 );
	}

	/**
	 * Singular post title
	 *
	 * Title if the post is singular.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function singular_title() {

		if ( ! $this->is_post_type() ) {
			return;
		}

		$title = null;

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Modify the title for each post type in the post_types property.
		foreach ( $types as $type ) {

			// If the post type matches one in the loop.
			if ( $type == get_post_type( get_the_ID() ) ) {

				$object = get_post_type_object( $type );

				if ( $object->labels->singular_name ) {
					$name = $object->labels->singular_name;
				} else {
					$name = $object->labels->name;
				}

				$title = sprintf(
					__( 'Filtered Demo Title: %s #%s', 'sitecore' ),
					$name,
					get_the_ID()
				);
			}
		}
		return $title;
	}

	/**
	 * Post archive title
	 *
	 * Title if the post is in its post type archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function archive_title() {

		if ( ! $this->is_post_type() ) {
			return;
		}

		$title = null;

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Modify the title for each post type in the post_types property.
		foreach ( $types as $type ) {

			// If the post type matches one in the loop.
			if ( $type == get_post_type( get_the_ID() ) ) {

				$object = get_post_type_object( $type );

				if ( $object->labels->singular_name ) {
					$name = $object->labels->singular_name;
				} else {
					$name = $object->labels->name;
				}

				$title = sprintf(
					__( 'Filtered Demo Title: Archived %s #%s', 'sitecore' ),
					$name,
					get_the_ID()
				);
			}
		}
		return $title;
	}
}
