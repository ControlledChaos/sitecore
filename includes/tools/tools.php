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

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Disable FloC
 *
 * Executes the `floc_headers` function.
 *
 * @since  1.0.0
 * @return void
 */
function disable_floc() {
	add_filter( 'wp_headers', __NAMESPACE__ . '\\disable_floc' );
}

/**
 * RTL/LTR switcher
 *
 * Executes the direction functions.
 *
 * @since  1.0.0
 * @return void
 */
function dir_switch() {
	add_action( 'init', __NAMESPACE__ . '\\set_direction' );
	add_action( 'admin_bar_menu', __NAMESPACE__ . '\\toolbar_dir_switch', 999 );
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

		$policies[$n] = $policy = trim( $policy );

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
