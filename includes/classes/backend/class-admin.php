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
use SiteCore\Classes as Classes,
	SiteCore\Compatibility  as Compat,
	SiteCore\Classes\Vendor as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Admin {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Get the filename of the current page.
		global $pagenow;

		// Admin settings.
		new Admin_Settings_Page;

		if ( Compat\active_acf_pro() ) {
			new Vendor\ACF_Manage_Site;
		} else {
			new Manage_Website_Page;
		}

		// Post edit screens
		new Post_Edit;

		// Run the dashboard only on the backend index screen.
		if ( 'index.php' == $pagenow && ! isset( $_GET['page'] ) ) {
			new Dashboard;
		}

		// Posts list tables.
		new Posts_List_Table;

		// Post type menu options.
		add_filter( 'register_post_type_args', [ $this, 'post_type_menu_options' ], 10, 2 );

		// Enqueue scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Enqueue styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );

		// Remove theme & plugin editor links.
		add_action( 'admin_init', [ $this, 'remove_editor_links' ] );

		// Redirect theme & plugin editor pages.
		add_action( 'admin_init', [ $this, 'redirect_editor_pages' ] );

		// Remove the ClassicPress/WordPress logo from the admin bar.
		add_action( 'admin_bar_menu', [ $this, 'remove_toolbar_logo' ], 999 );

		// Hide the ClassicPress/WordPress update notification to all but admins.
		add_action( 'admin_head', [ $this, 'admin_only_updates' ], 1 );

		// Remove Site Health from menu.
		if ( defined( 'SCP_ALLOW_SITE_HEALTH' ) && ! SCP_ALLOW_SITE_HEALTH ) {
			add_action( 'admin_menu', [ $this, 'menu_remove_site_health' ] );
		}

		// Menus & Widgets admin menu items.
		add_action( 'admin_menu', [ $this, 'menus_widgets' ] );

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
		wp_enqueue_style( SCP_CONFIG['admin_slug'] . '-admin', SCP_URL . 'assets/css/admin.min.css', [], '', 'all' );
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
	 * Menus & Widgets admin menu items
	 *
	 * Removes the Menus & Widgets links as submenu items
	 * of the Appearance link and makes them top-level items.
	 * Navigation and widgets are more content than style so
	 * this is a logical move. Leave the Appearance entry for
	 * headers, background, theme options, customizer, etc.
	 *
	 * This also provides the opportunity to have submenus for each.
	 *
	 * @todo Make this optional via settings, top-level being default.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global array menu The admin menu array.
	 * @global array submenu The admin submenu array.
	 * @return void
	 */
	public function menus_widgets() {

		global $menu, $submenu;

		// Remove Menus and Widgets as submenu items of Appearances.
		if ( isset( $submenu['themes.php'] ) ) {

			// Look for menu items under Appearances.
			foreach ( $submenu['themes.php'] as $key => $item ) {

				// Unset Menus if it is found.
				if ( $item[2] === 'nav-menus.php' ) {
					unset($submenu['themes.php'][$key] );
				}

				// Unset Widgets if it is found.
				if ( $item[2] === 'widgets.php' ) {
					unset( $submenu['themes.php'][$key] );
				}
			}
		}

		// Add a new top-level Menus page.
		add_menu_page(
			__( 'Menus', 'sitecore' ),
			__( 'Menus', 'sitecore' ),
			'delete_others_pages',
			'nav-menus.php',
			'',
			'dashicons-menu-alt',
			61
		);

		// Add a new top-level Widgets page.
		add_menu_page(
			__( 'Widgets', 'sitecore' ),
			__( 'Widgets', 'sitecore' ),
			'delete_others_pages',
			'widgets.php',
			'',
			'dashicons-screenoptions',
			62
		);
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
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description ) {
			$name = sprintf(
				'%s - %s',
				get_bloginfo( 'name' ),
				get_bloginfo( 'description' )
			);
		} else {
			$name = get_bloginfo( 'name' );
		}

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

		echo $name;
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
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description ) {
			$name = sprintf(
				'%s - %s',
				get_bloginfo( 'name' ),
				get_bloginfo( 'description' )
			);
		} else {
			$name = get_bloginfo( 'name' );
		}

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
}
