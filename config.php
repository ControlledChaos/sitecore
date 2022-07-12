<?php
/**
 * Plugin configuration
 *
 * The constants defined here do not override any default behavior
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

// Alias namespaces.
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Constant: Minimum PHP version
 *
 * @since 1.0.0
 * @var   string The minimum required PHP version.
 */
define( 'SCP_MIN_PHP_VERSION', '7.4' );

/**
 * Function: Minimum PHP version
 *
 * Checks the PHP version sunning on the current host
 * against the minimum version required by this plugin.
 *
 * @since  1.0.0
 * @return boolean Returns false if the minimum is not met.
 */
function min_php_version() {

	if ( version_compare( phpversion(), SCP_MIN_PHP_VERSION, '<' ) ) {
		return false;
	}
	return true;
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
 * Plugin name
 *
 * @since 1.0.0
 * @var   string The name of the plugin.
 */
if ( ! defined( 'SCP_NAME' ) ) {
	define( 'SCP_NAME', __( 'Site Core', 'sitecore' ) );
}

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
define( 'SCP_URL', plugin_dir_url( __FILE__ ) );

/**
 * PHP version check
 *
 * Stop here if the minimum PHP version is not met.
 * The following array definitions wi break sites
 * running older PHP versions.
 *
 * @since  1.0.0
 * @return void
 */
if ( ! min_php_version() ) {
	return;
}

/**
 * Constant: Plugin configuration.
 *
 * @since 1.0.0
 * @var   array Plugin identification, support, settings.
 */
if ( ! defined( 'SCP_CONFIG' ) ) {

	define( 'SCP_CONFIG', [

		/**
		 * Plugin name
		 *
		 * Remember to replace in the plugin header.
		 *
		 * @since 1.0.0
		 * @var   string The name of the plugin.
		 */
		'name' => SCP_NAME,

		/**
		 * Developer name
		 *
		 * @since 1.0.0
		 * @var   string The name of the developer/agency.
		 */
		'dev_name' => __( 'Controlled Chaos', 'sitecore' ),

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
		 * Posts content type
		 *
		 * The nature of the default posts (e.g. blog, news).
		 *
		 * @since 1.0.0
		 * @var   string The name of the developer/agency.
		 */
		'posts_content' => 'blog',

		/**
		 * Allow custom dashboard
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow custom dashboard.
		 */
		'dashboard' => false,

		/**
		 * Allow Site Health
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow the Site Health feature.
		 */
		'site_health' => false,

		/**
		 * Allow block widgets
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow block widgets.
		 */
		'block_widgets' => true,

		/**
		 * Allow links manager
		 *
		 * @since 1.0.0
		 * @var   boolean Whether to allow the links manager feature.
		 */
		'links_manager' => false,

		/**
		 * Allow Customizer
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
 * Posts content type
 *
 * @since 1.0.0
 * @var   string The nature of the default posts (e.g. blog, news).
 */
if ( ! defined( 'SCP_POSTS_CONTENT_TYPE' ) ) {
	define( 'SCP_POSTS_CONTENT_TYPE', SCP_CONFIG['posts_content'] );
}

/**
 * Allow custom dashboard
 *
 * @since 1.0.0
 * @var   boolean Whether to allow the custom dashboard.
 */
if ( ! defined( 'SCP_USE_CUSTOM_DASHBOARD' ) ) {
	define( 'SCP_USE_CUSTOM_DASHBOARD', SCP_CONFIG['dashboard'] );
}

/**
 * Allow Site Health
 *
 * @since 1.0.0
 * @var   boolean Whether to allow the Site Health feature.
 */
if ( ! defined( 'SCP_ALLOW_SITE_HEALTH' ) ) {
	define( 'SCP_ALLOW_SITE_HEALTH', SCP_CONFIG['site_health'] );
}

/**
 * Allow block widgets
 *
 * @since 1.0.0
 * @var   boolean Whether to allow block widgets.
 */
if ( ! defined( 'SCP_ALLOW_BLOCK_WIDGETS' ) ) {
	define( 'SCP_ALLOW_BLOCK_WIDGETS', SCP_CONFIG['block_widgets'] );
}

/**
 * Allow links manager
 *
 * @since 1.0.0
 * @var   boolean Whether to allow the links manager feature.
 */
if ( ! defined( 'SCP_ALLOW_LINKS_MANAGER' ) ) {
	define( 'SCP_ALLOW_LINKS_MANAGER', SCP_CONFIG['links_manager'] );
}

/**
 * Allow Customizer
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
