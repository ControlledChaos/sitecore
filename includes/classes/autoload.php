<?php
/**
 * Register plugin classes
 *
 * The autoloaders register plugin classes for later use.
 *
 * @see Demo function at end of this file.
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Classes
 * @since      1.0.0
 */

namespace SiteCore\Classes\Autoload;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Load classes
 *
 * Runs the autoload functions.
 *
 * @since  1.0.0
 * @return void
 */
function classes() {
	core();
	settings();
	tools();
	media();
	users();
	vendor();
	admin();
	front();
	widgets();
}

/**
 * Namespace & class name
 *
 * Class namespaces must contain `Classes` and a
 * category following the plugin namespace.
 * Example: `SiteCore\Classes\Category\My_Class`
 *
 * @since  1.0.0
 * @param  string $cat
 * @param  string $class
 * @return string Returns the namespace with category and class name.
 *                Example: SiteCore\Classes\Admin\My_Class.
 */
function ns( $cat, $class ) {
	return 'SiteCore\Classes\\' . $cat . '\\' . $class;
};

/**
 * File path
 *
 * Works for subdirectories of the `includes/classes` directory.
 * Files require the `class-` prefix.
 *
 * @since  1.0.0
 * @param  string $dir
 * @param  string $file
 * @return string Returns the file path in classes subdirectory.
 */
function f( $dir, $file ) {
	return SCP_PATH . 'includes/classes/' . $dir .'/class-' . $file;
};

/**
 * Core classes
 *
 * @since  1.0.0
 * @return void
 */
