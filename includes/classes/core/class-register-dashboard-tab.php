<?php
/**
 * Dashboard tab post type
 *
 * Used to replace the tabbed content on
 * the custom user dashboard.
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

class Register_Dashboard_Tab extends Register_Type {

	/**
	 * Constructor method
	 *
	 * @see Register_Type::__construct()
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		/**
		 * Keep singular and plural names lowercase and the
		 * parent class will capitalize them where appropriate.
		 */
		$labels = [
			'singular'    => __( 'dashboard tab', 'sitecore' ),
			'plural'      => __( 'dashboard tabs', 'sitecore' ),
			'description' => '',
			'menu_icon'   => 'dashicons-index-card',
			'excerpt_mb'  => false
		];

		$options = [
			'menu_position'       => 20,
			'capabilities'        => [
				'edit_'   . $this->type_key => 'manage_options',
				'delete_' . $this->type_key => 'manage_options',
				'edit_post'          => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_post'          => 'manage_options',
				'read_private_posts' => 'manage_options',
				'delete_posts'       => 'manage_options'
			],
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'use_block_editor'    => false,
			'show_in_menu'        => 'index.php',
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'taxonomies'          => [],
			'has_archive'         => false,
			'supports'            => [
				'title',
				'editor',
				'page-attributes'
			]
		];

		parent :: __construct(
			'dashboard_tab',
			$labels,
			$options,
			$this->priority,
			false
		);
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

		// Only modify this post type.
		if ( $this->type_key != $post_type ) {
			return $args;
		}

		// Sample option.
		// $args['menu_position'] = 3;

		return $args;
	}

	/**
	 * Rewrite post type labels
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns new values for array label keys.
	 */
	public function rewrite_labels() {

		// Post type.
		$post_type = $this->type_key;
		$type_obj  = get_post_type_object( $post_type );

		// New post type labels.
		$type_obj->labels->all_items = __( 'All Tabs', 'sitecore' );
		$type_obj->labels->add_new   = __( 'New Tab', 'sitecore' );
	}
}
