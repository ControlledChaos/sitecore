<?php
/**
 * Constants
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
 * @subpackage Includes
 * @category   Configuration
 * @since      1.0.0
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Plugin name
 *
 * Remember to replace in the plugin header.
 *
 * @since 1.0.0
 * @var   string The name of the plugin.
 */
if ( ! defined( 'SCP_NAME' ) ) {
	define( 'SCP_NAME', esc_html__( 'Site Core', SCP_DOMAIN ) );
}

/**
 * Developer name
 *
 * @since 1.0.0
 * @var   string The name of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_NAME' ) ) {
	define( 'SCP_DEV_NAME', 'Controlled Chaos' );
}

/**
 * Developer URL
 *
 * @since 1.0.0
 * @var   string The URL of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_URL' ) ) {
	define( 'SCP_DEV_URL', 'https://ccdzine.com/' );
}

/**
 * Developer email
 *
 * @since 1.0.0
 * @var   string The URL of the developer/agency.
 */
if ( ! defined( 'SCP_DEV_EMAIL' ) ) {
	define( 'SCP_DEV_EMAIL', 'greg@ccdzine.com' );
}

/**
 * Plugin URL
 *
 * @since 1.0.0
 * @var   string The URL of the plugin.
 */
if ( ! defined( 'SCP_PLUGIN_URL' ) ) {
	define( 'SCP_PLUGIN_URL', 'https://github.com/ControlledChaos/sitecore' );
}

/**
 * Disable Customizer
 *
 * @since 1.0.0
 * @var   boolean Whether to allow the Customizer.
 */
if ( ! defined( 'SCP_ALLOW_CUSTOMIZER' ) ) {
	define( 'SCP_ALLOW_CUSTOMIZER', true );
}

/**
 * User admin color picker
 *
 * @since 1.0.0
 * @var   boolean Whether to allow admin color pickers.
 */
if ( ! defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) ) {
	define( 'SCP_ALLOW_ADMIN_COLOR_PICKER', true );
}
