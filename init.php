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
SiteCore\Classes            as Classes,
SiteCore\Classes\Core       as Core,
SiteCore\Classes\Settings   as Settings,
SiteCore\Classes\Tools      as Tools,
SiteCore\Classes\Media      as Media,
SiteCore\Classes\Users      as Users,
SiteCore\Classes\Admin      as Admin,
SiteCore\Classes\Front      as Front,
SiteCore\Classes\Front\Meta as Meta,
SiteCore\Classes\Vendor     as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Initialization function
 *
 * Loads PHP classes and text domain.
 * Instantiates various classes.
 * Adds settings link in the plugin row.
 *
 * @since  1.0.0
 * @access public
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

	// Get compatibility functions.
	require SCP_PATH . 'includes/vendor/compatibility.php';

	// Instantiate settings classes.
	new Settings\Settings;
	new Admin\Content_Settings;

	// Instantiate core classes.
	new Core\Type_Tax;
	new Core\Register_Sample_Type;
	new Core\Register_Sample_Tax;
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

	// Run tools.
	// @todo Put into a settings page.
	$scp_tools = new Tools\Tools;
	$scp_tools->rtl_test();
	$scp_tools->customizer_reset();
	$scp_tools->disable_floc();

	// Instantiate media class.
	new Media\Media;

	// Register media type taxonomy.
	new Media\Register_Media_Type;

	// Include Advanced Custom Fields.
	$scp_acf = new Vendor\Plugin_ACF;
	$scp_acf->include();

	// Include Advanced Custom Fields: Extended.
	$scp_acfe = new Vendor\Plugin_ACFE;
	$scp_acfe->include();

	new Vendor\Sample_ACF_Options;
	new Vendor\Sample_ACF_Suboptions;

	// Instantiate backend classes.
	if ( is_admin() ) {
		new Admin\Admin;
	}

	// Instantiate users classes.
	new Users\Users;

	// Instantiate frontend classes.
	if ( ! is_admin() ) {
		new Front\Frontend;
		new Front\Template_Filters;
		new Meta\Meta_Data;
		new Meta\Meta_Tags;
	}

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

	/**
	 * Disable emoji script
	 *
	 * Emojis will still work in modern browsers. This removes the script
	 * that makes emojis work in old browsers.
	 */
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// System email from text.
	add_filter( 'wp_mail_from_name', function( $name ) {
		return apply_filters( 'scp_mail_from_name', get_bloginfo( 'name' ) );
	} );

	// Disable WordPress administration email verification prompt.
	add_filter( 'admin_email_check_interval', '__return_false' );
}

// Run initialization function.
init();

/**
 * Admin initialization function
 *
 * Instantiates various classes.
 *
 * @since  1.0.0
 * @access public
 * @global $pagenow Get the current admin screen.
 * @return void
 */
function admin_init() {

	// Access current admin page.
	global $pagenow;
}
add_action( 'admin_init', __NAMESPACE__ . '\admin_init' );
