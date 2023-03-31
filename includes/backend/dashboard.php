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
 * @global $pagenow Access the current admin page.
 * @return void
 */
function setup() {

	// Stop here if not on Dashboard screen.
	global $pagenow;
	if ( 'index.php' != $pagenow ) {
		return;
	}

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
	if ( use_custom() ) :

		// Enqueue dashboard panel styles.
		add_action( 'admin_enqueue_scripts', $ns( 'dashboard_panel_styles' ) );

		// Print dashboard panel styles.
		add_action( 'admin_print_styles', $ns( 'print_content_summary_styles' ), 20 );

		// Widgets area layout.
		custom_dashboard_layout();

		// Widget order.
		add_action( 'admin_init', $ns( 'widget_order' ), 25 );

		// Add custom dashboard panel.
		add_action( 'wp_dashboard_setup', $ns( 'dashboard_panel' ) );

		// Remove screen options.
		add_filter( 'screen_options_show_screen', '__return_false' );
	endif;

	// Add custom post types to "At a Glance".
	add_action( 'dashboard_glance_items', $ns( 'dashboard_glance_items' ) );

	// Remove contextual help items.
	add_action( 'admin_head', $ns( 'remove_help_items' ) );
}

/**
 * Remove contextual help items
 *
 * @since  1.0.0
 * @global $pagenow
 * @return void
 */
function remove_help_items() {
	$screen = get_current_screen();
	$screen->remove_help_tab( 'help-navigation' );
	$screen->remove_help_tab( 'help-layout' );
	$screen->remove_help_tab( 'help-content' );
	$screen->set_help_sidebar( null );
}

/**
 * Use custom dashboard
 *
 * @since  1.0.0
 * @return void
 */
