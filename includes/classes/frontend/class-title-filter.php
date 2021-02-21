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
	 * @access private
	 * @var    array Array of the post types to be filtered.
	 */
	private $post_types = [];

	/**
	 * Title filter priority
	 *
	 * When to filter the title.
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

		// Add title filter if post types are set.
		if ( $this->post_types() ) {
			add_filter( 'the_title', [ $this, 'the_title' ], $this->priority, 2 );
		}
	}

	/**
	 * Check for post types
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean Returns true if the post_types property
	 *                 has at least one post type.
	 */
	private function post_types() {
		if ( isset( $this->post_types ) && is_array( $this->post_types ) ) {
			return true;
		}
		return false;
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
		return $title;
	}
}
