<?php
/**
 * Plugin configuration
 *
 * The constants defined here do not override any default bavavior
 * or default user interfaces. However, the corresponding behavior
 * can be overridden in the system config file (e.g. `wp-config`,
 * `app-config` ).
 *
 * The reason for using constants in the config file rather than
 * in a settings file is to prevent site administrators wrongly
 * or incorrectly configuring the site built by developers.
 *
 * @package    Site_Core
 * @subpackage Configuration
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Constant: Plugin version
 *
 * Keeping the version at 1.0.0 as this is a starter plugin but
 * you may want to start counting as you develop for your use case.
 *
 * Remember to find and replace the `@version x.x.x` in docblocks.
 *
 * @since 1.0.0
 * @var   string The latest plugin version.
 */
define( 'SCP_VERSION', '1.0.0' );

/**
 * Constant: Required PHP version
 *
 * Used instead of the minimum PHP version
 * in the plugin header.
 *
 * @see activate/classes/class-activate.php
 *
 * @since 1.0.0
 * @var   string The minimum required PHP version.
 */
define( 'SCP_PHP_VERSION', '7.4' );

/**
 * Constant: Text domain
 *
 * Remember to freplace in the plugin header above.
 *
 * @since 1.0.0
 * @var   string The text domain of the plugin.
 */
define( 'SCP_DOMAIN', 'sitecore' );

/**
 * Constant: Plugin folder path
 *
 * @since 1.0.0
 * @var   string The filesystem directory path (with trailing slash)
 *               for the plugin __FILE__ passed in.
 */
define( 'SCP_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Constant: Plugin folder URL
 *
 * @since 1.0.0
 * @var   string The URL directory path (with trailing slash)
 *               for the plugin __FILE__ passed in.
 */
define( 'SCP_URL', plugin_dir_url(__FILE__ ) );

/**
 * Constant: Plugin configuration.
 *
 * @since 1.0.0
 * @var   array Plugin identification, support, settintgs.
 */
if ( ! defined( 'SCP_CONFIG' ) ) {

	define( 'SCP_CONFIG', [

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var   string The latest plugin version.
		 */
		'version' => SCP_VERSION,

		/**
		 * Required PHP version
		 *
		 * @since 1.0.0
		 * @var   string The minimum required PHP version.
		 */
		'php_version' => SCP_PHP_VERSION,

		/**
		 * Text domain
		 *
		 * @since 1.0.0
		 * @var   string The text domain of the plugin.
		 */
		'domain' => SCP_DOMAIN,

		/**
		 * Plugin name
		 *
		 * Remember to replace in the plugin header.
		 *
		 * @since 1.0.0
		 * @var   string The name of the plugin.
		 */
		'name' => __( 'Site Core', SCP_DOMAIN ),

		/**
		 * Developer name
		 *
		 * @since 1.0.0
		 * @var   string The name of the developer/agency.
		 */
		'dev_name' => __( 'Controlled Chaos', SCP_DOMAIN ),

		/**
		 * Developer URL
		 *
		 * @since 1.0.0
		 * @var   string The URL of the developer/agency.
		 */
		'dev_url' => esc_url( 'https://ccdzine.com/' ),

		/**
		 * Developer email
		 *
		 * @since 1.0.0
		 * @var   string The URL of the developer/agency.
		 */
		'dev_email' => sanitize_email( 'greg@ccdzine.com' ),

		/**
		 * Plugin URL
		 *
		 * @since 1.0.0
		 * @var   string The URL of the plugin.
		 */
		'plugin_url' => esc_url( 'https://github.com/ControlledChaos/sitecore' ),

		/**
		 * Universal slug
		 *
		 * This URL slug is used for various plugin admin & settings pages.
		 *
		 * The prefix will change in your search & replace in renaming the plugin.
		 * Change the second part of the define(), here as 'site-core',
		 * to your preferred page slug.
		 *
		 * @since 1.0.0
		 * @var   string The URL slug of the admin pages.
		 */
		'admin_slug' => 'site-core',

		/**
		 * Disable Customizer
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow the Customizer.
		 */
		'customizer' => true,

		/**
		 * User admin color picker
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow admin color pickers.
		 */
		'color_picker' => true
	] );
}

/**
 * Plugin name
 *
 * @since 1.0.0
 * @var   string The name of the plugin.
 */
if ( ! defined( 'SCP_NAME' ) ) {
	define( 'SCP_NAME', SCP_CONFIG['name'] );
}

/**
 * Developer name
 *
 * @since 1.0.0
 * @var   string The name of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_NAME' ) ) {
	define( 'SCP_DEV_NAME', SCP_CONFIG['dev_name'] );
}

/**
 * Developer URL
 *
 * @since 1.0.0
 * @var   string The URL of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_URL' ) ) {
	define( 'SCP_DEV_URL', SCP_CONFIG['dev_url'] );
}

/**
 * Developer email
 *
 * @since 1.0.0
 * @var   string The URL of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_EMAIL' ) ) {
	define( 'SCP_DEV_EMAIL', SCP_CONFIG['dev_email'] );
}

/**
 * Plugin URL
 *
 * @since 1.0.0
 * @var   string The URL of the plugin.
 */
if ( ! defined( 'SCP_PLUGIN_URL' ) ) {
	define( 'SCP_PLUGIN_URL', SCP_CONFIG['plugin_url'] );
}

/**
 * Disable Customizer
 *
 * @since 1.0.0
 * @var   boolean Whether to allow the Customizer.
 */
if ( ! defined( 'SCP_ALLOW_CUSTOMIZER' ) ) {
	define( 'SCP_ALLOW_CUSTOMIZER', SCP_CONFIG['customizer'] );
}

/**
 * User admin color picker
 *
 * @since 1.0.0
 * @var   boolean Whether to allow admin color pickers.
 */
if ( ! defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) ) {
	define( 'SCP_ALLOW_ADMIN_COLOR_PICKER', SCP_CONFIG['color_picker'] );
}
