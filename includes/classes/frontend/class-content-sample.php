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

		$taxes = [
			// 'sample_tax'
		];

		$formats = [
			'aside'
		];

		parent :: __construct(
			$types,
			$taxes,
			$formats,
			10
		);
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
