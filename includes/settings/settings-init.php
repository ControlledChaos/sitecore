<?php
/**
 * Initiate settings sections and fields
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Settings;

use
SiteCore\Classes\Admin    as Backend_Class,
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

	add_action( 'init', $ns( 'admin_settings' ), 10 );
	add_action( 'init', $ns( 'content_settings' ), 10 );
	add_action( 'init', $ns( 'developer_settings' ), 10 );
	add_action( 'init', $ns( 'sample_settings' ), 10 );
}

/**
 * Admin settings
 *
 * @since  1.0.0
 * @return void
 */
function admin_settings() {

	$sections  = new Settings_Class\Settings_Sections_Admin;
	$dashboard = new Settings_Class\Settings_Fields_Admin_Dashboard;
	$footer    = new Settings_Class\Settings_Fields_Admin_Footer;
	$forms     = new Settings_Class\Settings_Fields_Admin_Forms;
	$header    = new Settings_Class\Settings_Fields_Admin_Header;
	$menu      = new Settings_Class\Settings_Fields_Admin_Menu;
	$toolbar   = new Settings_Class\Settings_Fields_Admin_Toolbar;
	$users     = new Settings_Class\Settings_Fields_Admin_Users;
	$page      = new Backend_Class\Admin_Settings_Page;

	$sections  -> sections();
	$dashboard -> fields();
	$footer    -> fields();
	$forms     -> fields();
	$header    -> fields();
	$menu      -> fields();
	$toolbar   -> fields();
	$users     -> fields();
	$page      -> add_page();
}

/**
 * Content settings
 *
 * @since  1.0.0
 * @return void
 */
function content_settings() {

	$sections = new Settings_Class\Settings_Sections_Content;
	$posts    = new Settings_Class\Settings_Fields_Content;
	$page     = new Backend_Class\Content_Settings_Page;

	$sections -> sections();
	$posts    -> fields();
	$page     -> add_page();
}

/**
 * Developer settings
 *
 * @since  1.0.0
 * @return void
 */
function developer_settings() {

	$sections = new Settings_Class\Settings_Sections_Developer;
	$tools    = new Settings_Class\Settings_Fields_Developer;
	$content  = new Settings_Class\Settings_Fields_Developer_Content;
	$users    = new Settings_Class\Settings_Fields_Developer_Users;
	$page     = new Backend_Class\Developer_Settings_Page;

	$sections -> sections();
	$tools    -> fields();
	$content  -> fields();
	$users    -> fields();
	$page     -> add_page();
}

/**
 * Sample settings
 *
 * Only registered and added if the sample files
 * settings is true on the developer settings page.
 *
 * @since  1.0.0
 * @return void
 */
function sample_settings() {

	if ( ! get_option( 'enable_sample_files', false ) ) {
		return;
	}

	$sections = new Settings_Class\Settings_Sections_Sample;
	$settings = new Settings_Class\Settings_Fields_Sample;

	$sections -> sections();
	$settings -> fields();
}
