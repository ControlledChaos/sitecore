<?php
/**
 * Base class to register a post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @access     public
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
	 * Capability type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The capabilitiy type.
	 */
	protected $capability_type = 'post';

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
	protected $map_meta_cap = true;

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
	 * Can_export
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
	 * Builtin
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean True if this post type is a native or "built-in" post_type.
	 */
	protected $_builtin = false;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Register post type.
		add_action( 'init', [ $this, 'register' ] );
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
	 * @access public
	 * @return array Returns the array of post type options,
	 *               including labels from $this->labels().
	 */
	public function options() {

		$options = [
			'label'                 => __( ucwords( $this->plural ), SCP_DOMAIN ),
			'labels'                => $this->labels(),
			'description'           => __( ucfirst( $this->description ), SCP_DOMAIN ),
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
			'capability_type'       => $this->capability_type,
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
	 * @access public
	 * @return array Returns the array of post type labels.
	 */
	public function labels() {

		$labels = [
			'name'                  => __( ucwords( $this->plural ), SCP_DOMAIN ),
			'singular_name'         => __( ucwords( $this->singular ), SCP_DOMAIN ),
			'menu_name'             => __( ucwords( $this->plural ), SCP_DOMAIN ),
			'all_items'             => __( 'All ' . ucwords( $this->plural ), SCP_DOMAIN ),
			'add_new'               => __( 'Add New', SCP_DOMAIN ),
			'add_new_item'          => __( 'Add New ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'edit_item'             => __( 'Edit ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'new_item'              => __( 'New ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'view_item'             => __( 'View ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'view_items'            => __( 'View ' . ucwords( $this->plural ), SCP_DOMAIN ),
			'search_items'          => __( 'Search ' . ucwords( $this->plural ), SCP_DOMAIN ),
			'not_found'             => __( 'No ' . ucwords( $this->plural ) . ' Found', SCP_DOMAIN ),
			'not_found_in_trash'    => __( 'No ' . ucwords( $this->plural ) . ' Found in Trash', SCP_DOMAIN ),
			'parent_item_colon'     => __( 'Parent ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'featured_image'        => __( 'Featured image for this ' . strtolower( $this->singular ), SCP_DOMAIN ),
			'set_featured_image'    => __( 'Set featured image for this ' . strtolower( $this->singular ), SCP_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image for this ' . strtolower( $this->singular ), SCP_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image for this ' . strtolower( $this->singular ), SCP_DOMAIN ),
			'archives'              => __( ucwords( $this->singular ) . ' archives', SCP_DOMAIN ),
			'insert_into_item'      => __( 'Insert into ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'uploaded_to_this_item' => __( 'Uploaded to this ' . ucwords( $this->singular ), SCP_DOMAIN ),
			'filter_items_list'     => __( 'Filter ' . ucwords( $this->plural ), SCP_DOMAIN ),
			'items_list_navigation' => __( ucwords( $this->plural ) . ' list navigation', SCP_DOMAIN ),
			'items_list'            => __( ucwords( $this->plural ) . ' List', SCP_DOMAIN ),
			'attributes'            => __( ucwords( $this->singular ) . ' Attributes', SCP_DOMAIN ),
			'parent_item_colon'     => __( 'Parent ' . ucwords( $this->singular ), SCP_DOMAIN ),
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

		$rewrite = [
			'slug'       => $this->type_key,
			'with_front' => true,
			'feeds'      => true,
			'pages'      => true
		];

		return $rewrite;
	}
}
