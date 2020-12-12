<?php
/**
 * Register plugin classes
 *
 * The autoloader registers plugin classes for later use.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   General
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Class files
 *
 * Defines the class directories and file prefixes.
 *
 * @since 1.0.0
 * @var   array Defines an array of class file paths.
 */
define( 'SCP_CLASS', [
	'core'    => SCP_PATH . 'includes/classes/core/class-',
	'tools'   => SCP_PATH . 'includes/classes/tools/class-',
	'media'   => SCP_PATH . 'includes/classes/media/class-',
	'users'   => SCP_PATH . 'includes/classes/users/class-',
	'vendor'  => SCP_PATH . 'includes/classes/vendor/class-',
	'admin'   => SCP_PATH . 'includes/classes/backend/class-',
	'front'   => SCP_PATH . 'includes/classes/frontend/class-',
	'general' => SCP_PATH . 'includes/classes/class-',
] );

/**
 * Array of classes to register
 *
 * When you add new classes to your version of this plugin you may
 * add them to the following array rather than requiring the file
 * elsewhere. Be sure to include the precise namespace.
 *
 * @since 1.0.0
 * @var   array Defines an array of class files to register.
 */
define( 'SCP_CLASSES', [

	// Core classes.
	'SiteCore\Classes\Core\Editor_Options'      => SCP_CLASS['core'] . 'editor-options.php',
	'SiteCore\Classes\Core\Type_Tax'            => SCP_CLASS['core'] . 'type-tax.php',
	'SiteCore\Classes\Core\Register_Type'       => SCP_CLASS['core'] . 'register-type.php',
	'SiteCore\Classes\Core\Register_Tax'        => SCP_CLASS['core'] . 'register-tax.php',
	'SiteCore\Classes\Core\Register_Media_Type' => SCP_CLASS['core'] . 'register-media-type.php',

	// Media classes.
	'SiteCore\Classes\Media\Media'       => SCP_CLASS['media'] . 'media.php',

	// Vendor classes.
	'SiteCore\Classes\Vendor\Plugins' => SCP_CLASS['vendor'] . 'plugins.php',
	'SiteCore\Classes\Vendor\ACF'     => SCP_CLASS['vendor'] . 'acf.php',

	// Backend/admin classes,
	'SiteCore\Classes\Admin\Admin'               => SCP_CLASS['admin'] . 'admin.php',
	'SiteCore\Classes\Admin\Add_Menu_Page'       => SCP_CLASS['admin'] . 'add-menu-page.php',
	'SiteCore\Classes\Admin\Add_Submenu_Page'    => SCP_CLASS['admin'] . 'add-submenu-page.php',
	'SiteCore\Classes\Admin\Manage_Website_Page' => SCP_CLASS['admin'] . 'manage-website-page.php',
	'SiteCore\Classes\Admin\Dashboard'           => SCP_CLASS['admin'] . 'dashboard.php',

	// Frontend classes.
	'SiteCore\Classes\Front\Frontend' => SCP_CLASS['front'] . 'frontend.php',

	// General/miscellaneos classes.
	'SiteCore\Classes\User_Toolbar' => SCP_CLASS['general'] . 'user-toolbar.php'
] );

/**
 * Autoload class files
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
spl_autoload_register(
	function ( string $class ) {
		if ( isset( SCP_CLASSES[ $class ] ) ) {
			require SCP_CLASSES[ $class ];
		}
	}
);
