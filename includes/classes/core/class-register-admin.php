<?php
/**
 * Sample class to register a post type
 *
 * Copy this file and rename it to reflect
 * its new class name. Add to the autoloader
 * and instantiate where appropriate.
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

class Register_Admin extends Register_Type {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'singular'    => __( 'admin page', 'sitecore' ),
			'plural'      => __( 'admin pages', 'sitecore' ),
			'description' => '',
			'menu_icon'   => 'dashicons-clipboard'
		];

		$options = [
			'public'              => false,
			'menu_position'       => 99,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'supports'            => [
				'title',
				'thumbnail',
			],
			'taxonomies'  => [],
			'has_archive' => false
		];

		parent :: __construct(
			'admin',
			$labels,
			$options,
			20,
			true
		);

		// Modify row actions in list UI.
		add_filter( 'post_row_actions', [ $this, 'row_actions' ], 10, 1 );
	}

	/**
	 * Modify row actions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of actions.
	 */
	public function row_actions( $actions ) {

		if ( $this->type_key === get_post_type() ) {

			// Remove the view link.
			unset( $actions['view'] );
		}

		return $actions;
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

		// Only show under content settings if the page exists & if developer.
		if ( $content && current_user_can( 'develop' ) ) {

			// Set content settings as menu parent.
			$args['show_in_menu'] = 'content-settings';
		}

		// Only allow developer role to add & edit.
		if ( ! current_user_can( 'develop' ) ) {
			$args['capabilities'] = [
				'edit_'   . $this->type_key => false,
				'delete_' . $this->type_key => false,
				'edit_posts'   => false,
				'delete_posts' => false
			];
		}

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
		$rewrite = [
			'with_front' => false,
			'feeds'      => false,
			'pages'      => false
		];

		return $rewrite;
	}
}
