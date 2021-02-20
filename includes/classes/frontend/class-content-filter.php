<?php
/**
 * Content filter class
 *
 * Extend this class to filter the post title
 * and the default post content by post types.
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
	 * @example [ 'post', 'web_project' ]
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array Array of the post types to be filtered.
	 */
	protected $post_type = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Title filter.
		add_filter( 'the_title', [ $this, 'title' ], 10, 1 );

		// Content filter.
		add_filter( 'the_content', [ $this, 'content' ], 10, 1 );
	}

	/**
	 * Title filter
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the filtered title.
	 */
	public function title( $title ) {

		if ( is_array( $this->post_type ) && in_array( get_post_type( get_the_ID() ), $this->post_type ) ) {
			$title = '';
		}
		return $title;
	}

	/**
	 * Content filter
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the filtered content.
	 */
	public function content( $content ) {

		if ( is_array( $this->post_type ) && in_array( get_post_type( get_the_ID() ), $this->post_type ) ) {
			$content = '';
		}
		return $content;
	}
}
