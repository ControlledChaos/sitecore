<?php
/**
 * Frontend meta tags
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front\Meta;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Meta_Tags {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Print meta tags to the head.
		add_action( 'wp_head', [ $this, 'meta' ] );
		add_action( 'wp_head', [ $this, 'schema' ] );
		add_action( 'wp_head', [ $this, 'open_graph' ] );
		add_action( 'wp_head', [ $this, 'twitter' ] );
		add_action( 'wp_head', [ $this, 'dublin' ] );
	}

	/**
	 * Print meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function print_meta_tags() {
		return apply_filters( 'scp_meta_print_tags', true );
	}

	/**
	 * Standard meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta() {
		include SCP_PATH . 'views/frontend/meta-tags/standard.php';
	}

	/**
	 * Schema meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function schema() {
		include SCP_PATH . 'views/frontend/meta-tags/schema.php';
	}

	/**
	 * Open Graph meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function open_graph() {
		include SCP_PATH . 'views/frontend/meta-tags/open-graph.php';
	}

	/**
	 * Twitter meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function twitter() {
		include SCP_PATH . 'views/frontend/meta-tags/twitter.php';
	}

	/**
	 * Dublin Core meta tags
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dublin() {
		include SCP_PATH . 'views/frontend/meta-tags/dublin.php';
	}
}
