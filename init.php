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
use
SiteCore\Classes          as Classes,
SiteCore\Classes\Core     as Core,
SiteCore\Classes\Settings as Settings,
SiteCore\Classes\Tools    as Tools,
SiteCore\Classes\Media    as Media,
SiteCore\Classes\Users    as Users,
SiteCore\Classes\Admin    as Admin,
SiteCore\Classes\Front    as Front,
SiteCore\Classes\Vendor   as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Load plugin text domain
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function text_domain() {

	// Standard plugin installation.
	load_plugin_textdomain(
		SCP_CONFIG['domain'],
		false,
		dirname( SCP_BASENAME ) . '/languages'
	);

	// If this is in the must-use plugins directory.
	load_muplugin_textdomain(
		SCP_CONFIG['domain'],
		dirname( SCP_BASENAME ) . '/languages'
	);
}

/**
 * Core plugin function
 *
 * Loads and runs PHP classes.
 * Removes unwanted features.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function sitecore() {

	// Load text domain. Hook to `init` rather than `plugins_loaded`.
	add_action( 'init', __NAMESPACE__ . '\text_domain' );

	/**
	 * Class autoloader
	 *
	 * The autoloader registers plugin classes for later use,
	 * such as running new instances below.
	 */
	require_once SCP_PATH . 'includes/autoloader.php';

	// Get compatibility functions.
	require SCP_PATH . 'includes/vendor/compatibility.php';

	// Instantiate settings classes.
	new Settings\Settings;

	// Instantiate core classes.
	new Core\Type_Tax;
	new Core\Register_Admin;
	new Core\Register_Site_Help;

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
	new Tools\Tools;

	// Instantiate media classes.
	new Media\Media;

	// Instantiate third-party classes.
	new Vendor\Plugins;

	// Instantiate backend classes.
	if ( is_admin() ) {
		new Admin\Admin;
	}

	// Instantiate users classes.
	new Users\Users;

	// Instantiate frontend classes.
	if ( ! is_admin() ) {
		new Front\Frontend;
	}

	// Disable WordPress administration email verification prompt.
	add_filter( 'admin_email_check_interval', '__return_false' );

	// Disable Site Health notifications.
	if ( defined( 'SCP_ALLOW_SITE_HEALTH' ) && ! SCP_ALLOW_SITE_HEALTH ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}

	/**
	 * Restore links manager
	 *
	 * @todo Put into an option.
	 */
	add_filter( 'pre_option_link_manager_enabled', '__return_true' );

	// Remove the Draconian capital P filter.
	remove_filter( 'the_title', 'capital_P_dangit', 11 );
	remove_filter( 'the_content', 'capital_P_dangit', 11 );
	remove_filter( 'comment_text', 'capital_P_dangit', 31 );
}

// Run the plugin.
sitecore();
