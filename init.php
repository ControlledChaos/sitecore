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
SiteCore\Classes\Core     as Core_Class,
SiteCore\Classes\Settings as Settings_Class,
SiteCore\Classes\Tools    as Tools_Class,
SiteCore\Classes\Media    as Media_Class,
SiteCore\Classes\Users    as Users_Class,
SiteCore\Classes\Admin    as Backend_Class,
SiteCore\Classes\Front    as Frontend_Class,
SiteCore\Classes\Widgets  as Widgets_Class,
SiteCore\Classes\Vendor   as Vendor_Class;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Initialization function
 *
 * Loads PHP classes and text domain.
 * Executes various setup functions.
 * Instantiates various classes.
 * Adds settings link in the plugin row.
 *
 * @since  1.0.0
 * @return void
 */
function init() {

	// Standard plugin installation.
	load_plugin_textdomain(
		'sitecore',
		false,
		dirname( SCP_BASENAME ) . '/languages'
	);

	// If this is in the must-use plugins directory.
	load_muplugin_textdomain(
		'sitecore',
		dirname( SCP_BASENAME ) . '/languages'
	);

	/**
	 * Class autoloader
	 *
	 * The autoloader registers plugin classes for later use,
	 * such as running new instances below.
	 */
	require_once SCP_PATH . 'includes/autoloader.php';

	// Load required files.
	foreach ( glob( SCP_PATH . 'includes/post-types/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/media/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/backend/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/frontend/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/users/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/tools/*.php' ) as $filename ) {
		require $filename;
	}

	// Get compatibility functions.
	require SCP_PATH . 'includes/vendor/compatibility.php';

	// Instantiate settings classes.
	new Settings_Class\Settings;
	new Backend_Class\Content_Settings;

	// Instantiate core classes.
	new Core_Class\Register_Sample_Type;
	new Core_Class\Register_Sample_Tax;
	new Core_Class\Register_Admin;
	new Core_Class\Register_Site_Help;

	// If the Customizer is disabled in the system config file.
	if ( ( defined( 'SCP_ALLOW_CUSTOMIZER' ) && false == SCP_ALLOW_CUSTOMIZER ) && ! current_user_can( 'develop' ) ) {
		new Core_Class\Remove_Customizer;
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
			new Core_Class\Editor_Options;
		}
	}

	// Tools.
	// @todo Put into a settings page.
	new Tools_Class\Customizer_Reset;
	Tools\dir_switch();
	Tools\disable_floc();

	// Media.
	Media\setup();
	new Media_Class\Register_Media_Type;

	// Advanced Custom Fields.
	$scp_acf = new Vendor_Class\Plugin_ACF;
	$scp_acf->include();

	// Advanced Custom Fields: Extended.
	$scp_acfe = new Vendor_Class\Plugin_ACFE;
	$scp_acfe->include();

	// Users.
	Users\setup();
	User_Roles\setup();
	if ( ! is_plugin_active( 'user-avatars/user-avatars.php' ) ) {
		new Users_Class\User_Avatars;
	}

	// Customizer/front end.
	if ( ( defined( 'SCP_ALLOW_CUSTOMIZER' ) && SCP_ALLOW_CUSTOMIZER ) ) {
		Front_Page_Post_Type\setup();
	}

	// Front end.
	if ( ! is_admin() ) {
		Front\setup();
		Meta_Tags\setup();
	}

	// Widgets.
	new Widgets_Class\Sample_Widget;

	// Disable Site Health notifications.
	if ( defined( 'SCP_ALLOW_SITE_HEALTH' ) && ! SCP_ALLOW_SITE_HEALTH ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}

	// Disable block widgets.
	if ( defined( 'SCP_ALLOW_BLOCK_WIDGETS' ) && ! SCP_ALLOW_BLOCK_WIDGETS ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}

	/**
	 * Allow links manager
	 *
	 * @todo Put into an option.
	 */
	if ( defined( 'SCP_ALLOW_LINKS_MANAGER' ) && SCP_ALLOW_LINKS_MANAGER ) {
		add_filter( 'pre_option_link_manager_enabled', '__return_true' );
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
add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

/**
 * Admin initialization function
 *
 * @since  1.0.0
 * @global $pagenow Get the current admin screen.
 * @global $typenow Get the current post type screen.
 * @return void
 */
function admin_init() {

	if ( ! is_admin() ) {
		return;
	}

	// Access global variables.
	global $pagenow, $typenow;

	Admin\setup();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\admin_init' );
