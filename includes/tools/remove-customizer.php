<?php
/**
 * Remove Customizer
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Remove_Customizer;

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

	add_action( 'init', $ns( 'remove' ), 10 );
}

/**
 * Remove Customizer parts
 *
 * @since  1.0.0
 * @return void
 */
function remove() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// If the Customizer is disabled in the system config file.
	if ( ( defined( 'SCP_ALLOW_CUSTOMIZER' ) && false == SCP_ALLOW_CUSTOMIZER ) && ! current_user_can( 'develop' ) ) {

		// Remove Customizer parts.
		add_action( 'admin_init', $ns( 'init' ), 10 );

		// Remove customize capability.
		add_filter( 'map_meta_cap', $ns( 'capability' ), 10, 4 );
	}
}

/**
 * Init
 *
 * @since  1.0.0
 * @return void
 */
function init() {

	// Remove Customizer actions.
	remove_action( 'plugins_loaded', '_wp_customize_include', 10 );
	remove_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings', 11 );

	// Disable direct access to Customizer.
	add_action( 'load-customize.php', __NAMESPACE__ . '\direct_access' );
}

/**
 * Disable direct access to Customizer
 *
 * @since  1.0.0
 * @return void
 */
function direct_access() {
	wp_die( __( 'The Customizer is currently disabled.', 'sitecore' ) );
}

/**
 * Remove customize capability
 *
 * This needs to be public for the user toolbar link for 'Customize' to be hidden.
 *
 * Replaces `customize` capability with a fake capability.
 *
 * @since  1.0.0
 * @return array Returns an array of capabilities.
 */
function capability( $caps = [], $cap = '', $user_id = 0, $args = [] ) {

	if ( $cap == 'customize' ) {
		return [ 'fake_capability' ];
	}
	return $caps;
}
