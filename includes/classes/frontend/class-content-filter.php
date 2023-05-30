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

		add_action( 'init', [ $this, 'custom_content' ], $this->priority );
	}

	/**
	 * Custom content
	 *
	 * Adds the content filter.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function custom_content() {
		if ( $this->post_types() || $this->post_taxes() || $this->post_formats() ) {
			add_filter( 'the_content', [ $this, 'the_content' ], 10, 1 );
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
	 * Get single post type content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_singular_content() {
		return $this->singular_content();
	}

	/**
	 * Post index title
	 *
	 * Title if the post is in the main blog pages.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_index_content() {
		return $this->get_singular_content();
	}

	/**
	 * Post type archive title
	 *
	 * Title if the post is in its post type archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_post_type_content() {
		return $this->get_index_content();
	}

	/**
	 * Get post type archive content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_archive_content() {
		return $this->archive_content();
	}

	/**
	 * Category archive title
	 *
	 * Title if the post is in a category archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_category_content() {
		return $this->get_archive_content();
	}

	/**
	 * Tag archive title
	 *
	 * Title if the post is in a tag archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_tag_content() {
		return $this->get_archive_content();
	}

	/**
	 * Date archive title
	 *
	 * Title if the post is in a date archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_date_content() {
		return $this->get_archive_content();
	}

	/**
	 * Author archive title
	 *
	 * Title if the post is in an author archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function get_author_content() {
		return $this->get_archive_content();
	}

	/**
	 * Get taxonomy archive content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_taxonomy_content() {
		return $this->get_archive_content();
	}

	/**
	 * Single post type content
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function singular_content() {
		return get_post_field( 'post_content', get_the_ID() );
	}

	/**
	 * Post type archive content
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function archive_content() {
		return get_post_field( 'post_content', get_the_ID() );
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
					$content = $this->get_archive_content();

				} elseif ( is_category() && is_main_query() && in_the_loop() ) {
					$content = $this->get_category_content();

				} elseif ( is_tag() && is_main_query() && in_the_loop() ) {
					$content = $this->get_tag_content();

				} elseif ( is_date() && is_main_query() && in_the_loop() ) {
					$content = $this->get_date_content();

				} elseif ( is_author() && is_main_query() && in_the_loop() ) {
					$content = $this->get_author_content();

				} elseif ( is_archive() && is_main_query() && in_the_loop() ) {
					$content = $this->get_archive_content();

				// If the post is in the blog index and if it is in the loop.
				} elseif ( is_home( $type ) && is_main_query() && in_the_loop() ) {
					$content = $this->get_singular_content();

				// If the post is in taxonomy archive pages and if it is in the loop.
				} elseif ( is_tax() && is_main_query() && in_the_loop() ) {
					$content = $this->get_taxonomy_content();

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {
					$content = $this->get_singular_content();
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
