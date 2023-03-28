<?php
/**
 * Platform functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Core;

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
}

/**
 * Check for ClassicPress
 *
 * @since  1.0.0
 * @return boolean Returns true is ClassicPress is used.
 */
function is_classicpress() {

	if ( function_exists( 'classicpress_version' ) ) {
		return true;
	}
	return false;
}

/**
 * Platform name
 *
 * @since  1.0.0
 * @return string Returns the name of the platform
 */
function platform_name() {

	$name = 'WordPress';
	if ( is_classicpress() ) {
		$name = 'ClassicPress';
	}

	return apply_filters( 'scp_platform_name', $name );
}

/**
 * Dashboard type
 *
 * @since  1.0.0
 * @access public
 * @return string Returns the text of the dashboard type.
 */
function dashboard_type() {

	if ( is_network_admin() ) {
		return 'network';
	} else {
		return 'website';
	}
}
