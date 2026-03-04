<?php
/**
 * Various utilities
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Tools;

use SiteCore\{
	Classes\Core      as Core_Class,
	Classes\Tools     as Tools_Class,
	Types_Taxes_Order as Types_Taxes_Order,
	Theme_Test_Drive  as Test_Drive,
	Disable_Toolbar   as Disable_Toolbar,
	Remove_Customizer as Remove_Customizer
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

	add_action( 'tool_box', $ns( 'available_tools' ) );
	add_action( 'admin_head', $ns( 'add_help_tabs' ) );

	if ( get_option( 'type_tax_sort_order', false ) ) {
		Types_Taxes_Order\setup();
	}

	if ( get_option( 'direction_switch', false ) ) {
		add_action( 'init', $ns( 'set_direction' ) );
		add_action( 'admin_bar_menu', $ns( 'toolbar_dir_switch' ), 999 );
	}

	if ( get_option( 'disable_floc', true ) ) {
		add_filter( 'wp_headers', $ns( 'floc_headers' ) );
	}

	if ( get_option( 'theme_test_drive' ) ) {
		Test_Drive\setup();
	}

	if ( ! get_option( 'enable_text_domain', true ) ) {
		add_filter( 'pre_load_textdomain', '__return_false', -42 );
	}

	if ( is_multisite() ) {
		if ( is_network_admin() ) {
			Disable_Toolbar\setup();
		} else {
			if ( get_option( 'disable_user_toolbar', false ) ) {
				Disable_Toolbar\setup();
			}
		}
	} else {
		if ( get_option( 'disable_user_toolbar', false ) ) {
			Disable_Toolbar\setup();
		}
	}

	Remove_Customizer\setup();
}

/**
 * Tools classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {

	if ( get_option( 'customizer_reset', false ) ) {
		Tools_Class\Customizer_Reset :: instance();
	}
}

/**
 * Is tools screen
 *
 * @since  1.0.0
 * @global $pagenow Get the current admin screen.
 * @return boolean Returns true if on the tools screen.
 *                 Returns false for subpages.
 */
function is_tools_screen() {

	// Access current admin page.
	global $pagenow;

	$parse = parse_url( $_SERVER['REQUEST_URI'] );
	if ( 'tools.php' == $pagenow && ! array_key_exists( 'query', $parse ) ) {
		return true;
	}
	return false;
}

/**
 * Developer settings page
 *
 * Checks for the Developer_Settings_Page class.
 *
 * @since  1.0.0
 * @return boolean Returns true if the class exists.
 */
function dev_settings_page() {

	if ( class_exists( 'SiteCore\Classes\Admin\Developer_Settings_Page', ) ) {
		return true;
	}
	return false;
}

/**
 * Available tools
 *
 * Adds cards to the tools.php screen
 *
 * @since  1.0.0
 * @return void
 */
