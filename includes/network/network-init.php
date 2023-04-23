<?php
/**
 * Network classes & functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Network
 * @since      1.0.0
 */

namespace SiteCore\Network;

use SiteCore\Classes\Network  as Network_Class,
	SiteCore\Classes\Admin    as Admin_Class,
	SiteCore\Classes\Settings as Settings_Class;

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
	add_action( 'init', $ns( 'admin_settings' ), 10 );
}

/**
 * Network classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {

	new Admin_Class\Network_Admin_Settings_Page;
	// new Admin_Class\Sample_Network_Page;
	// new Admin_Class\Sample_Network_Subpage;
}

/**
 * Admin settings
 *
 * @since  1.0.0
 * @return void
 */
function admin_settings() {

	new Settings_Class\Settings_Sections_Network_Admin;
	new Settings_Class\Settings_Fields_Network_Admin_Dashboard;
	new Settings_Class\Settings_Fields_Network_Admin_Menu;
}
