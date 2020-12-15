<?php
/**
 * Initialize plugin functionality
 *
 * @package    Site_Core
 * @subpackage Init
 * @category   Core
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore;

// Alias namespaces.
use SiteCore\Classes          as General;
use SiteCore\Classes\Activate as Activate;
use SiteCore\Classes\Core     as Core;
use SiteCore\Classes\Tools    as Tools;
use SiteCore\Classes\Media    as Media;
use SiteCore\Classes\Users    as Users;
use SiteCore\Classes\Admin    as Admin;
use SiteCore\Classes\Front    as Front;
use SiteCore\Classes\Vendor   as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Core plugin function
 *
 * Loads and runs PHP classes.
 *
 * @since  1.0.0
 * @access public
 * @global string $pagenow Gets the filename of the current page.
 * @return void
 */
function sitecore() {

	// Get the filename of the current page.
	global $pagenow;

	/**
	 * Get plugins path
	 *
	 * Used to check for active plugins with the `is_plugin_active` function.
	 */

	// Compatibility with ClassicPress and WordPress.
	if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	// Compatibility with the antibrand system.
	} elseif ( defined( 'APP_INC_PATH' ) && file_exists( APP_INC_PATH . '/backend/plugin.php' ) ) {
		include_once( APP_INC_PATH . '/backend/plugin.php' );
	}

	/**
	 * Get pluggable path
	 *
	 * Used to check for the `is_user_logged_in` function.
	 */

	// Compatibility with ClassicPress and WordPress.
	if ( ( function_exists( 'is_multisite' ) && ! is_multisite() ) && file_exists( ABSPATH . 'wp-includes/pluggable.php' ) ) {
		include_once( ABSPATH . 'wp-includes/pluggable.php' );

	// Compatibility with the antibrand system.
	} elseif ( ( function_exists( 'is_network' ) && ! is_network() ) && defined( 'APP_INC_PATH' ) && file_exists( APP_INC_PATH . '/pluggable.php' ) ) {
		include_once( APP_INC_PATH . '/pluggable.php' );
	}

	/**
	 * Class autoloader
	 *
	 * The autoloader registers plugin classes for later use,
	 * such as running new instances below.
	 */
	require SCP_PATH . 'includes/autoloader.php';

	// Instantiate core plugin classes.
	new Core\Type_Tax;
	new Core\Register_Site_Help;

	/**
	 * Editor options for WordPress
	 *
	 * Not run for ClassicPress and the default antibrand system.
	 * The `classicpress_version()` function checks for ClassicPress.
	 * The `APP_INC_PATH` constant checks for the default antibrand system.
	 *
	 * Not run if the Classic Editor plugin is active.
	 */
	if ( ! function_exists( 'classicpress_version' ) || ! defined( 'APP_INC_PATH' ) ) {
		if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			new Core\Editor_Options;
		}
	}

	// Instantiate tools classes.
	// @todo Put into a settings page. new Tools\RTL_Test;
	// @todo Put into a settings page. new Tools\Customizer_Reset;

	// Instantiate media classes.
	new Media\Media;
	new Media\Register_Media_Type;

	// Instantiate third-party plugin classes.
	new Vendor\Plugins;

	// Instantiate backend classes.
	if ( is_admin() ) {
		new Admin\Admin;
		new Admin\Manage_Website_Page;
	}

	// Run the dashboard only on the backend index screen.
	if ( 'index.php' == $pagenow ) {
		new Admin\Dashboard;
	}

	// Instantiate users classes.
	new Users\Users;

	// Instantiate frontend classes.
	if ( ! is_admin() ) {
		new Front\Frontend;
	}

	if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
		new General\User_Toolbar;
	}

	// Disable Site Health notifications.
	add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
}

// Run the plugin.
sitecore();
