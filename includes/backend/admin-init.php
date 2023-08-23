<?php
/**
 * Admin screens
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Admin;

use SiteCore\{
	Classes        as Classes,
	Compatibility  as Compat,
	Classes\Vendor as Vendor
};

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'plugins_loaded', $ns( 'classes' ) );

	// Post edit screens.
	add_action( 'plugins_loaded', function() {
		Post_Edit\setup();
	} );

	// Posts list tables.
	add_action( 'plugins_loaded', function() {
		List_Tables\setup();
	} );

	// Custom dashboard.
	add_action( 'plugins_loaded', function() {
		Dashboard\setup();
	} );

	// Remove theme & plugin editor links.
	add_action( 'admin_init', $ns( 'remove_editor_links' ) );

	// Redirect theme & plugin editor pages.
	add_action( 'admin_init', $ns( 'redirect_editor_pages' ) );

	// Remove the ClassicPress/WordPress logo from the admin bar.
	add_action( 'admin_bar_menu', $ns( 'remove_toolbar_logo' ), 999 );

	// Hide the ClassicPress/WordPress update notification to all but admins.
	add_action( 'admin_head', $ns( 'admin_only_updates' ), 1 );

	// Custom admin menu order.
	add_filter( 'menu_order', $ns( 'menu_order' ), 10, 1 );
	add_filter( 'custom_menu_order', $ns( 'menu_order' ), 10, 1 );

	// Remove Site Health from menu.
	if ( get_option( 'disable_site_health', false ) ) {
		add_action( 'admin_menu', $ns( 'menu_remove_site_health' ) );
	}
	if ( defined( 'SCP_DISABLE_SITE_HEALTH' ) && SCP_DISABLE_SITE_HEALTH ) {
		add_action( 'admin_menu', $ns( 'menu_remove_site_health' ) );
	}

	// Menus & Widgets admin menu items.
	add_action( 'admin_menu', $ns( 'menus_widgets' ), 9 );

	// Remove menu items.
	add_action( 'admin_menu', $ns( 'remove_menu_items' ), 9 );

	// Show hidden screens in menu.
	add_action( 'register_post_type_args', $ns( 'show_in_menu' ), 10, 2 );

	// Admin menu highlighting.
	add_action( 'parent_file', $ns( 'parent_file' ) );

	// Hide help with privacy policy nag.
	add_action( 'admin_head', $ns( 'hide_policy_content_notice' ) );

	// Add admin header.
	if ( get_option( 'enable_custom_admin_header', false ) ) {

		/**
		 * Not hooked to `in_admin_header` because the screen options
		 * and contextual help buttons/sections need to load first.
		 *
		 * Add early to the relevant hook in attempt to display
		 * above any admin notices.
		 */
		if ( is_network_admin() ) {
			$header_hook = 'network_admin_notices';
		} elseif ( is_user_admin() ) {
			$header_hook = 'user_admin_notices';
		} else {
			$header_hook = 'admin_notices';
		}
		add_action( $header_hook, $ns( 'admin_header' ), 1 );
		add_action( 'after_setup_theme', $ns( 'admin_header_menu' ) );
		add_action( 'admin_print_styles', $ns( 'admin_header_styles' ) );
	}

	// Enqueue scripts.
	add_action( 'admin_enqueue_scripts', $ns( 'admin_enqueue_scripts' ) );

	// Enqueue styles.
	add_action( 'admin_enqueue_scripts', $ns( 'admin_enqueue_styles' ) );
}

/**
 * Backend classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {

	if (
		get_option( 'enable_custom_dashboard', false ) &&
		Compat\has_acf()
	) {
		$dashboard_acf = new Classes\Admin\Dashboard_Tabs_ACF;
		$dashboard_acf->add_page();
	}

	if ( get_option( 'enable_sample_files', false ) ) {
		$manage         = new Classes\Admin\Manage_Website_Page;
		$sample         = new Classes\Admin\Sample_Page;
		$sample_sub     = new Classes\Admin\Sample_Subpage;
		$sample_acf     = new Classes\Admin\Sample_ACF_Options;
		$sample_acf_sub = new Classes\Admin\Sample_ACF_Suboptions;

		$manage->add_page();
		$sample->add_page();
		$sample_sub->add_page();
		$sample_acf->add_page();
		$sample_acf_sub->add_page();
	}
}

/**
 * Remove theme & plugin editor links
 *
 * @since  1.0.0
 * @return void
 */
