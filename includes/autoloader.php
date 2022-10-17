<?php
/**
 * Register plugin classes
 *
 * The autoloader registers plugin classes for later use.
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Classes
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
	'core'     => SCP_PATH . 'includes/classes/core/class-',
	'settings' => SCP_PATH . 'includes/classes/settings/class-',
	'tools'    => SCP_PATH . 'includes/classes/tools/class-',
	'media'    => SCP_PATH . 'includes/classes/media/class-',
	'users'    => SCP_PATH . 'includes/classes/users/class-',
	'vendor'   => SCP_PATH . 'includes/classes/vendor/class-',
	'admin'    => SCP_PATH . 'includes/classes/backend/class-',
	'front'    => SCP_PATH . 'includes/classes/frontend/class-',
	'widgets'  => SCP_PATH . 'includes/classes/widgets/class-',
	'general'  => SCP_PATH . 'includes/classes/class-',
] );

/**
 * Classes namespace
 *
 * @since 1.0.0
 * @var   string Defines the namespace of class files.
 */
define( 'SCP_CLASS_NS', __NAMESPACE__ . '\Classes' );

/**
 * Array of classes to register
 *
 * When you add new classes to your version of this plugin you may
 * add them to the following array rather than requiring the file
 * elsewhere. Be sure to include the precise namespace.
 *
 * SAMPLES: Uncomment sample classes to load them.
 *
 * @since 1.0.0
 * @var   array Defines an array of class files to register.
 */
define( 'SCP_CLASSES', [

	// Core classes.
	SCP_CLASS_NS . '\Core\Editor_Options'       => SCP_CLASS['core'] . 'editor-options.php',
	SCP_CLASS_NS . '\Core\Register_Type'        => SCP_CLASS['core'] . 'register-type.php',
	SCP_CLASS_NS . '\Core\Register_Sample_Type' => SCP_CLASS['core'] . 'register-sample-type.php',
	SCP_CLASS_NS . '\Core\Register_Admin'       => SCP_CLASS['core'] . 'register-admin.php',
	SCP_CLASS_NS . '\Core\Register_Site_Help'   => SCP_CLASS['core'] . 'register-site-help.php',
	SCP_CLASS_NS . '\Core\Register_Tax'         => SCP_CLASS['core'] . 'register-tax.php',
	SCP_CLASS_NS . '\Core\Register_Sample_Tax'  => SCP_CLASS['core'] . 'register-sample-tax.php',
	SCP_CLASS_NS . '\Core\Types_Taxes_Order'    => SCP_CLASS['core'] . 'types-taxes-order.php',
	SCP_CLASS_NS . '\Core\Remove_Blog'          => SCP_CLASS['core'] . 'remove-blog.php',
	SCP_CLASS_NS . '\Core\Remove_Customizer'    => SCP_CLASS['core'] . 'remove-customizer.php',

	// Settings classes.
	SCP_CLASS_NS . '\Settings\Settings' => SCP_CLASS['settings'] . 'settings.php',

	// Tools classes.
	SCP_CLASS_NS . '\Tools\Customizer_Reset' => SCP_CLASS['tools'] . 'customizer-reset.php',

	// Media classes.
	SCP_CLASS_NS . '\Media\Register_Media_Type' => SCP_CLASS['media'] . 'register-media-type.php',

	// Users classes.
	SCP_CLASS_NS . '\Users\User_Avatars'    => SCP_CLASS['users'] . 'user-avatars.php',

	// Vendor classes.
	SCP_CLASS_NS . '\Vendor\Plugin'        => SCP_CLASS['vendor'] . 'plugin.php',
	SCP_CLASS_NS . '\Vendor\Plugin_Sample' => SCP_CLASS['vendor'] . 'plugin-sample.php',
	SCP_CLASS_NS . '\Vendor\Plugin_ACF'    => SCP_CLASS['vendor'] . 'plugin-acf.php',
	SCP_CLASS_NS . '\Vendor\Plugin_ACFE'   => SCP_CLASS['vendor'] . 'plugin-acfe.php',
	SCP_CLASS_NS . '\Vendor\ACF_Columns'   => SCP_CLASS['vendor'] . 'acf-columns.php',
	SCP_CLASS_NS . '\Vendor\Add_ACF_Options'    => SCP_CLASS['vendor'] . 'add-acf-options.php',
	SCP_CLASS_NS . '\Vendor\Add_ACF_Suboptions' => SCP_CLASS['vendor'] . 'add-acf-suboptions.php',
	SCP_CLASS_NS . '\Vendor\ACF_Manage_Site'    => SCP_CLASS['vendor'] . 'acf-manage-site.php',
	SCP_CLASS_NS . '\Vendor\Sample_ACF_Options'    => SCP_CLASS['vendor'] . 'sample-acf-options.php',
	SCP_CLASS_NS . '\Vendor\Sample_ACF_Suboptions' => SCP_CLASS['vendor'] . 'sample-acf-suboptions.php',

	// Backend/admin classes,
	SCP_CLASS_NS . '\Admin\Add_Page'                => SCP_CLASS['admin'] . 'add-page.php',
	SCP_CLASS_NS . '\Admin\Sample_Page'             => SCP_CLASS['admin'] . 'sample-page.php',
	SCP_CLASS_NS . '\Admin\Sample_Subpage'          => SCP_CLASS['admin'] . 'sample-subpage.php',
	SCP_CLASS_NS . '\Admin\Admin_Settings_Page'     => SCP_CLASS['admin'] . 'admin-settings-page.php',
	SCP_CLASS_NS . '\Admin\Admin_ACF_Settings_Page' => SCP_CLASS['admin'] . 'admin-acf-settings-page.php',
	SCP_CLASS_NS . '\Admin\Content_Settings'        => SCP_CLASS['admin'] . 'content-settings.php',
	SCP_CLASS_NS . '\Admin\Manage_Website_Page'     => SCP_CLASS['admin'] . 'manage-website-page.php',

	// Frontend classes.
	SCP_CLASS_NS . '\Front\Title_Filter'     => SCP_CLASS['front'] . 'title-filter.php',
	SCP_CLASS_NS . '\Front\Content_Filter'   => SCP_CLASS['front'] . 'content-filter.php',
	SCP_CLASS_NS . '\Front\Content_Sample'   => SCP_CLASS['front'] . 'content-sample.php',

	// Widget classes.
	SCP_CLASS_NS . '\Widgets\Add_Widget'    => SCP_CLASS['widgets'] . 'add-widget.php',
	SCP_CLASS_NS . '\Widgets\Sample_Widget' => SCP_CLASS['widgets'] . 'sample-widget.php'


	// General/miscellaneous classes.

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
