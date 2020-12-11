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

// Define the class directories and file prefixes.
define( 'SCP_CLASS_PATH',    SCP_PATH . 'includes/classes/' );
define( 'SCP_CORE_CLASS',    SCP_CLASS_PATH . 'core/class-' );
define( 'SCP_MEDIA_CLASS',   SCP_CLASS_PATH . 'media/class-' );
define( 'SCP_VENDOR_CLASS',  SCP_CLASS_PATH . 'vendor/class-' );
define( 'SCP_ADMIN_CLASS',   SCP_CLASS_PATH . 'backend/class-' );
define( 'SCP_FRONT_CLASS',   SCP_CLASS_PATH . 'frontend/class-' );
define( 'SCP_GENERAL_CLASS', SCP_CLASS_PATH . 'class-' );

/**
 * Array of classes to register
 *
 * When you add new classes to your version of this plugin you may
 * add them to the following array rather than requiring the file
 * elsewhere. Be sure to include the precise namespace.
 */
const SCP_CLASSES = [

	// Core classes.
	'SiteCore\Classes\Core\Editor_Options'      => SCP_CORE_CLASS . 'editor-options.php',
	'SiteCore\Classes\Core\Type_Tax'            => SCP_CORE_CLASS . 'type-tax.php',
	'SiteCore\Classes\Core\Register_Type'       => SCP_CORE_CLASS . 'register-type.php',
	'SiteCore\Classes\Core\Register_Sample_Tax' => SCP_CORE_CLASS . 'register-sample-tax.php',
	'SiteCore\Classes\Core\Register_Tax'        => SCP_CORE_CLASS . 'register-tax.php',
	'SiteCore\Classes\Core\Register_Media_Type' => SCP_CORE_CLASS . 'register-media-type.php',

	// Media classes.
	'SiteCore\Classes\Media\Media' => SCP_MEDIA_CLASS . 'media.php',

	// Vendor classes.
	'SiteCore\Classes\Vendor\Plugins' => SCP_VENDOR_CLASS . 'plugins.php',
	'SiteCore\Classes\Vendor\ACF'     => SCP_VENDOR_CLASS . 'acf.php',

	// Backend/admin classes,
	'SiteCore\Classes\Admin\Admin'        => SCP_ADMIN_CLASS . 'admin.php',
	'SiteCore\Classes\Admin\Admin_Screen' => SCP_ADMIN_CLASS . 'admin-screen.php',
	'SiteCore\Classes\Admin\Dashboard'    => SCP_ADMIN_CLASS . 'dashboard.php',

	// Frontend classes.
	'SiteCore\Classes\Front\Frontend' => SCP_FRONT_CLASS . 'frontend.php',

	// General/miscellaneos classes.
	'SiteCore\Classes\User_Toolbar' => SCP_GENERAL_CLASS . 'user-toolbar.php'
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( SCP_CLASSES[ $classname ] ) ) {
			require SCP_CLASSES[ $classname ];
		}
	}
);
