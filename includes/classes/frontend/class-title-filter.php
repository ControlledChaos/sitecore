<?php
/**
 * Post title filter
 *
 * Extend this class to filter the post title
 * by post types.
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

class Title_Filter {

	/**
	 * Post types
	 *
	 * Array of the post types to be filtered,
	 * as they are registered.
	 *
	 * @example [ 'post', 'sample_type' ]
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the post types to be filtered.
	 */
	public $post_types = [];

	/**
	 * Title filter priority
	 *
	 * When to filter the title.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set filter priority.
	 */
	protected $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $post_types, $priority ) {

		$types = [];

		$this->post_types = wp_parse_args( $post_types, $types );
		$this->priority   = (int) $priority;

		add_action( 'init', [ $this, 'custom_title' ], $this->priority );
	}

	/**
	 * Check for post types
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Returns true if the post_types property
	 *                 has at least one post type.
	 */
	protected function post_types() {
		if ( isset( $this->post_types ) && is_array( $this->post_types ) ) {
			return true;
		}
		return false;
	}

	public function custom_title() {

		// Add title filter if post types are set.
		if ( $this->post_types() ) {
			add_filter( 'the_title', [ $this, 'the_title' ], 10, 1 );
		}
	}

	/**
	 * Title text
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $title The value of the title field.
	 * @return string Returns the text of the post title.
	 */
	public function the_title( $title = '' ) {

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Modify the title for each post type in the post_types property.
		foreach ( $types as $type ) {

			// Default title for post types not modified.
			$title = $title;

			// If the post type matches one in the loop.
			if ( $type == get_post_type( get_the_ID() ) ) {

				$object = get_post_type_object( $type );

				if ( $object->labels->singular_name ) {
					$name = $object->labels->singular_name;
				} else {
					$name = $object->labels->name;
				}

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
						__( 'Filtered Demo Title: Archived %s #%s', 'sitecore' ),
						$name,
						get_the_ID(),
					);

				// If the post is in the main blog pages.
				} elseif ( is_home() && is_main_query() && in_the_loop() ) {

					// Text specific to the blog.
					$title = sprintf(
						__( 'Filtered Demo Title: %s #%s', 'sitecore' ),
						$name,
						get_the_ID(),
					);

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {

					// Text specific to the single post.
					$title = sprintf(
						__( 'Filtered Demo Title: %s #%s', 'sitecore' ),
						$name,
						get_the_ID()
					);

				}
			}
		}

		// Return the modified or unmodified title.
		return $title;
	}
}
