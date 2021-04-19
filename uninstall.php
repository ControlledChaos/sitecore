<?php
/**
 * Uninstall operations
 *
 * Fired when the plugin is uninstalled.
 *
 * Must not be namespaced!
 *
 * @package    Site_Core
 * @subpackage Core
 * @category   Uninstall
 * @since      1.0.0
 */

// If uninstall not called then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Register uninstall hook.
register_uninstall_hook( __FILE__, 'scp_sample_uninstall' );

/**
 * Sample uninstall function
 *
 * Change the name as necessary or delete.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function scp_sample_uninstall() {

	// Add uninstall operations here.
}
