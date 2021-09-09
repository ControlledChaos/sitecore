<?php
/**
 * Post content filter
 *
 * Extend this class to filter the
 * post content by post types.
 * Content is from the default rich text
 * editor or block editor in WordPress 5.0+.
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

class Content_Filter {

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

		// Add content filter if post types are set.
		if ( $this->post_types() ) {
			add_filter( 'the_content', [ $this, 'the_content' ], $this->priority, 1 );
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
	 * Filter content
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $content The value of the content field.
	 * @return mixed Returns the content to be filtered or
	 *               returns the unfiltered content if post types don't match.
	 */
	public function the_content( $content ) {
		$filtered  = $this->before_content();
		$filtered .= $content;
		$filtered .= $this->after_content();
		return $filtered;
	}

	/**
	 * Before content
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function before_content() {
		$before_content = do_action( 'SiteCore\before_content_filter' );
		return apply_filters( 'scp_before_content_filter', $before_content );
	}

	/**
	 * After content
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function after_content() {
		$after_content = do_action( 'SiteCore\after_content_filter' );
		return apply_filters( 'scp_after_content_filter', $after_content );
	}
}
