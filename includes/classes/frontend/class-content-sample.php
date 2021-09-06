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

// Alias namespaces.
use SiteCore\Classes\Vendor as Vendor;

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
					$content = $this->archive_content();

				// If the post is singular and if it is in the loop.
				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {
					$content = $this->single_content();

				// If the post is in taxonomy archive pages and if it is in the loop.
				} elseif ( is_tax( 'sample_tax' ) && is_main_query() && in_the_loop() ) {
					$content = $this->taxonomy_content();

				}
			}
		}

		// Return the modified or unmodified content.
		return $content;
	}

	/**
	 * Post type archive content
	 *
	 * A partials subdirectory is used because many themes
	 * have more markup in the content directory files than
	 * simply the content section, which this replaces.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function archive_content() {

		// Instantiate Plugin_ACF class to get the suffix.
		$acf = new Vendor\Plugin_ACF;

		// Look for a archive content template in the active theme.
		$template = locate_template( 'template-parts/content/content-archive-sample' . $acf->suffix() . '.php' );

		// If the active theme has a template, use that.
		if ( ! empty( $template ) ) {
			get_template_part( 'template-parts/content/content-archive-sample' . $acf->suffix() );

		// Use the plugin template if no theme template is found.
		} else {
			include SCP_PATH . 'views/frontend/content/content-archive-sample' . $acf->suffix() . '.php';
		}
	}

	/**
	 * Single post type content
	 *
	 * A partials subdirectory is used because many themes
	 * have more markup in the content directory files than
	 * simply the content section, which this replaces.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	 public function single_content() {

		// Instantiate Plugin_ACF class to get the suffix.
		$acf = new Vendor\Plugin_ACF;

		// Look for a single content template in the active theme.
		$template = locate_template( 'template-parts/content/content-single-sample' . $acf->suffix() . '.php' );

		// If the active theme has a template, use that.
		if ( ! empty( $template ) ) {
			get_template_part( 'template-parts/content/content-single-sample' . $acf->suffix() );

		// Use the plugin template if no theme template is found.
		} else {
			include SCP_PATH . 'views/frontend/content/content-single-sample' . $acf->suffix() . '.php';
		}
	}

	/**
	 * Taxonomy archive content
	 *
	 * A partials subdirectory is used because many themes
	 * have more markup in the content directory files than
	 * simply the content section, which this replaces.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function taxonomy_content() {

		// Instantiate Plugin_ACF class to get the suffix.
		$acf = new Vendor\Plugin_ACF;

		// Look for a taxonomy content template in the active theme.
		$template = locate_template( 'template-parts/content/content-taxonomy-sample' . $acf->suffix() . '.php' );

		// If the active theme has a template, use that.
		if ( ! empty( $template ) ) {
			get_template_part( 'template-parts/content/content-taxonomy-sample' . $acf->suffix() );

		// Use the plugin template if no theme template is found.
		} else {
			include SCP_PATH . 'views/frontend/content/content-taxonomy-sample' . $acf->suffix() . '.php';
		}
	}
}