function remove_editor_links() {

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
 * @global object pagenow Gets the current admin screen.
 * @return void
 */
function redirect_editor_pages() {

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
 * @param  object $wp_admin_bar
 * @return void
 */
function remove_toolbar_logo( $wp_admin_bar ) {

	if ( get_option( 'toolbar_remove_platform_link', true ) ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}
}

/**
 * Admin only updates
 *
 * Hides the ClassicPress/WordPress update notification to all but admins.
 *
 * @since  1.0.0
 * @return void
 */
function admin_only_updates() {

	// The `update_core` capability includes admins and super admins.
	if ( ! current_user_can( 'update_core' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}

/**
 * Custom admin menu order
 *
 * Hook `scp_menu_order_after_index`
 * filter to the `admin_menu` action
 * to add items in the top section.
 *
 * @since  1.0.0
 * @param  array $order
 * @return array
 */
function menu_order( $order ) {

	// Variables.
	$order = [];
	$index = [ 'index.php' ];
	$after = apply_filters( 'scp_menu_order_after_index', [] );
	$top   = array_merge( $index, $after );
	$links = '';

	if ( get_option( 'enable_link_manager', false ) ) {
		$links = 'link-manager.php';
	}

	// Add items to follow the dashboard.
	if ( get_option( 'admin_menu_custom_order', true ) ) {
		$custom = [
			'separator1',
			'upload.php',
			'edit.php',
			'edit.php?post_type=page',
			'custom-content',
			$links,
			'edit-comments.php',
			'separator2'
		];
		$order = array_merge( $top, $custom );
	}
	return apply_filters( 'scp_admin_menu_order', $order );
}

/**
 * Remove Site Health from menu
 *
 * A temporary redirect to the dashboard is created.
 *
 * @since  1.0.0
 * @global object pagenow Gets the current admin screen.
 * @return void
 */
function menu_remove_site_health(){

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
 * @since  1.0.0
 * @global array submenu The admin submenu array.
 * @return void
 */
function menus_widgets() {

	global $submenu;

	if ( isset( $submenu['themes.php'] ) ) {

		foreach ( $submenu['themes.php'] as $key => $item ) {

			if ( $item[2] === 'nav-menus.php' && get_option( 'admin_menu_menus_top', true ) ) {
				unset( $submenu['themes.php'][$key] );
			}

			if ( $item[2] === 'widgets.php' && get_option( 'admin_menu_widgets_top', true ) ) {
				unset( $submenu['themes.php'][$key] );
			}
		}
	}

	// Add a new top-level Menus page.
	if ( current_theme_supports( 'menus' ) || current_theme_supports( 'widgets' ) ) {

		if ( get_option( 'admin_menu_menus_top', true ) ) {
			add_menu_page(
				__( 'Navigation Menus', 'sitecore' ),
				__( 'Navigation', 'sitecore' ),
				'delete_others_pages',
				'nav-menus.php',
				'',
				'dashicons-menu-alt',
				61
			);
		}
	}

	// Add a new top-level Widgets page.
	if ( current_theme_supports( 'widgets' ) ) {

		if ( get_option( 'admin_menu_widgets_top', true ) ) {
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
	}
}

/**
 * Remove menu items
 *
 * @since  1.0.0
 * @global object pagenow Gets the current admin screen.
 * @global array submenu The admin submenu array.
 * @return void
 */
function remove_menu_items() {

	// Stop if no content metabox on the ACF content tools screen.
	if (
		! class_exists( 'acf' ) &&
		! class_exists( 'SiteCore\Classes\Tools\Content_Import_Export' )
	) {
		return;
	}

	global $pagenow, $submenu;

	if ( isset( $submenu['tools.php'] ) ) {

		foreach ( $submenu['tools.php'] as $key => $item ) {

			if ( $item[2] === 'import.php' && 'import.php' != $pagenow ) {
				unset( $submenu['tools.php'][$key] );
			}
			if ( $item[2] === 'export.php' && 'export.php' != $pagenow ) {
				unset( $submenu['tools.php'][$key] );
			}
		}
	}
}

/**
 * Show in admin menu
 *
 * @since  1.0.0
 * @param  array $args Array of arguments for registering a post type.
 * @param  string $post_type Post type key.
 * @return array Returns an array of new option arguments.
 */
function show_in_menu( $args, $post_type ) {

	// Determine whether blocks are used.
	$editor_replace     = get_option( 'editor-options-replace' );
	$editor_allow_users = get_option( 'editor-options-allow-users' );
	$show_blocks        = false;

	if ( 'block' == $editor_replace || ( 'tinymce' == $editor_replace && 'allow' == $editor_allow_users ) ) {
		$show_blocks = true;
	}

	// Look for the content settings page and set as a variable.
	$content = get_plugin_page_hookname( 'custom-content', 'custom-content' );

	if ( get_option( 'admin_menu_menus_top', true ) ) {
		$nav_parent = 'nav-menus.php';
	} else {
		$nav_parent = 'themes.php';
	}

	if (
		'wp_block' == $post_type &&
		get_option( 'admin_menu_reuse_blocks', false )
	) {
		if ( $content && $show_blocks ) {
			$args['labels']['name']      = __( 'Reusable Blocks', 'sitecore' );
			$args['labels']['menu_name'] = __( 'Reusable Blocks', 'sitecore' );
			$args['labels']['all_items'] = __( 'Reusable Blocks', 'sitecore' );
			$args['show_in_menu'] = 'custom-content';
		}
	}

	if (
		'wp_navigation' == $post_type &&
		get_option( 'admin_menu_nav_blocks', false )
	) {
		$args['labels']['menu_name'] = __( 'Navigation Blocks', 'sitecore' );
		$args['labels']['all_items'] = __( 'Navigation Blocks', 'sitecore' );
		$args['show_in_menu'] = $nav_parent;
	}
	return $args;
}

/**
 * Admin menu highlighting
 *
 * @since  1.0.0
 * @param string $parent_file
 * @return string Returns the parent file.
 */
function parent_file( $parent_file ) {

	// Access global variables.
	global $pagenow;

	if ( 'widgets.php' == $pagenow ) {
		$parent_file = 'widgets.php';
	} elseif ( 'nav-menus.php' == $pagenow ) {
		$parent_file = 'nav-menus.php';
	}
	return $parent_file;
}

/**
 * Hide help with privacy policy nag
 *
 * @since  1.0.0
 * @global object $post
 * @return void
 */
function hide_policy_content_notice() {

	global $post;

	$current_screen = \get_current_screen();
	$policy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );

	if ( 'post' !== $current_screen->base || $policy_page_id !== $post->ID ) {
		return;
	}

	echo '<style>.wp-pp-notice{ display: none !important; }</style>';
}

/**
 * Admin header menu
 *
 * @since  1.0.0
 * @return void
 */
function admin_header_menu() {
	register_nav_menus( [
		'admin_header' => __( 'Admin Header Menu', 'sitecore' )
	] );
}

/**
 * Admin header
 *
 * @since  1.0.0
 * @return void
 */
function admin_header() {
	include_once SCP_PATH . 'views/backend/header/admin-header.php';
}

/**
 * Admin header CSS
 *
 * @since  1.0.0
 * @return void
 */
function admin_header_styles() {

?>
<style>
.admin-header {
	margin: 2rem 20px 0 0;
}
.rtl .admin-header {
	margin: 2rem 0 0 20px;
}
.admin-header .site-branding-wrap {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 1rem 2rem;
}
@media screen and ( max-width: 782px ) {
	.admin-header .site-branding-wrap {
		flex-direction: column;
		align-items: flex-start;
	}
}
.admin-header .site-branding-wrap > div,
.admin-header .site-branding-wrap > nav {
	width: 100%;
}
.admin-header .site-branding {
	display: flex;
	flex-wrap: nowrap;
	align-items: center;
	gap: 1rem;
}
.admin-header .site-title {
	font-size: 23px;
	font-weight: 400;
	margin: 0;
	line-height: 1.3;
	color: #1d2327;
}
.admin-header .site-title a {
	text-decoration: none;
	color: #1d2327;
}
.admin-header .site-description {
	margin: 0;
	line-height: 1.3;
}
.admin-header .site-logo {
	margin: 0;
}
.admin-header .site-logo a {
	display: block;
}
.admin-header .site-logo img {
	display: block;
	max-height: 60px;
}
.admin-header .menu {
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-end;
	gap: 0.625em 1em;
	margin: 0;
	padding: 0;
	list-style: none;
}
@media screen and ( max-width: 782px ) {
	.admin-header .menu {
		justify-content: flex-start;
	}
}
.admin-header .menu li {
	display: inline;
}
</style>
<?php
}

/**
 * Enqueue backend JavaScript
 *
 * @since  1.0.0
 * @return void
 */
function admin_enqueue_scripts() {

	// Script suffix.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$suffix = '';
	} else {
		$suffix = '.min';
	}

	/**
	 * Enqueue plugin tabs system
	 *
	 * The tabs script is required for the content tabs added by
	 * child classes of the `Add_Page` class. This creates tabbed
	 * content on admin pages, settings pages, & the dashboard.
	 */
	wp_enqueue_script( 'scp-tabs', SCP_URL . 'assets/js/admin-tabs' . $suffix . '.js', [ 'jquery' ], '', true );
}

/**
 * Enqueue the stylesheets for the admin area.
 *
 * Uses the universal slug partial for admin pages. Set this
 * slug in the core plugin file.
 *
 * @since  1.0.0
 * @return void
 */
function admin_enqueue_styles() {

	// Script suffix.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$suffix = '';
	} else {
		$suffix = '.min';
	}

	/**
	 * Enqueue general backend styles
	 *
	 * Included are just a few style rules for features added by this plugin.
	 *
	 * @since 1.0.0
	 */
	wp_enqueue_style( 'scp-admin', SCP_URL . 'assets/css/admin' . $suffix . '.css', [], '', 'all' );

	/**
	 * Enqueue admin tabs styles
	 *
	 * The tabs stylesheet is required for the content tabs added by
	 * child classes of the `Add_Page` class. This creates tabbed
	 * content on admin pages, settings pages, & the dashboard.
	 */
	wp_enqueue_style( 'scp-tabs', SCP_URL . 'assets/css/admin-tabs' . $suffix . '.css', [], '', 'all' );
}
