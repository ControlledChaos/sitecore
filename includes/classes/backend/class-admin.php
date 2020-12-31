<?php
/**
 * Admin class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Admin extends Classes\Base {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		parent :: __construct();

		// Get the filename of the current page.
		global $pagenow;

		// Manage website page/help pages.
		new Manage_Website_Page;

		// Run the dashboard only on the backend index screen.
		if ( 'index.php' == $pagenow ) {
			new Dashboard;
		}

		// Post type menu options.
		add_filter( 'register_post_type_args', [ $this, 'post_type_menu_options' ], 10, 2 );

		// Remove theme & plugin editor links.
		add_action( 'admin_init', [ $this, 'remove_editor_links' ] );

		// Redirect theme & plugin editor pages.
		add_action( 'admin_init', [ $this, 'redirect_editor_pages' ] );

		// Remove the ClassicPress/WordPress logo from the admin bar.
		add_action( 'admin_bar_menu', [ $this, 'remove_toolbar_logo' ], 999 );

		// Hide the ClassicPress/WordPress update notification to all but admins.
		add_action( 'admin_head', [ $this, 'admin_only_updates' ], 1 );

		// Remove Site Health from menu.
		add_action( 'admin_menu', [ $this, 'menu_remove_site_health' ] );

		// Add featured image to admin post columns.
		add_filter( 'manage_posts_columns', [ $this, 'image_column_head' ] );
		add_filter( 'manage_pages_columns', [ $this, 'image_column_head' ] );
		add_action( 'manage_posts_custom_column', [ $this, 'image_column_content' ], 10, 2 );
		add_action( 'manage_pages_custom_column', [ $this, 'image_column_content' ], 10, 2 );

		// Primary footer text.
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_primary' ], 1 );

		// Secondary footer text.
		add_filter( 'update_footer', [ $this, 'admin_footer_secondary' ], 1 );
	}

	/**
	 * Post type menu options
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Array of arguments for registering a post type.
	 * @param  string $post_type Post type key.
	 * @return array Returns an array of new option arguments.
	 */
	public function post_type_menu_options( $args, $post_type ) {

		// ACFE dynamic options page post type.
		if ( 'acfe-dop' == $post_type ) {
			$args['show_in_menu'] = 'options-general.php';
			return $args;
		}
		return $args;
	}

	/**
	 * Enqueue backend JavaScript
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Enqueue the stylesheets for the admin area.
	 *
	 * Uses the universal slug partial for admin pages. Set this
     * slug in the core plugin file.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_styles() {

		/**
		 * Enqueue the general backend styles.
		 *
		 * Included are just a few style rules for features added by this plugin.
		 *
		 * @since 1.0.0
		 */
		wp_enqueue_style( SCP_ADMIN_SLUG . '-admin', SCP_URL . 'assets/css/admin.min.css', [], '', 'all' );
	}

	/**
	 * Remove theme & plugin editor links
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function remove_editor_links() {

		// Do not remove for Developer user role.
		if ( ! current_user_can( 'develop' ) ) {
			remove_submenu_page( 'themes.php', 'theme-editor.php' );
			remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
		}
	}

	/**
	 * Redirect theme & plugin editor pages
	 *
	 * A temporary redirect to the dashboard is created.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object pagenow Gets the current admin screen.
	 * @return void
	 */
	public function redirect_editor_pages() {

		// Do not redirect for Developer user role.
		if ( current_user_can( 'develop' ) ) {
			return;
		}

		global $pagenow;

		// Redirect if user is on the theme or plugin editor page.
		if ( $pagenow == 'plugin-editor.php' || $pagenow == 'theme-editor.php' ) {
			wp_redirect( admin_url( '/', 'http' ), 302 );
			exit;
		}
	}

	/**
	 * Remove toolbar logos
	 *
	 * Removes the ClassicPress/WordPress logo from the admin bar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $wp_admin_bar
	 * @return void
	 *
	 * @todo Make this optional on the Site Settings screen.
	 */
	public function remove_toolbar_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}

	/**
	 * Admin only updates
	 *
	 * Hides the ClassicPress/WordPress update notification to all but admins.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @todo Make this optional on the Site Settings screen.
	 */
	public function admin_only_updates() {

		// The `update_core` capability includes admins and super admins.
		if ( ! current_user_can( 'update_core' ) ) {
			remove_action( 'admin_notices', 'update_nag', 3 );
		}
	}

	/**
	 * Remove Site Health from menu
	 *
	 * A temporary redirect to the dashboard is created.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object pagenow Gets the current admin screen.
	 * @return void
	 *
	 * @todo Make this optional on the Site Settings screen.
	 */
	public function menu_remove_site_health(){

		global $pagenow;

		// Remove the menu entry.
		remove_submenu_page( 'tools.php','site-health.php' );

		// Redirect if user is on the Site Health page, `wp-admin/site-health.php`.
		if ( $pagenow == 'site-health.php' ) {
			wp_redirect( admin_url( '/', 'http' ), 302 );
			exit;
		}
	}

	/**
	 * Admin footer primary
	 *
	 * Replaces the "Thank you for creating with ClassicPress/WordPress" text
	 * in the #wpfooter div at the bottom of all admin screens. This replaces
	 * text inside the default paragraph (<p>) tags.
	 *
	 * Several options are provided. Edit or delete as desired.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the footer.
	 */
	public function admin_footer_primary() {

		// Plugin credit option.
		$plugin = sprintf(
			'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a> %s',
			get_bloginfo( 'name' ),
			esc_html__( 'is managed by the' ),
			esc_url( SCP_PLUGIN_URL ),
			esc_html( SCP_NAME ),
			esc_html__( 'plugin' )
		);

		// Site name & description option.
		$name_desc = sprintf(
			'%s - %s',
			get_bloginfo( 'name' ),
			get_bloginfo( 'description' )
		);

		// Developer website option.
		$dev_url = sprintf(
			'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a>',
			get_bloginfo( 'name' ),
			esc_html__( 'website was designed & developed by' ),
			esc_url( SCP_DEV_URL ),
			esc_html( SCP_DEV_NAME )
		);

		// Developer email option.
		$dev_email = sprintf(
			'%s %s %s <a href="mailto:%s">%s</a>',
			esc_html__( 'Contact' ),
			esc_html( SCP_DEV_NAME ),
			esc_html__( 'for website assistance:' ),
			esc_html( SCP_DEV_EMAIL ),
			esc_html( SCP_DEV_EMAIL )
		);

		echo $name_desc;
	}

	/**
	 * Admin footer secondary
	 *
	 * Several options are provided. Edit or delete as desired.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the footer.
	 */
	public function admin_footer_secondary() {

		remove_filter( 'update_footer', 'core_update_footer' );

		// Plugin credit option.
		$plugin = sprintf(
			'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a> %s',
			get_bloginfo( 'name' ),
			esc_html__( 'is managed by the' ),
			esc_url( SCP_PLUGIN_URL ),
			esc_html( SCP_NAME ),
			esc_html__( 'plugin' )
		);

		// Site name & description option.
		$name_desc = sprintf(
			'%s - %s',
			get_bloginfo( 'name' ),
			get_bloginfo( 'description' )
		);

		// Developer website option.
		$dev_url = sprintf(
			'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a>',
			get_bloginfo( 'name' ),
			esc_html__( 'website was designed & developed by' ),
			esc_url( SCP_DEV_URL ),
			esc_html( SCP_DEV_NAME )
		);

		// Developer email option.
		$dev_email = sprintf(
			'%s %s %s <a href="mailto:%s">%s</a>',
			esc_html__( 'Contact' ),
			esc_html( SCP_DEV_NAME ),
			esc_html__( 'for website assistance:' ),
			esc_html( SCP_DEV_EMAIL ),
			esc_html( SCP_DEV_EMAIL )
		);

		echo $dev_email;
	}

	/**
	 * Get column image
	 *
	 * Gets post thumbnail for use in admin columns.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param int $post_ID Returns the post ID.
	 * @return string Returns the path to the featured image.
	 */

	// Get featured image.
	private function get_column_image( $post_ID ) {

		// Get the post thumbnail ID as a variable.
		$post_thumbnail_id = get_post_thumbnail_id( $post_ID );

		/**
		 * Column thumbnail size.
		 *
		 * @see includes/classes/media/class-media.php
		 */
		$size  = 'column-thumbnail';

		// Apply a filter for conditional modification.
		$thumb = apply_filters( 'scp_column_thumbnail_size', $size );

		// If there is an ID (if the post has a featured image).
		if ( $post_thumbnail_id ) {

			// Get the src for the Thumbnail size.
			$post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, $thumb );

			// Return the image src for use below.
			return $post_thumbnail_img[0];
		}
	}

	/**
	 * Image column head
	 *
	 * Adds a new post admin column for the featured image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $defaults Gets the array of default admin columns.
	 * @return string Returns the name of the new column head.
	 */
	public function image_column_head( $defaults ) {

		// The column heading name.
		$name    = __( 'Featured Image', SCP_DOMAIN );

		// Apply a filter for conditional modification.
		$heading = apply_filters( 'scp_image_column_head', $name );

		// The column heading name to new `featured_image` column.
		$defaults['featured_image'] = esc_html__( $heading );

		// Return the heading name.
		return $defaults;
	}

	/**
	 * Image column content
	 *
	 * Adds the featured image to post admin columns.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $column_name
	 * @param  int $post_ID
	 * @return string Returns the image tag for the featured image.
	 */
	public function image_column_content( $column_name, $post_ID ) {

		// If the column is the `featured_image` column established above.
		if ( 'featured_image' == $column_name ) {

			// Get the image src established above.
			$post_featured_image = $this->get_column_image( $post_ID );

			/**
			 * The image tag to be added to the column/post row.
			 *
			 * The tag uses a style attribute for the width, and no width
			 * or height attributes are used, because the image size may
			 * be filtered externally to use a different aspect ratio.
			 */

			// If the post has a featured image.
			if ( $post_featured_image ) {
				echo '<img src="' . esc_url( $post_featured_image ) . '" alt="' . get_the_title( $post_ID ) . __( ' — featured image', SCP_DOMAIN ) . '" width="48px" height="48px" />';

			// If the post doen't have a featured image then use the fallback image.
			} else {
				echo '<img src="' . esc_url( SCP_URL . 'assets/images/featured-image-placeholder.png' ) . '" alt="' . __( 'No featured image available', SCP_DOMAIN ) . '" width="48px" height="48px" />';
			}
		}
	}
}
