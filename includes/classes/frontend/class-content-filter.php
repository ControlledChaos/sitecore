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

// Alias namespaces.
use SiteCore\Classes\Vendor as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Content_Filter {

	/**
	 * Post types
	 *
	 * Array of the post types to be filtered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the post types to be filtered.
	 */
	public $post_types = [];

	/**
	 * Post taxonomies
	 *
	 * Array of the taxonomies to be filtered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the taxonomies to be filtered.
	 */
	public $post_taxes = [];

	/**
	 * Post formats
	 *
	 * Array of the formats to be filtered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the formats to be filtered.
	 */
	public $post_formats = [];

	/**
	 * Content filter priority
	 *
	 * When to filter the content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    integer The numeral to set filter priority.
	 */
	public $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $post_types, $post_taxes, $post_formats, $priority ) {

		$types   = [];
		$taxes   = [];
		$formats = [];

		$this->post_types   = wp_parse_args( $post_types, $types );
		$this->post_taxes   = wp_parse_args( $post_taxes, $taxes );
		$this->post_formats = wp_parse_args( $post_formats, $formats );
		$this->priority     = $priority;

		// Add content filter if post types are set.
		if ( $this->post_types() || $this->post_taxes() || $this->post_formats() ) {
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
	 * Check for taxonomies
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean Returns true if the post_taxes property
	 *                 has at least one taxonomy.
	 */
	private function post_taxes() {
		if ( isset( $this->post_taxes ) && is_array( $this->post_taxes ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check for formats
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean Returns true if the post_formats property
	 *                 has at least one formats.
	 */
	private function post_formats() {
		if ( isset( $this->post_formats ) && is_array( $this->post_formats ) ) {
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

		// Get the array of post types & taxonomies to be filtered.
		$types = $this->post_types;
		$taxes = $this->post_taxes;

		// Default content for post types not modified.
		$content = $content;

		// Modify the content for each post type in the post_types property.
		foreach ( $types as $type ) {

			$this->before_content();

			// If the post type matches one in the loop.
			if ( $type == get_post_type( get_the_ID() ) ) {

				/**
				 * If the post is in its post type archive
				 * and if the content is in the loop.
				 */
				if ( is_post_type_archive( $type ) && is_main_query() && in_the_loop() ) {
					$content = $this->archive_content();

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {
					$content = $this->single_content();

				// If the post is in taxonomy archive pages and if it is in the loop.
				} elseif ( is_tax( 'sample_tax' ) && is_main_query() && in_the_loop() ) {
					$content = $this->taxonomy_content();

				}
			}

			$this->after_content();
		}

		// Return the modified or unmodified content.
		return $content;
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
