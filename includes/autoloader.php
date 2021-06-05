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

	// Base class.
	SCP_CLASS_NS . '\Base' => SCP_CLASS['general'] . 'base.php',

	// Core classes.
	SCP_CLASS_NS . '\Core\Editor_Options'     => SCP_CLASS['core'] . 'editor-options.php',
	SCP_CLASS_NS . '\Core\Type_Tax'           => SCP_CLASS['core'] . 'type-tax.php',
	SCP_CLASS_NS . '\Core\Register_Type'      => SCP_CLASS['core'] . 'register-type.php',
	// SCP_CLASS_NS . '\Core\Register_Sample_Type' => SCP_CLASS['core'] . 'register-sample-type.php',
	SCP_CLASS_NS . '\Core\Register_Admin'     => SCP_CLASS['core'] . 'register-admin.php',
	SCP_CLASS_NS . '\Core\Register_Site_Help' => SCP_CLASS['core'] . 'register-site-help.php',
	SCP_CLASS_NS . '\Core\Register_Tax'       => SCP_CLASS['core'] . 'register-tax.php',
	// SCP_CLASS_NS . '\Core\Register_Sample_Tax' => SCP_CLASS['core'] . 'register-sample-tax.php',
	SCP_CLASS_NS . '\Core\Types_Taxes_Order'  => SCP_CLASS['core'] . 'types-taxes-order.php',
	SCP_CLASS_NS . '\Core\Taxonomy_Templates' => SCP_CLASS['core'] . 'taxonomy-templates.php',
	SCP_CLASS_NS . '\Core\Remove_Blog'        => SCP_CLASS['core'] . 'remove-blog.php',
	SCP_CLASS_NS . '\Core\Remove_Customizer'  => SCP_CLASS['core'] . 'remove-customizer.php',

	// Settings classes.
	SCP_CLASS_NS . '\Settings\Settings'     => SCP_CLASS['settings'] . 'settings.php',

	// Tools classes.
	SCP_CLASS_NS . '\Tools\Tools'            => SCP_CLASS['tools'] . 'tools.php',
	SCP_CLASS_NS . '\Tools\Disable_FloC'     => SCP_CLASS['tools'] . 'disable-google-floc.php',
	SCP_CLASS_NS . '\Tools\RTL_Test'         => SCP_CLASS['tools'] . 'rtl-test.php',
	SCP_CLASS_NS . '\Tools\Customizer_Reset' => SCP_CLASS['tools'] . 'customizer-reset.php',

	// Media classes.
	SCP_CLASS_NS . '\Media\Media'               => SCP_CLASS['media'] . 'media.php',
	SCP_CLASS_NS . '\Media\Register_Media_Type' => SCP_CLASS['media'] . 'register-media-type.php',

	// Users classes.
	SCP_CLASS_NS . '\Users\Users'           => SCP_CLASS['users'] . 'users.php',
	SCP_CLASS_NS . '\Users\User_Roles_Caps' => SCP_CLASS['users'] . 'user-roles-caps.php',
	SCP_CLASS_NS . '\Users\User_Toolbar'    => SCP_CLASS['users'] . 'user-toolbar.php',
	SCP_CLASS_NS . '\Users\User_Avatars'    => SCP_CLASS['users'] . 'user-avatars.php',

	// Vendor classes.
	SCP_CLASS_NS . '\Vendor\Plugin'        => SCP_CLASS['vendor'] . 'plugin.php',
	// SCP_CLASS_NS . '\Vendor\Sample_Plugin' => SCP_CLASS['vendor'] . 'sample-plugin.php',
	SCP_CLASS_NS . '\Vendor\ACF'           => SCP_CLASS['vendor'] . 'acf.php',
	SCP_CLASS_NS . '\Vendor\ACFE'          => SCP_CLASS['vendor'] . 'acfe.php',
	SCP_CLASS_NS . '\Vendor\ACF_Columns'   => SCP_CLASS['vendor'] . 'acf-columns.php',
	SCP_CLASS_NS . '\Vendor\Add_ACF_Options'       => SCP_CLASS['vendor'] . 'add-acf-options.php',
	SCP_CLASS_NS . '\Vendor\Add_ACF_Suboptions'    => SCP_CLASS['vendor'] . 'add-acf-suboptions.php',
	// SCP_CLASS_NS . '\Vendor\Sample_ACF_Options'    => SCP_CLASS['vendor'] . 'sample-acf-options.php',
	// SCP_CLASS_NS . '\Vendor\Sample_ACF_Suboptions' => SCP_CLASS['vendor'] . 'sample-acf-suboptions.php',

	// Backend/admin classes,
	SCP_CLASS_NS . '\Admin\Admin'                   => SCP_CLASS['admin'] . 'admin.php',
	SCP_CLASS_NS . '\Admin\Add_Page'                => SCP_CLASS['admin'] . 'add-page.php',
	SCP_CLASS_NS . '\Admin\Add_Subpage'             => SCP_CLASS['admin'] . 'add-subpage.php',
	SCP_CLASS_NS . '\Admin\Admin_Settings_Page'     => SCP_CLASS['admin'] . 'admin-settings-page.php',
	SCP_CLASS_NS . '\Admin\Add_Settings_Page'       => SCP_CLASS['admin'] . 'add-settings-page.php',
	SCP_CLASS_NS . '\Admin\Admin_ACF_Settings_Page' => SCP_CLASS['admin'] . 'admin-acf-settings-page.php',
	SCP_CLASS_NS . '\Admin\Content_Settings'        => SCP_CLASS['admin'] . 'content-settings.php',
	SCP_CLASS_NS . '\Admin\Manage_Website_Page'     => SCP_CLASS['admin'] . 'manage-website-page.php',
	SCP_CLASS_NS . '\Admin\User_Colors'             => SCP_CLASS['admin'] . 'user-colors.php',
	SCP_CLASS_NS . '\Admin\Dashboard'               => SCP_CLASS['admin'] . 'dashboard.php',
	SCP_CLASS_NS . '\Admin\Posts_List_Table'        => SCP_CLASS['admin'] . 'posts-list-table.php',

	// Frontend classes.
	SCP_CLASS_NS . '\Front\Frontend'       => SCP_CLASS['front'] . 'frontend.php',
	SCP_CLASS_NS . '\Front\Title_Filter'   => SCP_CLASS['front'] . 'title-filter.php',
	SCP_CLASS_NS . '\Front\Content_Filter' => SCP_CLASS['front'] . 'content-filter.php',
	SCP_CLASS_NS . '\Front\Meta\Meta_Data' => SCP_CLASS['front'] . 'meta-data.php',
	SCP_CLASS_NS . '\Front\Meta\Meta_Tags' => SCP_CLASS['front'] . 'meta-tags.php'

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
