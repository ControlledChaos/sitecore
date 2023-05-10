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

	$admin = new Admin_Class\Network_Admin_Settings_Page;

	if ( get_blog_option( get_main_site_id(), 'enable_sample_files', false ) ) {
		$sample     = new Admin_Class\Sample_Network_Page;
		$sample_sub = new Admin_Class\Sample_Network_Subpage;

		$sample->add_page();
		$sample_sub->add_page();
	}
}

/**
 * Admin settings
 *
 * @since  1.0.0
 * @return void
 */
function admin_settings() {

	$sections  = new Settings_Class\Settings_Sections_Network_Admin;
	$dashboard = new Settings_Class\Settings_Fields_Network_Admin_Dashboard;
	$menu      = new Settings_Class\Settings_Fields_Network_Admin_Menu;

	$sections->sections();
	$dashboard->fields();
	$menu->fields();
}
