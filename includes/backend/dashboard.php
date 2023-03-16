<?php
/**
 * Admin dashboard
 *
 * @package    Site_Core
 * @subpackage Admin
 * @category   Dashboard
 * @since      1.0.0
 */

namespace SiteCore\Admin\Dashboard;

// Alias namespaces.
use SiteCore\Classes as Classes,
	SiteCore\Classes\Users as Users,
	SiteCore\Classes\Vendor as Vendor;

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

	// Enqueue admin scripts.
	add_action( 'admin_enqueue_scripts', $ns( 'admin_enqueue_scripts' ) );

	// Remove widgets.
	add_action( 'wp_dashboard_setup', $ns( 'remove_widgets' ) );

	/**
	 * Custom dashboard panel
	 *
	 * This replaces the core welcome panel which is
	 * limited to admins. With this, a custom welcome
	 * panel can be offered to all user roles.
	 */
	if ( get_option( 'enable_custom_dashboard', false ) ) :

		// Enqueue dashboard panel styles.
		add_action( 'admin_enqueue_scripts', $ns( 'dashboard_panel_styles' ) );

		// Widgets area layout.
		layout();

		// Widget order.
		add_action( 'admin_init', $ns( 'widget_order' ), 25 );

		// Add custom dashboard panel.
		add_action( 'wp_dashboard_setup', $ns( 'dashboard_panel' ) );
	endif;

	add_action( 'dashboard_glance_items', $ns( 'dashboard_glance_items' ) );
}

/**
 * Enqueue page scripts
 *
 * This is for scripts that shall not be
 * overridden by class extension. Specific
 * screens should use enqueue_scripts() to
 * enqueue scripts for its screen.
 *
 * @since  1.0.0
 * @return void
 */
function admin_enqueue_scripts() {}

/**
 * Remove widgets
 *
 * @since  1.0.0
 * @global array wp_meta_boxes The metaboxes array holds all the widgets for wp-admin.
 * @return void
 */
function remove_widgets() {

	global $wp_meta_boxes;

	// WordPress news.
	// unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );

	// ClassicPress petitions.
	// unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_petitions'] );

	// Hide Quick Draft (QuickPress) widget.
	// unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );

	// Hide At a Glance widget.
	// unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );

	// Hide Activity widget.
	// remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );

	// Site Health.
	if ( defined( 'SCP_ALLOW_SITE_HEALTH' ) && ! SCP_ALLOW_SITE_HEALTH ) {
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}

	// PHP update nag.
	unset( $wp_meta_boxes['dashboard']['normal']['high']['dashboard_php_nag'] );

	// Hide forums activity.
	if (
		is_plugin_active( 'bbpress/bbpress.php' ) ||
		is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ||
		is_plugin_active( 'buddyboss-platform-pro/buddyboss-platform-pro.php' )
	) {
		// remove_meta_box( 'bbp-dashboard-right-now', 'dashboard', 'normal' );
	}
}

/**
 * Enqueue admin scripts
 *
 * @since  1.0.0
 * @return void
 */
function dashboard_panel_styles() {

	// Script suffix.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$suffix = '';
	} else {
		$suffix = '.min';
	}

	// Get the screen ID to target the dashboard.
	$screen = get_current_screen();

	// Enqueue only on the Dashboard screen.
	if ( $screen->id == 'dashboard' ) {
		wp_enqueue_style( 'scp-dashboard', SCP_URL .  'assets/css/dashboard-panel' . $suffix . '.css', [], null, 'screen' );

		if ( is_rtl() ) {
			wp_enqueue_style( 'scp-dashboard-rtl', SCP_URL .  'assets/css/dashboard-panel-rtl' . $suffix . '.css', [], null, 'screen' );
		}
	}
}

/**
 * Widgets area layout
 *
 * @since  1.0.0
 * @return void
 */
function layout() {

	// Make dashboard one column because of the big user panel.
	add_filter( 'screen_layout_columns', function( $columns ) {
		$columns['dashboard'] = 1;
		return $columns;
	} );
	add_filter( 'get_user_option_screen_layout_dashboard', function() { return 1; } );
}

/**
 * Widget order
 *
 * @since  1.0.0
 * @return void
 */
function widget_order() {

	$id = get_current_user_id();
	$meta_value = [
		'normal'  => 'scp-dashboard',
		'side'    => '',
		'column3' => '',
		'column4' => '',
	];
	update_user_meta( $id, 'meta-box-order_dashboard', $meta_value );
}

/**
 * Dashboard panel
 *
 * This and some CSS replicates the custom welcome panel.
 * It is used instead because the welcome panel hook is
 * only available to users who can customize the site.
 * With this content can be made available to all users
 * then conditionally displayed by user role.
 *
 * @since  1.0.0
 * @return void
 */
function dashboard_panel() {

	// Remove default widgets.
	global $wp_meta_boxes;
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_petitions'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_action( 'welcome_panel', 'wp_welcome_panel' );

	$heading = sprintf(
		'%s %s',
		get_bloginfo( 'name' ),
		__( 'Dashboard', 'sitecore' )
	);

	wp_add_dashboard_widget(
		'scp-dashboard',
		$heading,
		__NAMESPACE__ . '\dashboard_template',
		null,
		null,
		'normal',
		'high'
	);
}

/**
 * Get the custom dashboard panel
 *
 * @since  1.0.0
 * @return void
 */
function dashboard_template() {

	// Instantiate Plugin_ACF class to get the suffix.
	$acf = new Vendor\Plugin_ACF;

	// Look first in the active theme for a dashboard panel template.
	$dashboard = locate_template( 'template-parts/admin/dashboard-panel' . $acf->suffix() . '.php' );

	if ( ! empty( $dashboard ) ) {
		get_template_part( 'template-parts/admin/dashboard-panel' . $acf->suffix() );
	} else {
		include_once SCP_PATH . 'views/backend/widgets/dashboard-panel' . $acf->suffix() . '.php';
	}
}

/**
 * Dashboard glance items
 *
 * Adds custom post types to "At a Glance" dashboard widget.
 *
 * @since  1.0.0
 * @return void
 */
function dashboard_glance_items() {

	// Post type query arguments.
	$args = [
		'public'   => true,
		'_builtin' => false
	];
	$output     = 'object';
	$operator   = 'and';
	$post_types = get_post_types( $args, $output, $operator );

	// Prepare an entry for each post type matching the query.
	foreach ( $post_types as $post_type ) {

		$count  = wp_count_posts( $post_type->name );
		$number = number_format_i18n( $count->publish );
		$name   = _n( $post_type->labels->menu_name, $post_type->labels->name, intval( $count->publish ) );

		// Supply an edit link if the user can edit posts.
		if ( current_user_can( 'edit_posts' ) ) {
			echo sprintf(
				'<li class="post-count %1s-count"><a href="edit.php?post_type=%2s">%3s %4s</a></li>',
				$post_type->name,
				$post_type->name,
				$number,
				$name
			);

		// Otherwise just the count and post type name.
		} else {
			echo sprintf(
				'<li class="post-count %1s-count">%2s %3s</li>',
				$post_type->name,
				$number,
				$name
			);
		}
	}
}
