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
 * Check for Advanced Custom Fields
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function has_acf() {

	if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * Check for Advanced Custom Fields PRO
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function has_acf_pro() {

	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		return true;
	}
	return false;
}

/**
 * Check for Advanced Custom Fields: Extended
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function has_acfe() {

	if ( is_plugin_active( 'acf-extended/acf-extended.php' ) ) {
		return true;
	}
	return false;
}

/**
 * Check for Advanced Custom Fields: Extended PRO
 *
 * @since  1.0.0
 * @access public
 * @return boolean Returns true if the plugin is installed & active.
 */
function has_acfe_pro() {

	if ( is_plugin_active( 'acf-extended-pro/acf-extended.php' ) ) {
		return true;
	}
	return false;
}
