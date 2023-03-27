<?php
/**
 * Register admin pages post type
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
				'editor',
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

		// Add a page per post.
		add_action( 'admin_menu', [ $this, 'add_pages' ] );

		// Enqueue admin scripts & styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
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
	 * Rewrite post type labels
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns new values for array label arguments.
	 */
	public function rewrite_labels() {

		// Post type.
		$post_type = $this->type_key;
		$type_obj  = get_post_type_object( $post_type );

		// New post type labels.
		$type_obj->labels->all_items = __( 'Admin Pages', 'sitecore' );
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

	/**
	 * Add admin pages
	 *
	 * This uses this class' post type to add
	 * admin pages, applying the post title,
	 * post content, and included fields for
	 * Advanced Custom Fields.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_pages() {

		if ( ! class_exists( 'acf' ) ) {
			return;
		}

		$posts = get_posts( [
			'post_type'      => [ $this->type_key ],
			'post_status'    => [ 'publish' ]
		] );

		foreach ( $posts as $post ) {

			setup_postdata( $post );

			$post_id    = $post->ID;
			$capability = get_field( 'admin_post_capability', $post_id );
			$slug       = get_field( 'admin_post_page_slug', $post_id );
			$parent     = get_field( 'admin_post_parent_slug', $post_id );
			$menu_title = get_field( 'admin_post_menu_title', $post_id );
			$icon       = get_field( 'admin_post_icon_url', $post_id );
			$position   = get_field( 'admin_post_position', $post_id );
			$desc       = get_field( 'admin_post_description', $post_id );

			if ( $capability ) {
				$capability = $capability;
			} else {
				$capability = 'manage_options';
			}

			if ( $menu_title ) {
				$menu_title = $menu_title;
			} else {
				$menu_title = $post->post_title;
			}

			if ( $position ) {
				$position = $position;
			} else {
				$position = 85;
			}

			$content = function() use ( $post ) {
				printf(
					'<div class="wrap"><h1>%s</h1><p class="description">%s</p>%s</div>',
					$post->post_title,
					get_field( 'admin_post_description', $post->ID ),
					apply_filters( 'the_content', $post->post_content )
				);
			};

			// Submenu page if the parent slug field is filled.
			if ( $slug && $parent ) {
				add_submenu_page(
					$parent,
					$post->post_title,
					$menu_title,
					$capability,
					strtolower( $slug ),
					$content,
					$position
				);

			// Top-level page if no parent slug.
			} elseif ( $slug ) {
				add_menu_page(
					$post->post_title,
					$menu_title,
					$capability,
					strtolower( $slug ),
					$content,
					$icon,
					$position
				);
			}
		}
		wp_reset_postdata();
	}

	/**
	 * Enqueue scripts & styles
	 *
	 * Filter available for themes to style the admin pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		return apply_filters( 'scp_admin_pages_enqueue_scripts', null );
	}
}
