<?php
/**
 * Initiate settings sections and fields
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
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
}

/**
 * Admin settings
 *
 * @since  1.0.0
 * @return void
 */
function admin_settings() {

	new Settings_Class\Settings_Sections_Admin;
	new Settings_Class\Settings_Fields_Admin_Dashboard;
	new Settings_Class\Settings_Fields_Admin_Footer;
	new Settings_Class\Settings_Fields_Admin_Header;
	new Settings_Class\Settings_Fields_Admin_Menu;
	new Settings_Class\Settings_Fields_Admin_Toolbar;
	new Settings_Class\Settings_Fields_Admin_Users;
	new Backend_Class\Admin_Settings_Page;
}

/**
 * Content settings
 *
 * @since  1.0.0
 * @return void
 */
function content_settings() {

	new Settings_Class\Settings_Sections_Content;
	new Settings_Class\Settings_Fields_Content_Posts;
	new Backend_Class\Content_Settings_Page;
}

/**
 * Developer settings
 *
 * @since  1.0.0
 * @return void
 */
function developer_settings() {

	new Settings_Class\Settings_Sections_Developer;
	new Settings_Class\Settings_Fields_Developer;
	new Settings_Class\Settings_Fields_Developer_Users;
	new Backend_Class\Developer_Settings_Page;
}
