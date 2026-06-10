<?php
/**
 * Third-party code compatibility
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Compatibility;

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

	// Disable Site Health in Dashboard Summary plugin.
	if ( get_option( 'disable_site_health', false ) ) {
		add_action( 'init', function() {
			add_filter( 'ds_show_health_link', '__return_false' );
		} );
	}

	add_action( 'init', $ns( 'ds_acf_tools_link' ) );
}

/**
 * Get pluggable path
 *
 * Used to check for the `is_user_logged_in` function.
 */

if ( ( function_exists( 'is_multisite' ) && ! is_multisite() ) && file_exists( ABSPATH . 'wp-includes/pluggable.php' ) ) {
	include_once( ABSPATH . 'wp-includes/pluggable.php' );
}

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
if ( ! function_exists( 'is_plugin_active' ) ) {
	if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
}

// Stop here if the plugin functions file can not be accessed.
if ( ! function_exists( 'is_plugin_active' ) ) {
	return;
}

/**
 * ACF is active
 *
 * Checks for the Advanced Custom Fields plugin.
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acf() {

	if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACF PRO is active
 *
 * Checks for the Advanced Custom Fields PRO plugin
 * and for the same core class bundled in this plugin.
 *
 * @since  1.0.0
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acf_pro() {

	if (
		is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ||
		is_plugin_active( 'applied-content-forms/acf.php' )
	) {
		return true;
	}
	return false;
}

/**
 * ACF included
 *
 * Checks for Advanced Custom Fields files included
 * with this plugin or as activated original plugin.
 *
 * @since  1.0.0
 * @return boolean Returns true if the core file is found & included.
 */
function has_acf() {

	// Look for ACF files and set as a variable.
	$acf = file_exists( SCP_PATH . 'includes/vendor/acf/acf.php' );

	// Return true if the file is found.
	if ( $acf && class_exists( 'acf' ) ) {
		return true;
	}

	// Otherwise return false.
	return false;
}

/**
 * ACF PRO included
 *
 * Checks for Advanced Custom Fields PRO files included
 * with this plugin.
 *
 * @since  1.0.0
 * @return boolean Returns true if the core file is found & included.
 */
function has_acf_pro() {

	// Look for ACF PRO files and set as a variable.
	$acf_pro = file_exists( SCP_PATH . 'includes/vendor/acf/acf.php' );

	// Return true if the file is found.
	if ( $acf_pro && class_exists( 'acf_pro' ) ) {
		return true;
	}

	// Otherwise return false.
	return false;
}

/**
 * ACF ready
 *
 * Returns true if ACF or ACF PRO is found.
 *
 * @since  1.0.0
 * @return boolean Returns true if ACF is found.
 */
function acf_ready() {

	// Return true if ready.
	if ( has_acf() || has_acf_pro() || active_acf() || active_acf_pro() ) {
		return true;
	}

	// Otherwise return false.
	return false;
}


/**
 * Dashboard Summary filters
 *
 * @link https://github.com/ControlledChaos/dashboard-summary
 *
 * @since  1.0.0
 * @return boolean Returns true if ACFE is found.
 */
function ds_acf_tools_link() {

	if ( ! is_plugin_active( 'dashboard-summary/dashboard-summary.php' ) ) {
		return;
	}

	// Filters the link to the ACF tools page.
	add_filter( 'ds_acf_link_tools', function() {
		return 'tools.php?page=acf-tools';
	} );
}