function available_tools() {

	if ( current_user_can( 'manage_options' ) ) :
	?>
	<div class="card">
		<h2 class="title"><?php _e( 'Content Tools', 'sitecore' ); ?></h2>
		<p>
		<?php
			printf(
				__( 'Import and export various content types on the <a href="%s">content tools</a> page. This includes custom fields and forms. Developers can export some ACF content as PHP for plugin and theme development.', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=acf-tools' ) )
			);
		?>
		</p>
	</div>
	<div class="card">
		<h2 class="title"><?php _e( 'Manage Options', 'sitecore' ); ?></h2>
		<p>
		<?php
			printf(
				__( 'Manage this website\'s options database table from the <a href="%s">options edit</a> page. This is for advanced users or developers only.', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=acfe-options' ) )
			);
		?>
		</p>
	</div>
	<?php
	endif;

	if ( current_user_can( 'develop' ) && dev_settings_page() ) :
	?>
	<div class="card">
		<h2 class="title"><?php _e( 'Developer Tools', 'sitecore' ); ?></h2>
		<p>
		<?php
			printf(
				__( 'As a registered developer of this website there are <a href="%s">tools available</a> to you for managing the site.', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=developer-tools' ) )
			);
		?>
		</p>
	</div>
	<div class="card">
		<h2 class="title"><?php _e( 'Orphan Meta Cleaner', 'sitecore' ); ?></h2>
		<p>
		<?php
			printf(
				__( 'Clean orphan metadata from posts, terms, users, and options pages with <a href="%s">this tool</a>.', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=acfe-scripts&script=orphan_meta_cleaner' ) )
			);
		?>
		</p>
	</div>
	<div class="card">
		<h2 class="title"><?php _e( 'Single Meta Converter', 'sitecore' ); ?></h2>
		<p>
		<?php
			printf(
				__( 'Convert posts, users, taxonomies & options pages meta to Single Meta or back to normal <a href="%s">this tool</a>.', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=acfe-scripts&script=single_meta_converter' ) )
			);
		?>
		</p>
	</div>
	<?php endif;
}

/**
 * Add help tabs
 *
 * @since  1.0.0
 * @return void
 */
function add_help_tabs() {

	if ( ! is_tools_screen() ) {
		return;
	}

	$screen = get_current_screen();

	$screen->add_help_tab(
		[
			'id'      => 'edit_options',
			'title'   => __( 'Manage Options', 'sitecore' ),
			'content' => sprintf(
				__( '<p>The <a href="%s">options management page</a> page edits the options database. Only use this if you are familiar with editing the database via the phpmyadmin interface.</p>', 'sitecore' ),
				esc_url( admin_url( 'tools.php?page=acfe-options' ) )
			)
		]
	);

	if ( current_user_can( 'develop' ) && dev_settings_page() ) {
		$screen->add_help_tab(
			[
				'id'      => 'dev_tools',
				'title'   => __( 'Developer Tools', 'sitecore' ),
				'content' => sprintf(
					__( '<p>The <a href="%s">developer tools</a> are only available to logged-in users with the user capability of "develop" under the "Developer" user role.</p>', 'sitecore' ),
					esc_url( admin_url( 'tools.php?page=developer-tools' ) )
				)
			]
		);
	}
	$screen->set_help_sidebar( null );
}

/**
 * FloC headers
 *
 * Disable Google's next-generation tracking technology.
 * Adds an http header to disable FLoC.
 *
 * Don't feed The Beast!
 *
 * @since  1.0.0
 * @param  array $headers
 * @return array Returns a modified array of http headers.
 */
function floc_headers( $headers ) {

	// No Permissions-Policy header present? Add one and return.
	if ( empty( $headers['Permissions-Policy'] ) ) {
		$headers['Permissions-Policy'] = 'interest-cohort=()';
		return $headers;
	}

	// Separate Permissions-Policy directives.
	$policies = explode( ',', $headers['Permissions-Policy'] );

	// Check for existence of interest-cohort directive; set flag.
	foreach ( $policies as $n => $policy ) {

		$policies[$n] = $policy = trim( $policy ?? '' );

		if ( stripos( $policy, 'interest-cohort' ) === 0 ) {
			$directive_present = true;
		}
	}

	// If interest-cohort directive not present, add it.
	if ( ! isset( $directive_present ) ) {
		$policies[] = 'interest-cohort=()';
	}

	// Assign policies to the header.
	$headers['Permissions-Policy'] = implode( ', ', $policies );

	// Return headers.
	return $headers;
}

/**
 * Set RTL/LTR direction
 *
 * Saves the currently chosen direction on a per-user basis.
 *
 * @since  1.0.0
 * @global WP_Locale $wp_locale Locale object.
 * @global WP_Styles $wp_styles Styles object.
 * @return void
 */
function set_direction() {

	global $wp_locale, $wp_styles;

	$_user_id = get_current_user_id();

	if ( isset( $_GET['d'] ) ) {
		$direction = $_GET['d'] == 'rtl' ? 'rtl' : 'ltr';
		update_user_meta( $_user_id, 'rtladminbar', $direction );

	} else {
		$direction = get_user_meta( $_user_id, 'rtladminbar', true );

		if ( false === $direction ) {
			$direction = isset( $wp_locale->text_direction ) ? $wp_locale->text_direction : 'ltr';
		}
	}

	$wp_locale->text_direction = $direction;

	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		$wp_styles = new \WP_Styles();
	}

	$wp_styles->text_direction = $direction;
}

/**
 * RTL/LTR toolbar switch
 *
 * Adds a button to the user toolbar for toggling LTR & RTL.
 *
 * @since  1.0.0
 * @global object wp_admin_bar Most likely instance of WP_Admin_Bar but this is filterable.
 * @return void Returns early if capability check isn't matched, or admin bar should not be showing.
 */
function toolbar_dir_switch() {

	global $wp_admin_bar;

	$required_cap = apply_filters( 'dir_tester_capability_check', 'activate_plugins' );

	if ( ! current_user_can( $required_cap ) || ! is_admin_bar_showing() ) {
		return;
	}

	// Get opposite direction for button text.
	if ( is_rtl() ) {
		$direction = 'ltr';
	} else {
		$direction = 'rtl';
	}

	// Add the link in the toolbar.
	$wp_admin_bar->add_menu(
		[
			'id'    => 'RTL',
			'title' => sprintf( __( 'Switch to %s', 'sitecore' ), strtoupper( $direction ) ),
			'href'  => add_query_arg( [ 'd' => $direction ] )
		]
	);
}
