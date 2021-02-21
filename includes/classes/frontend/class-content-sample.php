<?php
/**
 * Sample post content filter
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

class Content_Sample extends Content_Filter {

	/**
	 * Post types
	 *
	 * Array of the post types to be filtered,
	 * as they are registered.
	 *
	 * @example [ 'post', 'sample_type' ]
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array Array of the post types to be filtered.
	 */
	private $post_types = [
		'post',
		'sample_type'
	];

	/**
	 * Content filter priority
	 *
	 * When to filter the content.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    integer The numeral to set filter priority.
	 */
	private $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run the parent constructor method.
		parent :: __construct();
	}

	/**
	 * Filter content
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $content The value of the content field.
	 * @return mixed Returns the content to be filtered or
	 *               returns the unfiltered content if post types don't match.
	 */
	public function the_content( $content ) {

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Default content for post types not modified.
		$content = $content;

		// Modify the content for each post type in the post_types property.
		foreach ( $types as $type ) {

			$id = get_the_ID();

			// If the post type matches one in the loop.
			if ( $type == get_post_type( $id ) ) {

				/**
				 * If the post is in its post type archive
				 * and if the content is in the loop.
				 */
				if ( is_post_type_archive( $type ) && is_main_query() && in_the_loop() ) {

					// Text specific to the archive.
					$content = sprintf(
						'<p>%s%s</p>',
						__( 'Content for archived post #', SCP_DOMAIN ),
						$id
					);

				// If the post is in the main blog pages.
				} elseif ( is_home() && is_main_query() && in_the_loop() ) {

					// Text specific to the blog.
					$content = sprintf(
						'<p>%s%s</p>',
						__( 'Content for blog post #', SCP_DOMAIN ),
						$id
					);

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {

					// Text specific to the single post.
					$content = sprintf(
						'<p>%s%s</p>',
						__( 'Content for post #', SCP_DOMAIN ),
						$id
					);

				}
			}
		}

		// Return the modified or unmodified content.
		return $content;
	}
}
