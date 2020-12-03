<?php
/**
 * Site Core Plugin
 *
 * Develop site-specific plugins for ClassicPress, WordPress, and the antibrand system.
 *
 * @package Site_Core
 * @version 1.0.0
 * @author  Greg Sweet <greg@ccdzine.com>
 * @link    https://github.com/ControlledChaos/sitecore
 *
 * Plugin Name:  Site Core
 * Plugin URI:   https://github.com/ControlledChaos/sitecore
 * Description:  Develop site-specific plugins for ClassicPress, WordPress, and the antibrand system.
 * Version:      1.0.0
 * Author:       Controlled Chaos Design
 * Author URI:   https://ccdzine.com/
 * Text Domain:  sitecore
 * Domain Path:  /languages
 * Tested up to: 5.5.3
 */

/**
 * License & Warranty
 *
 * Site Core is free software. It can be redistributed and/or modified
 * ad libidum. There is no license distributed with this product.
 *
 * Site Core is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @see DISCLAIMER.md
 */

/**
 * Renaming the plugin
 *
 * First change the name of this file to reflect the new name of your plugin.
 *
 * Next change the information above in the plugin header and either change
 * the plugin name in the License & Warranty notice or remove it.
 *
 * Following is a list of strings to find and replace in all plugin files.
 *
 * 1. Plugin name
 *    Find `Site Core` and replace with your plugin name.
 *
 * 2. Package
 *    Find `Site_Core` and replace with your plugin name, include
 *    underscores between words. This will change the package name
 *    in file headers.
 *
 * 3. Namespace
 *    Find `SiteCore` and replace with something unique to your plugin name,
 *    include underscores between words.
 *
 * 4. Text domain
 *    Find sitecore and replace with the new name of your
 *    primary plugin file (this file).
 *
 * 5. Constants prefix
 *    Find `SCP` and replace with something unique to your plugin name. Use
 *    only uppercase letters.
 *
 * 6. General prefix
 *    Find `scp` and replace with something unique to your plugin name. Use
 *    only lowercase letters. This will change the prefix of all filters and
 *    settings, and the prefix of functions outside of a class.
 *
 * 7. Author
 *    Find `Greg Sweet <greg@ccdzine.com>` and replace with your name and
 *    email address or those of your organization.
 *
 * Finally, remember to modify or remove the instructional information in
 * admin pages, including contextual help tabs.
 *
 * @see admin\partials - Check all files.
 * @see admin\partials\help - Check all files.
 */

namespace SiteCore;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Constant: Required PHP version
 *
 * @since  1.0.0
 * @return string Returns the minimum required PHP version.
 */
if ( ! defined( 'SCP_PHP_VERSION' ) ) {
	define( 'SCP_PHP_VERSION', '7.3' );
}

/**
 * Constant: Plugin version
 *
 * Keeping the version at 1.0.0 as this is a starter plugin but
 * you may want to start counting as you develop for your use case.
 *
 * @since  1.0.0
 * @return string Returns the latest plugin version.
 */
if ( ! defined( 'SCP_VERSION' ) ) {
	define( 'SCP_VERSION', '1.0.0' );
}

/**
 * Constant: Text domain
 *
 * @since  1.0.0
 * @return string Returns the text domain of the plugin.
 *
 * @todo   Replace all strings with constant.
 */
if ( ! defined( 'SCP_DOMAIN' ) ) {
	define( 'SCP_DOMAIN', 'sitecore' );
}

/**
 * Constant: Plugin folder path
 *
 * @since  1.0.0
 * @return string Returns the filesystem directory path (with trailing slash)
 *                for the plugin __FILE__ passed in.
 */
if ( ! defined( 'SCP_PATH' ) ) {
	define( 'SCP_PATH', plugin_dir_path( __FILE__ ) );
}

/**
 * Constant: Plugin folder URL
 *
 * @since  1.0.0
 * @return string Returns the URL directory path (with trailing slash)
 *                for the plugin __FILE__ passed in.
 */
if ( ! defined( 'SCP_URL' ) ) {
	define( 'SCP_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Constant: Universal slug
 *
 * This URL slug is used for various plugin admin & settings pages.
 *
 * The prefix will change in your search & replace in renaming the plugin.
 * Change the second part of the define(), here as 'sitecore',
 * to your preferred page slug.
 *
 * @since  1.0.0
 * @return string Returns the URL slug of the admin pages.
 */
if ( ! defined( 'SCP_ADMIN_SLUG' ) ) {
	define( 'SCP_ADMIN_SLUG', 'sitecore' );
}