function use_custom() {

	if ( defined( 'SCP_USE_CUSTOM_DASHBOARD' ) && ! SCP_USE_CUSTOM_DASHBOARD ) {
		return false;
	} elseif ( get_option( 'enable_custom_dashboard', false ) ) {
		return true;
	}
	return false;
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
	if ( get_option( 'disable_site_health', false ) ) {
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}
	if ( defined( 'SCP_DISABLE_SITE_HEALTH' ) && SCP_DISABLE_SITE_HEALTH ) {
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
function custom_dashboard_layout() {

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

/**
 * Custom post types query
 *
 * The custom post types are here as a separate query
 * for use in the At a Glance widget in which the default,
 * built-in post types already exists and custom post types
 * are added by filter.
 *
 * @since  1.0.0
 * @return array Returns an array of queried post types.
 */
function custom_post_types_query() {

	// Array of post type query arguments.
	$query = [
		'public'   => true,
		'_builtin' => false
	];

	// Return post types according to above.
	$query = get_post_types( $query, 'names', 'and' );

	// Return the custom post types.
	return apply_filters( 'scp_custom_post_types_query', $query );
}

/**
 * Public post types
 *
 * Merges built-in post types and custom post types.
 *
 * @since  1.0.0
 * @return array Returns an array of all public post types.
 */
function public_post_types_query() {

	// Add attachment post type.
	$builtin = [ 'post', 'page', 'attachment' ];

	// Custom post types query.
	$custom = custom_post_types_query();

	// Merge the post type arrays.
	$query = array_merge( $builtin, $custom );

	// Return the public post types.
	return apply_filters( 'scp_public_post_types_query', $query );
}

/**
 * Taxonomies query
 *
 * @since  1.0.0
 * @return array Returns an array of queried taxonomies.
 */
function taxonomies_query() {

	// Taxonomy query arguments.
	$query = [
		'public'  => true,
		'show_ui' => true
	];

	// Get taxonomies according to above array.
	$query = get_taxonomies( $query, 'object', 'and' );

	// Return the array of taxonomies. Apply filter for customization.
	return apply_filters( 'scp_taxonomies_query', $query );
}

/**
 * Post types list
 *
 * @since  1.0.0
 * @return string Returns unordered list markup.
 */
function post_types_list() {

	// Get all public post types.
	$post_types = public_post_types_query();

	// Begin the post types list.
	$html = '<ul class="scp-content-list scp-post-types-list">';

	// Conditional list items.
	foreach ( $post_types as $post_type ) {

		$type = get_post_type_object( $post_type );

		// Count the number of posts.
		$get_count = wp_count_posts( $type->name );
		if ( 'attachment' === $post_type ) {
			$count = $get_count->inherit;
		} else {
			$count = $get_count->publish;
		}

		// Get the number of published posts.
		$number = number_format_i18n( $count );

		// Get the plural or single name based on the count.
		$name = _n( $type->labels->singular_name, $type->labels->name, intval( $count ), 'dashboard-summary' );

		// If the icon is data:image/svg+xml.
		if ( 0 === strpos( $type->menu_icon, 'data:image/svg+xml;base64,' ) ) {
			$menu_icon = sprintf(
				'<icon class="scp-cpt-icons" style="%s"></icon>',
				esc_attr( 'background-image: url( "' . esc_html( $type->menu_icon ) . '" );' )
			);

		// If the icon is a Dashicon class.
		} elseif ( 0 === strpos( $type->menu_icon, 'dashicons-' ) ) {
			$menu_icon = '<icon class="dashicons ' . $type->menu_icon . '"></icon>';

		// If the icon is a URL.
		} elseif( 0 === strpos( $type->menu_icon, 'http' ) ) {
			$menu_icon = '<icon class="scp-cpt-icons"><img src="' . esc_url( $type->menu_icon ) . '" /></icon>';

		// Fall back to the default post icon.
		} else {
			$menu_icon = '<icon class="dashicons dashicons-admin-post dashicons-admin-' . $type->menu_icon . '"></icon>';
		}

		// Supply an edit link if media & the user can access the media library.
		if ( 'attachment' === $post_type && current_user_can( 'upload_files' ) ) {
			$html .= sprintf(
				'<li class="post-count %s-count"><a href="edit.php?post_type=%s">%s %s %s</a></li>',
				$type->name,
				$type->name,
				$menu_icon,
				$number,
				$name
			);

		// Supply an edit link if not media & the user can edit posts.
		} elseif ( 'attachment' != $post_type && current_user_can( $type->cap->edit_posts ) ) {
			$html .= sprintf(
				'<li class="post-count %s-count"><a href="edit.php?post_type=%s">%s %s %s</a></li>',
				$type->name,
				$type->name,
				$menu_icon,
				$number,
				$name
			);

		// Otherwise just the count and post type name.
		} else {
			$html .= sprintf(
				'<li class="post-count %s-count">%s %s %s</li>',
				$type->name,
				$menu_icon,
				$number,
				$name
			);

		}
	}

	// End the post types list.
	$html .= '</ul>';

	// Print the list markup.
	echo $html;
}

/**
 * Taxonomies list with icons
 *
 * Includes icon elements rather than adding
 * icons via CSS.
 *
 * @since  1.0.0
 * @return string Returns unordered list markup.
 */
function taxonomies_list() {

	// Get taxonomies.
	$taxonomies = taxonomies_query();

	// Prepare an entry for each taxonomy matching the query.
	if ( $taxonomies ) {

		// Begin the taxonomies icons list.
		$html = '<ul class="scp-content-list scp-taxonomies-list">';

		foreach ( $taxonomies as $taxonomy ) {

			// Get the first supported post type in the array.
			if ( ! empty( $taxonomy->object_type ) ) {
				$types = $taxonomy->object_type[0];
			} else {
				$types = null;
			}

			// Set `post_type` URL parameter for menu highlighting.
			if ( $types && 'post' === $types ) {
				$type = '&post_type=post';
			} elseif ( $types ) {
				$type = '&post_type=' . $types;
			} else {
				$type = '';
			}

			// Count the terms in the taxonomy.
			$count = wp_count_terms( $taxonomy->name );

			// Get the plural or singular name based on the count.
			$name = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name, intval( $count ), 'dashboard-summary' );

			// Conditional icon markup.
			$icon = '';
			if ( 'post_tag' == $taxonomy->name ) {
				$icon = sprintf(
					'<icon class="dashicons dashicons-tag scp-icon-%s"></icon>',
					$taxonomy->name
				);
			} elseif ( 'media_type' == $taxonomy->name ) {
				$icon = sprintf(
					'<icon class="dashicons dashicons-portfolio scp-icon-%s"></icon>',
					$taxonomy->name
				);
			} else {
				$icon = sprintf(
					'<icon class="dashicons dashicons-category scp-icon-%s"></icon>',
					$taxonomy->name
				);
			}

			// Supply an edit link if the user can edit the taxonomy.
			$edit = get_taxonomy( $taxonomy->name );
			if ( current_user_can( $edit->cap->edit_terms ) ) {

				// Print a list item for the taxonomy.
				$html .= sprintf(
					'<li class="at-glance-taxonomy %s"><a href="%s">%s %s %s</a></li>',
					$taxonomy->name,
					esc_url( admin_url( 'edit-tags.php?taxonomy=' . $taxonomy->name . $type ) ),
					$icon,
					$count,
					$name
				);

			// List item without link.
			} else {
				// Print a list item for the taxonomy.
				$html .= sprintf(
					'<li class="at-glance-taxonomy %s">%s %s %s</li>',
					$taxonomy->name,
					$icon,
					$count,
					$name
				);
			}
		}

		// End the taxonomies icons list.
		$html .= '</ul>';

		// Print the list markup.
		echo $html;
	}
}

/**
 * Print admin styles
 *
 * Needed to override the default CSS pseudoelement icon on
 * custom post types and for post type icons that are
 * base64/SVG or <img> element.
 * Also, icons colored with current link color.
 *
 * @since  1.0.0
 * @param  string $style Default empty string.
 * @return string Returns the style blocks.
 */
function print_content_summary_styles( $style = '' ) {

	// Get post types.
	$post_types = public_post_types_query();

	// Prepare styles for each post type matching the query.
	$type_count = '';
	foreach ( $post_types as $post_type ) {

		$type = get_post_type_object( $post_type );
		$type_count .= sprintf(
			'#dashboard_right_now .post-count.%s a:before, #dashboard_right_now .post-count.%s span:before { display: none; }',
			$type->name . '-count',
			$type->name . '-count'
		);
	}

	$style  = '<style>';
	$style .= '#dashboard_right_now li a:before, #dashboard_right_now li span:before { color: currentColor; } ';
	$style .= '.at-glance-cpt-icons { display: inline-block; width: 20px; height: 20px; vertical-align: middle; background-repeat: no-repeat; background-position: center; background-size: 20px auto; } ';
	$style .= '.at-glance-cpt-icons img { display: inline-block; max-width: 20px; } ';
	$style .= $type_count;
	$style .= '#dashboard_right_now li.at-glance-taxonomy a:before, #dashboard_right_now li.at-glance-taxonomy > span:before { display: none; }';
	$style .= '#dashboard_right_now .post-count.attachment-count a::before, #dashboard_right_now .post-count.attachment-count span::before { display: none; }';
	$style .= '#dashboard_right_now li.at-glance-user-count a:before, #dashboard_right_now li.at-glance-user-count span:before { content: "\f110"; }';
	$style .= '#dashboard_right_now li.at-glance-users-count a:before, #dashboard_right_now li.at-glance-users-count span:before { content: "\f307"; }';
	$style .= '#dashboard_right_now .scp-widget-divided-section { margin-top: 1em; padding-top: 0.5em; border-top: solid 1px #ccd0d4; }';
	$style .= '#dashboard_right_now #wp-version-message { display: none; }';
	$style .= '#dashboard-widgets #dashboard_right_now .scp-widget-divided-section h4 { margin: 0.75em 0 0; font-size: 1em; font-weight: bold; font-weight: 600; }';
	$style .= '#dashboard-widgets #dashboard_right_now .scp-widget-divided-section p.description { margin: 0.75em 0 0; font-style: italic; line-height: 1.3; }';
	$style .= '#dashboard-widgets #dashboard_right_now .scp-widget-divided-section a:not(.scp-search-engines) { text-decoration: none; }';
	$style .= '#dashboard_right_now ul.scp-widget-system-list { display: block; margin: 0.75em 0 0; }';
	$style .= '#dashboard_right_now .scp-widget-system-list li { margin: 0.325em 0 0; }';
	$style .= '#dashboard_right_now .scp-widget-system-list li a:before { display: none; }';
	$style .= '#dashboard_right_now .main p.scp-widget-link-button { margin-top: 1.5em; }';
	$style .= '.scp-dashboard-search-fields { display: flex; flex-wrap: wrap; gap: 0.25em; }';
	$style .= '</style>';

	// Apply filter and print the style block.
	echo apply_filters( 'scp_website_default_print_styles', $style );
}
