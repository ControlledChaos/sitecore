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

		parent :: __construct( $types, 10 );
	}

	/**
	 * Title text
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $title The value of the title field.
	 * @param  integer $id The ID of the post.
	 * @return string Returns the text of the post title.
	 */
	public function the_title( $title, $id ) {

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Default title for post types not modified.
		$title = $title;

		// Modify the title for each post type in the post_types property.
		foreach ( $types as $type ) {

			// If the post type matches one in the loop.
			if ( $type == get_post_type( $id ) ) {

				/**
				 * Using `in_the_loop()` will modify all instances of
				 * the title in the loop, including the post navigation.
				 * Post navigation outside of the loop is not modified.
				 */

				/**
				 * If the post is in its post type archive
				 * and if the title is in the loop.
				 */
				if ( is_post_type_archive( $type ) && is_main_query() && in_the_loop() ) {

					// Text specific to the archive.
					$title = sprintf(
						'%s%s',
						__( 'Archived Post #', 'sitecore' ),
						$id
					);

				// If the post is in the main blog pages.
				} elseif ( is_home() && is_main_query() && in_the_loop() ) {

					// Text specific to the blog.
					$title = sprintf(
						'%s%s',
						__( 'Blog Post #', 'sitecore' ),
						$id
					);

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {

					// Text specific to the single post.
					$title = sprintf(
						'%s%s',
						__( 'Post #', 'sitecore' ),
						$id
					);

				}
			}
		}

		// Return the modified or unmodified title.
		return $title;
	}
}
