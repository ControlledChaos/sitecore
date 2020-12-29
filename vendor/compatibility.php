<?php
/**
 * Third-party code compatibility
 *
 * @package    Site_Core
 * @subpackage Vendor
 * @category   Functions
 * @since      1.0.0
 */

namespace SiteCore;

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
if ( ! function_exists( 'is_plugin_active' ) ) {

	// Compatibility with ClassicPress and WordPress.
	if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
		include( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Compatibility with the antibrand system.
	} elseif ( defined( 'APP_INC_PATH' ) && file_exists( APP_INC_PATH . '/backend/plugin.php' ) ) {
		include( APP_INC_PATH . '/backend/plugin.php' );
	}
}

// Stop here if the plugin functions file can not be accessed.
if ( ! function_exists( 'is_plugin_active' ) ) {
	return;
}

/**
 * ACF is active
 *
 * Checks for the Advanced Custom Fields plugin
 *
 * @since  1.0.0
 * @access public
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
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acf_pro() {

	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACFE is active
 *
 * Checks for the Advanced Custom Fields: Extended plugin
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acfe() {

	if ( is_plugin_active( 'acf-extended/acf-extended.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACFE PRO is active
 *
 * Checks for the Advanced Custom Fields: Extended PRO plugin
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function active_acfe_pro() {

	if ( is_plugin_active( 'acf-extended-pro/acf-extended.php' ) ) {
		return true;
	}
	return false;
}

/**
 * ACF included
 *
 * Checks for Advanced Custom Fields files included
 * with this plugin.
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the core file is found.
 */
function has_acf() {

	// Set core ACF file as a variable.
	$acf = file_exists( SCP_PATH . 'includes/vendor/acf/acf.php' );

	// Return true if the file is found.
	if ( $acf ) {
		return true;
	}

	// Otherwise return false.
	return false;
}

/**
 * ACFE included
 *
 * Checks for Advanced Custom Fields: Extended files included
 * with this plugin.
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the core file is found.
 */
function has_acfe() {

	// Set core ACF file as a variable.
	$acfe = file_exists( SCP_PATH . 'includes/vendor/acf-extended/acf-extended.php' );

	// Return true if the file is found.
	if ( $acfe ) {
		return true;
	}

	// Otherwise return false.
	return false;
}
