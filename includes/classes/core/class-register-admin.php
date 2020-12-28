<?php
/**
 * Sample class to register a post type
 *
 * Copy this file and rename it to reflect
 * its new class name. Add to the autoloader
 * and intantiate where appropriate.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Admin extends Register_Type {

	/**
	 * Post type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The database name of the post type.
	 */
	protected $type_key = 'admin';

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the post type.
	 */
	protected $singular = 'admin page';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the post type.
	 */
	protected $plural = 'admin pages';

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The dashicon class for book.
	 */
	protected $menu_icon = 'dashicons-clipboard';

	/**
	 * Menu position
	 *
	 * If the content settings page is not available then
	 * put this as a top-level entry at or near the
	 * bottom of the menu.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set position.
	 */
	protected $menu_position = 99;

	/**
	 * Register priority
	 *
	 * Attempt to display below other content entries.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set hook priority.
	 */
	protected $priority = 20;

	/**
	 * Supports
	 *
	 * The built in fields/metaboxes supported by the post type.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of support.
	 */
	protected $supports = [
		'title',
		'thumbnail',
	];

	/**
	 * Supported taxonomies
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of supported taxonomies.
	 */
	protected $taxonomies = [];

	/**
	 * Settings page
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to create a settings page for this post type.
	 */
	protected $settings_page = true;

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
	 * New post type options
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Array of arguments for registering a post type.
	 * @param  string $post_type Post type key.
	 * @return array Returns an array of new option arguments.
	 */
	public function post_type_options( $args, $post_type ) {

		// Look for the content settings page and set as a variable.
		$content = get_plugin_page_hookname( 'content-settings', 'content-settings' );

		// Only modify this post type.
		if ( $this->type_key != $post_type ) {
			return $args;
		}

		// Only show under content settings if the page exists.
		if ( $content ) {

			// Set content settings as menu parent.
			$args['show_in_menu'] = 'content-settings';
		}

		// Only allow developer role to add & edit.
		$args['capabilities'] = [
			'edit_post'    => 'develop',
			'delete_post'  => 'develop',
			'edit_posts'   => 'develop',
			'delete_posts' => 'develop',
		];

		return $args;
	}

	/**
	 * Filter post type labels
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns new values for array label arguments.
	 */
	public function filter_labels() {

		// New post type labels.
		$labels = [
			'all_items' => 'Admin Pages'
		];

		return $labels;
	}

	/**
	 * Rewrite rules
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of rewrite rules.
	 */
	public function rewrite() {

		// No rewrite rules.
		$rewrite = [];

		return $rewrite;
	}
}
