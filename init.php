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
SiteCore\Classes\Autoload as Autoload;

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

	// If this plugin is in the must-use plugins directory.
	load_muplugin_textdomain(
		'sitecore',
		dirname( SCP_BASENAME ) . '/languages'
	);

	// Autoload classes.
	require_once SCP_PATH . 'includes/classes/autoload.php';
	Autoload\classes();

	// Load required files.
	foreach ( glob( SCP_PATH . 'includes/core/*.php' ) as $filename ) {
		require $filename;
	}
	foreach ( glob( SCP_PATH . 'includes/settings/*.php' ) as $filename ) {
		require $filename;
	}
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
	foreach ( glob( SCP_PATH . 'includes/vendor/*.php' ) as $filename ) {
		require $filename;
	}

	// Instantiate classes & define functions.
	Settings\setup();
	Core\setup();
	Tools\setup();
	Media\setup();
	Vendor\setup();
	Users\setup();
	User_Roles\setup();
	Front_Page_Post_Type\setup();

	// Front end.
	if ( ! is_admin() ) {
		Front\setup();
		Meta_Tags\setup();
	}

	// Back end.
	if ( is_admin() ) {
		Admin\setup();
		Admin_Footer\setup();
	}
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );
