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
 * @see includes/classes/class-register-sample.php
 */

declare( strict_types = 1 );
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
	 * @access protected
	 * @var    string The database name of the post type.
	 */
	protected $type_key = '';

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the post type.
	 */
	protected $singular = '';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the post type.
	 */
	protected $plural = '';

	/**
	 * Description
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The post type description.
	 */
	protected $description = '';

	/**
	 * Public type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type is public.
	 */
	protected $public = true;

	/**
	 * Hierarchical
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type is hierarchical.
	 */
	protected $hierarchical = false;

	/**
	 * Exclude from search
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type should be
	 *                 excluded from search.
	 */
	protected $exclude_from_search = false;

	/**
	 * Publicly queryable
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type is
	 *                 publicly queryable.
	 */
	protected $publicly_queryable = true;

	/**
	 * Show user interface
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type displays
	 *                 a user interface.
	 */
	protected $show_ui = true;

	/**
	 * Show in admin menu
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type displays
	 *                 links in the admin menu.
	 */
	protected $show_in_menu = true;

	/**
	 * Show in navigation menus
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type displays
	 *                 in the navigation menus interface.
	 */
	protected $show_in_nav_menus = true;

	/**
	 * Show in admin/user toolbar
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type displays
	 *                 links in the admin/user toolbar.
	 */
	protected $show_in_admin_bar = true;

	/**
	 * Show in REST API
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to show in REST API.
	 */
	protected $show_in_rest = false;

	/**
	 * REST controller class
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string REST API Controller class name.
	 */
	protected $rest_controller_class = 'WP_REST_Posts_Controller';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set position.
	 */
	protected $menu_position = 5;

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The URL to the icon to be used in the menu.
	 *                * Pass a base64-encoded SVG using a data URI,
	 *                  which will be colored to match the color scheme.
	 *                  This should begin with 'data:image/svg+xml;base64,'.
	 *                * Pass the name of a Dashicons helper class to use
	 *                  a font icon, e.g. 'dashicons-chart-pie'.
	 *                * Pass 'none' to leave div.wp-menu-image empty so
	 *                  an icon can be added via CSS.
	 *
	 *                Defaults to use the posts icon.
	 */
	protected $menu_icon = 'dashicons-admin-post';

	/**
	 * Capabilities
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of capabilities.
	 */
	protected $capabilities = [];

	/**
	 * Map meta cap
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to use the internal default
	 *                 meta capability handling.
	 */
	protected $map_meta_cap = null;

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
	];

	/**
	 * Register meta box callback
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var string Provide a callback function that sets up
	 *             the meta boxes for the edit form.
	 *             Do remove_meta_box() and add_meta_box()
	 *             calls in the callback.
	 */
	protected $register_meta_box_cb = null;

	/**
	 * Supported taxonomies
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of supported taxonomies.
	 */
	protected $taxonomies = [
		'category',
		'post_tag'
	];

	/**
	 * Has archive
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether there should be post type archives,
	 *                 or if a string, the archive slug to use.
	 *                 Will generate the proper rewrite rules if
	 *                 $rewrite is enabled.
	 */
	protected $has_archive = true;

	/**
	 * Can export
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to allow this post type to be exported.
	 */
	protected $can_export = true;

	/**
	 * Delete with user
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to delete posts of this type when deleting a user.
	 *                 If true, posts of this type belonging to the user will be
	 *                 moved to Trash when then user is deleted. If false, posts
	 *                 of this type belonging to the user will *not* be trashed
	 *                 or deleted. If not set (the default), posts are trashed
	 *                 if post_type_supports('author'). Otherwise posts are not
	 *                 trashed or deleted.
	 */
	protected $delete_with_user = null;

	/**
	 * Template lock
	 *
	 * Whether the block template should be locked if $template is set.
	 *
	 * Only used by WordPress 5.0.0 and greater.
	 *
	 * If set to 'all', the user is unable to insert new blocks,
	 * move existing blocks and delete blocks.
	 *
	 * If set to 'insert', the user is able to move existing blocks but
	 * is unable to insert new blocks and delete blocks.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    mixed Returns the lock type or false for no lock.
	 */
	protected $template_lock = false;

	/**
	 * Builtin
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean True if this post type is a native or "built-in" post_type.
	 */
	protected $_builtin = false;

	/**
	 * Settings page
	 *
	 * Add a settings page for the post type.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether to create a settings page for this post type.
	 */
	protected $settings_page = false;

	/**
	 * Register priority
	 *
	 * When to register the post type.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set hook priority.
	 */
	protected $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return self
	 */
	protected function __construct() {

		// Register post type.
		add_action( 'init', [ $this, 'register' ], $this->priority );

		// New post type options.
		add_filter( 'register_post_type_args', [ $this, 'post_type_options' ], 10, 2 );

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
			'label'                 => __( ucwords( $this->plural ), 'sitecore' ),
			'labels'                => $this->labels(),
			'description'           => __( ucfirst( $this->description ), 'sitecore' ),
			'public'                => $this->public,
			'hierarchical'          => $this->hierarchical,
			'exclude_from_search'   => $this->exclude_from_search,
			'publicly_queryable'    => $this->publicly_queryable,
			'show_ui'               => $this->show_ui,
			'show_in_menu'          => $this->show_in_menu,
			'show_in_nav_menus'     => $this->show_in_nav_menus,
			'show_in_admin_bar'     => $this->show_in_admin_bar,
			'show_in_rest'          => $this->show_in_rest,
			'rest_base'             => $this->type_key . '_rest_api',
			'rest_controller_class' => $this->rest_controller_class,
			'menu_position'         => $this->menu_position,
			'menu_icon'             => $this->menu_icon,
			'capability_type'       => $this->capability_type(),
			'capabilities'          => $this->capabilities,
			'map_meta_cap'          => $this->map_meta_cap,
			'supports'              => $this->supports,
			'register_meta_box_cb'  => $this->register_meta_box_cb,
			'taxonomies'            => $this->taxonomies,
			'has_archive'           => $this->has_archive,
			'rewrite'               => $this->rewrite(),
			'query_var'             => $this->type_key,
			'can_export'            => $this->can_export,
			'delete_with_user'      => $this->delete_with_user,
			'template'              => $this->template(),
			'template_lock'         => $this->template_lock,
			'_builtin'              => $this->_builtin,
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
			'name'                  => __( ucwords( $this->plural ), 'sitecore' ),
			'singular_name'         => __( ucwords( $this->singular ), 'sitecore' ),
			'menu_name'             => __( ucwords( $this->plural ), 'sitecore' ),
			'all_items'             => __( 'All ' . ucwords( $this->plural ), 'sitecore' ),
			'add_new'               => __( 'Add New', 'sitecore' ),
			'add_new_item'          => __( 'Add New ' . ucwords( $this->singular ), 'sitecore' ),
			'edit_item'             => __( 'Edit ' . ucwords( $this->singular ), 'sitecore' ),
			'new_item'              => __( 'New ' . ucwords( $this->singular ), 'sitecore' ),
			'view_item'             => __( 'View ' . ucwords( $this->singular ), 'sitecore' ),
			'view_items'            => __( 'View ' . ucwords( $this->plural ), 'sitecore' ),
			'search_items'          => __( 'Search ' . ucwords( $this->plural ), 'sitecore' ),
			'not_found'             => __( 'No ' . ucwords( $this->plural ) . ' Found', 'sitecore' ),
			'not_found_in_trash'    => __( 'No ' . ucwords( $this->plural ) . ' Found in Trash', 'sitecore' ),
			'parent_item_colon'     => __( 'Parent ' . ucwords( $this->singular ), 'sitecore' ),
			'featured_image'        => __( 'Featured image for this ' . strtolower( $this->singular ), 'sitecore' ),
			'set_featured_image'    => __( 'Set featured image for this ' . strtolower( $this->singular ), 'sitecore' ),
			'remove_featured_image' => __( 'Remove featured image for this ' . strtolower( $this->singular ), 'sitecore' ),
			'use_featured_image'    => __( 'Use as featured image for this ' . strtolower( $this->singular ), 'sitecore' ),
			'archives'              => __( ucwords( $this->singular ) . ' archives', 'sitecore' ),
			'insert_into_item'      => __( 'Insert into ' . ucwords( $this->singular ), 'sitecore' ),
			'uploaded_to_this_item' => __( 'Uploaded to this ' . ucwords( $this->singular ), 'sitecore' ),
			'filter_items_list'     => __( 'Filter ' . ucwords( $this->plural ), 'sitecore' ),
			'items_list_navigation' => __( ucwords( $this->plural ) . ' list navigation', 'sitecore' ),
			'items_list'            => __( ucwords( $this->plural ) . ' List', 'sitecore' ),
			'attributes'            => __( ucwords( $this->singular ) . ' Attributes', 'sitecore' ),
			'parent_item_colon'     => __( 'Parent ' . ucwords( $this->singular ), 'sitecore' ),
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
