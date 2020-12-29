<?php
/**
 * Initialize plugin functionality
 *
 * @package    Site_Core
 * @subpackage Init
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore;

// Alias namespaces.
use SiteCore\Classes          as General;
use SiteCore\Classes\Activate as Activate;
use SiteCore\Classes\Core     as Core;
use SiteCore\Classes\Settings as Settings;
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
 * Removes unwanted features.
 *
 * @since  1.0.0
 * @access public
 * @global string $pagenow Gets the filename of the current page.
 * @return void
 */
function sitecore() {

	/**
	 * Class autoloader
	 *
	 * The autoloader registers plugin classes for later use,
	 * such as running new instances below.
	 */
	require SCP_PATH . 'includes/autoloader.php';

	// Get constants & helpers.
	require SCP_PATH . 'includes/config.php';

	// Get compatibility functions.
	require SCP_PATH . 'vendor/compatibility.php';

	/**
	 * Base class
	 *
	 * This offers methods that may be widely used
	 * so other classes can extend this to add scripts
	 * and styles, and other common operations.
	 */
	new General\Base;

	// Instantiate core plugin classes.
	new Core\Type_Tax;
	new Core\Register_Admin;
	new Core\Register_Site_Help;

	// Instantiate settings plugin classes.
	new Settings\Settings;

	// If the Customizer is disabled in the system config file.
	if ( ( defined( 'SCP_ALLOW_CUSTOMIZER' ) && false == SCP_ALLOW_CUSTOMIZER ) && ! current_user_can( 'develop' ) ) {
		new Core\Remove_Customizer;
	}

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

	// Instantiate third-party plugin classes.
	new Vendor\Plugins;

	// Instantiate backend classes.
	if ( is_admin() ) {
		new Admin\Admin;
	}

	// Instantiate users classes.
	new Users\Users;

	if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
		new Users\User_Toolbar;
	}

	// Instantiate frontend classes.
	if ( ! is_admin() ) {
		new Front\Frontend;
	}

	// Disable WordPress administration email verification prompt.
	add_filter( 'admin_email_check_interval', '__return_false' );

	// Disable Site Health notifications.
	add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );

	// Remove the Draconian capital P filter.
	remove_filter( 'the_title', 'capital_P_dangit', 11 );
	remove_filter( 'the_content', 'capital_P_dangit', 11 );
	remove_filter( 'comment_text', 'capital_P_dangit', 31 );
}

// Run the plugin.
sitecore();
