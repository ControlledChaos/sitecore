<?php
/**
 * Enhancements to post types and taxonomies
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Post types and taxonomies class.
 *
 * @since  1.0.0
 * @access public
 */
final class Type_Tax {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Post type & taxonomy sort order.
		new Types_Taxes_Order;

		// Taxonomy templates.
		new Taxonomy_Templates;

		// Add taxonomies to the page post type.
		add_action( 'init', [ $this, 'page_taxonomies' ] );
	}

	/**
	 * Page taxonomies
	 *
	 * Adds taxonomies to the page post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function page_taxonomies() {
		register_taxonomy_for_object_type( 'category', 'page' );
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
}
