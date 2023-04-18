<?php
/**
 * Core classes & functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Core;

use SiteCore\Classes\Core as Core_Class;

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

	// Allow link manager.
	if ( get_option( 'enable_link_manager', false ) ) {
		add_filter( 'pre_option_link_manager_enabled', '__return_true' );
	}

	// Disable Site Health notifications.
	if ( get_option( 'disable_site_health', false ) ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}
	if ( defined( 'SCP_DISABLE_SITE_HEALTH' ) && SCP_DISABLE_SITE_HEALTH ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}

	// Disable block widgets.
	if ( get_option( 'disable_block_widgets', true ) ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}
	if ( defined( 'SCP_DISABLE_BLOCK_WIDGETS' ) && SCP_DISABLE_BLOCK_WIDGETS ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}

	// Remove the Draconian capital P filters.
	remove_filter( 'the_title', 'capital_P_dangit', 11 );
	remove_filter( 'the_content', 'capital_P_dangit', 11 );
	remove_filter( 'comment_text', 'capital_P_dangit', 31 );

	// System email from text.
	add_filter( 'wp_mail_from_name', function( $name ) {
		return apply_filters( 'scp_mail_from_name', get_bloginfo( 'name' ) );
	} );

	// Disable WordPress administration email verification prompt.
	add_filter( 'admin_email_check_interval', '__return_false' );
}

/**
 * Core classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {

	new Core_Class\Register_Admin;
	if ( get_option( 'remove_blog' ) ) {
		new Core_Class\Remove_Blog;
	}

	// If the Customizer is disabled in the system config file.
	if ( ( defined( 'SCP_ALLOW_CUSTOMIZER' ) && false == SCP_ALLOW_CUSTOMIZER ) && ! current_user_can( 'develop' ) ) {
		new Core_Class\Remove_Customizer;
	}

	/**
	 * Editor options for WordPress
	 *
	 * Not run for ClassicPress and the default antibrand system.
	 * The `Core\is_classicpress()` function checks for ClassicPress.
	 *
	 * Not run if the Classic Editor plugin is active.
	 */
	if ( ! is_classicpress() ) {
		if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			new Core_Class\Editor_Options;
		}
	}

	if ( get_option( 'enable_sample_files', false ) ) {
		new Core_Class\Register_Sample_Type;
		new Core_Class\Register_Sample_Tax;
	}
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
