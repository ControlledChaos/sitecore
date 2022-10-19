<?php
/**
 * Base class to register a post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 *
 * To register a new post type create a class that
 * extends this class, including a constructor that
 * runs this parent constructor method.
 * ```
 * public function __construct() {
 *     parent :: __construct();
 * }
 * ```
 *
 * Add new variables for the key, names, supports, etc.
 * Override the `labels()` and `options()` methods if
 * more detailed replacement is needed.
 *
 * @see includes/classes/core/class-register-sample.php
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Type {

	/**
	 * Post type
	 *
	 * Maximum 20 characters. May only contain lowercase alphanumeric
	 * characters, dashes, and underscores. Dashes discouraged.
	 *
	 * @example 'book'
	 * @example 'car_part'
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The database name of the post type.
	 */
	public $type_key = '';

	/**
	 * Post type labels
	 *
	 * Various text for the post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array An array of post type labels.
	 */
	public $type_labels = [];

	/**
	 * Post type options
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array An array of post type options.
	 */
	public $type_options = [];

	/**
	 * Settings page
	 *
	 * Add a settings page for the post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    boolean Whether to create a settings page for this post type.
	 */
	public $settings_page = false;

	/**
	 * Register priority
	 *
	 * When to register the post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    integer The numeral to set hook priority.
	 */
	public $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $type_key, $type_labels, $type_options, $priority, $settings ) {

		$labels = [
			'singular'    => '',
			'plural'      => '',
			'description' => '',
			'menu_icon'   => 'dashicons-admin-post'
		];

		$options = [
			'public'                => true,
			'hierarchical'          => false,
			'menu_position'         => 5,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'use_block_editor'      => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => true,
			'show_in_rest'          => true,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'capabilities'          => [],
			'map_meta_cap'          => null,
			'supports'              => [
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'revisions',
				'page-attributes',
				'post-formats'
			],
			'register_meta_box_cb' => null,
			'taxonomies'           => [
				'category',
				'post_tag'
			],
			'has_archive'      => true,
			'can_export'       => true,
			'delete_with_user' => null,
			'template_lock'    => false,
			'_builtin'         => false
		];

		$this->type_key      = $type_key;
		$this->type_labels   = wp_parse_args( $type_labels, $labels );
		$this->type_options  = wp_parse_args( $type_options, $options );
		$this->priority      = $priority;
		$this->settings_page = $settings;

		// Register post type.
		add_action( 'init', [ $this, 'register' ], $this->priority );

		// New post type options.
		add_filter( 'register_post_type_args', [ $this, 'post_type_options' ], 10, 2 );

		// Use block editor.
		// add_filter( 'use_block_editor_for_post_type', [ $this, 'use_block_editor' ], 10, 1 );

		// Rewrite post type labels.
		add_action( 'wp_loaded', [ $this, 'rewrite_labels' ] );

		// Field groups.
		add_action( 'acf/init', [ $this, 'field_groups' ] );
	}

	/**
	 * Register post type
	 *
	 * Note for WordPress 5.0 or greater:
	 * If you want your post type to adopt the block edit_form_image_editor
	 * rather than using the rich text editor then set `show_in_rest` to `true`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function register() {

		register_post_type(
			strtolower( $this->type_key ),
			$this->options()
		);
	}

	/**
	 * Post type options
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns the array of post type options,
	 *               including labels from $this->labels().
	 */
	protected function options() {

		$options = [
			'label'                 => __( ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'labels'                => $this->labels(),
			'description'           => __( ucfirst( $this->type_labels['description'] ), 'sitecore' ),
			'public'                => $this->type_options['public'],
			'hierarchical'          => $this->type_options['hierarchical'],
			'exclude_from_search'   => $this->type_options['exclude_from_search'],
			'publicly_queryable'    => $this->type_options['publicly_queryable'],
			'show_ui'               => $this->type_options['show_ui'],
			'show_in_menu'          => $this->type_options['show_in_menu'],
			'show_in_nav_menus'     => $this->type_options['show_in_nav_menus'],
			'show_in_admin_bar'     => $this->type_options['show_in_admin_bar'],
			'show_in_rest'          => $this->type_options['show_in_rest'],
			'rest_base'             => $this->type_key . '_rest_api',
			'rest_controller_class' => $this->type_options['rest_controller_class'],
			'menu_position'         => $this->type_options['menu_position'],
			'menu_icon'             => $this->type_labels['menu_icon'],
			'capability_type'       => $this->capability_type(),
			'capabilities'          => $this->type_options['capabilities'],
			'map_meta_cap'          => $this->type_options['map_meta_cap'],
			'supports'              => $this->type_options['supports'],
			'register_meta_box_cb'  => $this->type_options['register_meta_box_cb'],
			'taxonomies'            => $this->type_options['taxonomies'],
			'has_archive'           => $this->type_options['has_archive'],
			'rewrite'               => $this->rewrite(),
			'query_var'             => $this->type_key,
			'can_export'            => $this->type_options['can_export'],
			'delete_with_user'      => $this->type_options['delete_with_user'],
			'template'              => $this->template(),
			'template_lock'         => $this->type_options['template_lock'],
			'_builtin'              => $this->type_options['_builtin'],
			'_edit_link'            => 'post.php?post=%d'
		];

		return $options;
	}

	/**
	 * Post type labels
	 *
	 * The `ucwords()` function capitalizes
	 * the string (uppercase words).
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns the array of post type labels.
	 */
	protected function labels() {

		$labels = [
			'name'                  => __( ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'singular_name'         => __( ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'menu_name'             => __( ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'all_items'             => __( 'All ' . ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'add_new'               => __( 'Add New', 'sitecore' ),
			'add_new_item'          => __( 'Add New ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'edit_item'             => __( 'Edit ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'new_item'              => __( 'New ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'view_item'             => __( 'View ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'view_items'            => __( 'View ' . ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'search_items'          => __( 'Search ' . ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'not_found'             => __( 'No ' . ucwords( $this->type_labels['plural'] ) . ' Found', 'sitecore' ),
			'not_found_in_trash'    => __( 'No ' . ucwords( $this->type_labels['plural'] ) . ' Found in Trash', 'sitecore' ),
			'parent_item_colon'     => __( 'Parent ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'featured_image'        => __( 'Featured image for this ' . strtolower( $this->type_labels['singular'] ), 'sitecore' ),
			'set_featured_image'    => __( 'Set featured image for this ' . strtolower( $this->type_labels['singular'] ), 'sitecore' ),
			'remove_featured_image' => __( 'Remove featured image for this ' . strtolower( $this->type_labels['singular'] ), 'sitecore' ),
			'use_featured_image'    => __( 'Use as featured image for this ' . strtolower( $this->type_labels['singular'] ), 'sitecore' ),
			'archives'              => __( ucwords( $this->type_labels['singular'] ) . ' archives', 'sitecore' ),
			'insert_into_item'      => __( 'Insert into ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'uploaded_to_this_item' => __( 'Uploaded to this ' . ucwords( $this->type_labels['singular'] ), 'sitecore' ),
			'filter_items_list'     => __( 'Filter ' . ucwords( $this->type_labels['plural'] ), 'sitecore' ),
			'items_list_navigation' => __( ucwords( $this->type_labels['plural'] ) . ' list navigation', 'sitecore' ),
			'items_list'            => __( ucwords( $this->type_labels['plural'] ) . ' List', 'sitecore' ),
			'attributes'            => __( ucwords( $this->type_labels['singular'] ) . ' Attributes', 'sitecore' )
		];

		// Filter for child classes to modify this array.
		return $labels;
	}

	/**
	 * Capability type
	 *
	 * @link https://wordpress.stackexchange.com/a/108375
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the post type capability.
	 */
	protected function capability_type() {

		/**
		 * Use another post type to inherit its capabilities.
		 * Using this post type defaults to `post` post type
		 * capabilities unless custom capabilities have been
		 * applied. Also, the conditional statement defaults
		 * to `post` in case a different post type capability
		 * is used but that post type does not exists.
		 */
		if ( post_type_exists( $this->type_key ) ) {
			return $this->type_key;
		} else {
			return 'post';
		}
	}

	/**
	 * Use block editor
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $post_type
	 * @return boolean Returns true if the `$use_block_editor` property is true.
	 */
	public function use_block_editor( $post_type ) {

		// Only modify this post type.
		if ( $this->type_key != $post_type ) {
			return true;
		}
		return $this->type_options['use_block_editor'];
	}

	/**
	 * Rewrite rules
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns the array of rewrite rules.
	 */
	protected function rewrite() {

		$rewrite = [
			'slug'       => $this->type_key,
			'with_front' => true,
			'feeds'      => true,
			'pages'      => true
		];

		return $rewrite;
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

		return $args;
	}

	/**
	 * Rewrite post type labels
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns new values for array label keys.
	 */
	public function rewrite_labels() {}

	/**
	 * Template
	 *
	 * Array of blocks to use as the default initial state for an editor session.
	 * Each item should be an array containing block name and optional attributes.
	 *
	 * Only used by WordPress 5.0.0 and greater.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns the array of blocks in the template.
	 */
	protected function template() {
		$template = [];
		return $template;
	}

	/**
	 * Field groups
	 *
	 * Register field groups for this post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function field_groups() {

		/**
		 * Include from another file or use the
		 * `acf_add_local_field_group` function
		 * here, as exported.
		 */
	}
}
