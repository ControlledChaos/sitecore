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

	add_action( 'init', $ns( 'instantiate_classes' ) );
	// instantiate_classes();
}

/**
 * Instantiate classes
 *
 * Run the settings sections and fields classes.
 *
 * @since  1.0.0
 * @return void
 */
function instantiate_classes() {

	new Settings_Class\Settings_Sections_Admin;
	new Settings_Class\Settings_Sections_Content;

	new Settings_Class\Settings_Fields_Admin_Dashboard;
	new Settings_Class\Settings_Fields_Admin_Footer;
	new Settings_Class\Settings_Fields_Admin_Menu;
	new Settings_Class\Settings_Fields_Admin_Toolbar;
	new Settings_Class\Settings_Fields_Admin_Users;

	new Settings_Class\Settings_Fields_Content_Posts;

	new Backend_Class\Content_Settings_Page;
	new Backend_Class\Admin_Settings_Page;
}