function core() {

	$classes = [
		ns( 'Core', 'Editor_Options' )            => f( 'core', 'editor-options.php' ),
		ns( 'Core', 'Register_Type' )             => f( 'core', 'register-type.php' ),
		ns( 'Core', 'Register_Sample_Type' )      => f( 'core', 'register-sample-type.php' ),
		ns( 'Core', 'Register_Admin' )            => f( 'core', 'register-admin.php' ),
		ns( 'Core', 'Register_Tax' )              => f( 'core', 'register-tax.php' ),
		ns( 'Core', 'Register_Sample_Tax' )       => f( 'core', 'register-sample-tax.php' ),
		ns( 'Core', 'Register_Media_Type' )       => f( 'core', 'register-media-type.php' ),
		ns( 'Core', 'Register_Shortcode' )        => f( 'core', 'register-shortcode.php' ),
		ns( 'Core', 'Register_Sample_Shortcode' ) => f( 'core', 'register-sample-shortcode.php' ),
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Settings classes
 *
 * @since  1.0.0
 * @return void
 */
function settings() {

	$classes = [
		ns( 'Settings', 'Settings_Sections' )                => f( 'settings', 'settings-sections.php' ),
		ns( 'Settings', 'Settings_Sections_Sample' )         => f( 'settings', 'settings-sections-sample.php' ),
		ns( 'Settings', 'Settings_Sections_Content' )        => f( 'settings', 'settings-sections-content.php' ),
		ns( 'Settings', 'Settings_Sections_Admin' )          => f( 'settings', 'settings-sections-admin.php' ),
		ns( 'Settings', 'Settings_Sections_Network_Admin' )  => f( 'settings', 'settings-sections-network-admin.php' ),
		ns( 'Settings', 'Settings_Sections_Developer' )      => f( 'settings', 'settings-sections-developer.php' ),
		ns( 'Settings', 'Settings_Fields' )                  => f( 'settings', 'settings-fields.php' ),
		ns( 'Settings', 'Settings_Fields_Sample' )           => f( 'settings', 'settings-fields-sample.php' ),
		ns( 'Settings', 'Settings_Fields_Content' )    => f( 'settings', 'settings-fields-content.php' ),
		ns( 'Settings', 'Settings_Fields_Meta_Tags' )    => f( 'settings', 'settings-fields-meta-tags.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Dashboard' )  => f( 'settings', 'settings-fields-admin-dashboard.php' ),
		ns( 'Settings', 'Settings_Fields_Network_Admin_Dashboard' ) => f( 'settings', 'settings-fields-network-admin-dashboard.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Footer' )       => f( 'settings', 'settings-fields-admin-footer.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Forms' )        => f( 'settings', 'settings-fields-admin-forms.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Header' )       => f( 'settings', 'settings-fields-admin-header.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Menu' )         => f( 'settings', 'settings-fields-admin-menu.php' ),
		ns( 'Settings', 'Settings_Fields_Network_Admin_Menu' ) => f( 'settings', 'settings-fields-network-admin-menu.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Toolbar' )      => f( 'settings', 'settings-fields-admin-toolbar.php' ),
		ns( 'Settings', 'Settings_Fields_Admin_Users' )        => f( 'settings', 'settings-fields-admin-users.php' ),
		ns( 'Settings', 'Settings_Fields_Developer' )          => f( 'settings', 'settings-fields-developer.php' ),
		ns( 'Settings', 'Settings_Fields_Developer_Content' )  => f( 'settings', 'settings-fields-developer-content.php' ),
		ns( 'Settings', 'Settings_Fields_Developer_Users' )    => f( 'settings', 'settings-fields-developer-users.php' ),
		ns( 'Settings', 'Settings_Fields_Media_Images' )       => f( 'settings', 'settings-fields-media-images.php' ),
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Tools classes
 *
 * @since  1.0.0
 * @return void
 */
function tools() {

	$classes = [

		// Load `ACF_Admin_Tool` prior to `Content_Import_Export`.
		'ACF_Admin_Tool' => SCP_PATH . 'includes/vendor/acf/includes/admin/tools/class-acf-admin-tool.php',

		ns( 'Tools', 'Content_Import_Export' ) => f( 'tools', 'content-import-export.php' ),
		ns( 'Tools', 'Customizer_Reset' )      => f( 'tools', 'customizer-reset.php' ),
		ns( 'Tools', 'Disable_User_Toolbar' )  => f( 'tools', 'disable-user-toolbar.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Media classes
 *
 * @since  1.0.0
 * @return void
 */
function media() {

	$classes = [];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Users classes
 *
 * @since  1.0.0
 * @return void
 */
function users() {

	$classes = [
		ns( 'Users', 'User_Avatars' ) => f( 'users', 'user-avatars.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Vendor classes
 *
 * @since  1.0.0
 * @return void
 */
function vendor() {

	$classes = [
		ns( 'Vendor', 'Plugin' )             => f( 'vendor', 'plugin.php' ),
		ns( 'Vendor', 'Plugin_Sample' )      => f( 'vendor', 'plugin-sample.php' ),
		ns( 'Vendor', 'Plugin_ACF' )         => f( 'vendor', 'plugin-acf.php' ),
		ns( 'Vendor', 'Plugin_ACFE' )        => f( 'vendor', 'plugin-acfe.php' ),
		ns( 'Vendor', 'ACF_Nav_Menu_Field' ) => f( 'vendor', 'acf-nav-menu-field.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Backend classes
 *
 * @since  1.0.0
 * @return void
 */
function admin() {

	$classes = [
		ns( 'Admin', 'Add_Page' )                => f( 'backend', 'add-page.php' ),
		ns( 'Admin', 'Sample_Page' )             => f( 'backend', 'sample-page.php' ),
		ns( 'Admin', 'Sample_Network_Page' )     => f( 'backend', 'sample-network-page.php' ),
		ns( 'Admin', 'Sample_Subpage' )          => f( 'backend', 'sample-subpage.php' ),
		ns( 'Admin', 'Sample_Network_Subpage' )  => f( 'backend', 'sample-network-subpage.php' ),
		ns( 'Admin', 'Dashboard_Tabs_ACF' )      => f( 'backend', 'dashboard-tabs-acf.php' ),
		ns( 'Admin', 'Admin_Settings_Page' )     => f( 'backend', 'admin-settings-page.php' ),
		ns( 'Admin', 'Network_Admin_Settings_Page' ) => f( 'backend', 'network-admin-settings-page.php' ),
		ns( 'Admin', 'Developer_Settings_Page' ) => f( 'backend', 'developer-settings-page.php' ),
		ns( 'Admin', 'Content_Settings_Page' )   => f( 'backend', 'content-settings-page.php' ),
		ns( 'Admin', 'Manage_Website_Page' )     => f( 'backend', 'manage-website-page.php' ),
		ns( 'Admin', 'Sample_ACF_Options' )      => f( 'backend', 'sample-acf-options.php' ),
		ns( 'Admin', 'Sample_ACF_Suboptions' )   => f( 'backend', 'sample-acf-suboptions.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Frontend classes
 *
 * @since  1.0.0
 * @return void
 */
function front() {

	$classes = [
		ns( 'Front', 'Title_Filter' )   => f( 'frontend', 'title-filter.php' ),
		ns( 'Front', 'Title_Sample' )   => f( 'frontend', 'title-sample.php' ),
		ns( 'Front', 'Content_Filter' ) => f( 'frontend', 'content-filter.php' ),
		ns( 'Front', 'Content_Sample' ) => f( 'frontend', 'content-sample.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

/**
 * Widgets classes
 *
 * @since  1.0.0
 * @return void
 */
function widgets() {

	$classes = [
		ns( 'Widgets', 'Add_Widget' )    => f( 'widgets', 'add-widget.php' ),
		ns( 'Widgets', 'Sample_Widget' ) => f( 'widgets', 'sample-widget.php' )
	];
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}

// Stop here for demo.
return;

/**
 * Autoload demo
 *
 * The namespace and file path function are not
 * required in the array of classes to load.
 *
 * In this demo function, various combinations
 * are used in the array.
 *
 * @since  1.0.0
 * @return void
 */
function demo() {

	/**
	 * All key => value examples would work together in
	 * the array if these files actually existed.
	 */
	$classes = [

		// Both functions used.
		ns( 'Demo', 'Demo_One' ) => f( 'demo', 'demo-one.php' ),

		// Full namespace & class name, path function.
		'SiteCore\Classes\Demo\Demo_Two' => f( 'demo', 'demo-two.php' ),

		// Namespace function, full path.
		ns( 'Demo', 'Demo_Three' ) => SCP_PATH . 'includes/classes/demo/class-demo-three.php',

		// Fully custom.
		'SiteCore\Custom\Namespace\Demo_Four' => SCP_PATH . 'includes/custom/directory/class-demo-four.php'
	];

	// Autoload when in use.
	spl_autoload_register(
		function ( string $class ) use ( $classes ) {
			if ( isset( $classes[ $class ] ) ) {
				require $classes[ $class ];
			}
		}
	);
}
