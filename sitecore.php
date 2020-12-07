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
 */

namespace SiteCore;

/**
 * License & Warranty
 *
 * Site Core is free software. It can be redistributed and/or modified
 * ad libidum. There is no license distributed with this product.
 *
 * Site Core is distributed WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
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
 *    Find `sitecore` and replace with the new name of your
 *    primary plugin file (this file).
 *
 * 5. Admin page slug
 *    Find `site-core` and replace with the new base slug of your
 *    plugin's admin pages.
 *
 * 6. Constants prefix
 *    Find `SCP` and replace with something unique to your plugin name. Use
 *    only uppercase letters.
 *
 * 7. General prefix
 *    Find `scp` and replace with something unique to your plugin name. Use
 *    only lowercase letters. This will change the prefix of all filters and
 *    settings, and the prefix of functions outside of a class.
 *
 * 8. Author
 *    Find `Greg Sweet <greg@ccdzine.com>` and replace with your name and
 *    email address or those of your organization.
 *
 * Finally, remember to modify or remove the instructional information in
 * admin pages, including contextual help tabs. Remove these renaming
 * instructions as desired.
 *
 * @see admin\partials - Check all files.
 * @see admin\partials\help - Check all files.
 */

// Stop if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constant: Plugin version
 *
 * Keeping the version at 1.0.0 as this is a starter plugin but
 * you may want to start counting as you develop for your use case.
 *
 * Remember to find and replace the `@version x.x.x` in docblocks.
 *
 * @since  1.0.0
 * @return string Returns the latest plugin version.
 */
define( 'SCP_VERSION', '1.0.0' );

/**
 * Constant: Required PHP version
 *
 * @since  1.0.0
 * @return string Returns the minimum required PHP version.
 */
define( 'SCP_PHP_VERSION', '7.3' );

/**
 * Constant: Text domain
 *
 * @since  1.0.0
 * @return string Returns the text domain of the plugin.
 */
define( 'SCP_DOMAIN', 'sitecore' );

/**
 * Constant: Plugin name
 *
 * @since  1.0.0
 * @return string Returns the text domain of the plugin.
 */
if ( defined( 'SCP_NAME' ) ) {
	$plugin_name = esc_html__( 'Site Core', SCP_DOMAIN );
	define( 'SCP_NAME', $plugin_name );
} else {
	define( 'SCP_NAME', 'Site Core' );
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
 * Change the second part of the define(), here as 'site-core',
 * to your preferred page slug.
 *
 * @since  1.0.0
 * @return string Returns the URL slug of the admin pages.
 */
if ( ! defined( 'SCP_ADMIN_SLUG' ) ) {
	define( 'SCP_ADMIN_SLUG', 'site-core' );
}

/**
 * Activation & deactivation
 *
 * The activation & deactivation methods run here before the check
 * for PHP version which otherwise disables the functionality of
 * the plugin.
 */

// Get the plugin activation class.
require_once SCP_PATH . 'includes/classes/class-activate.php';

// Get the plugin deactivation class.
require_once SCP_PATH . 'includes/classes/class-deactivate.php';

/**
 * Register the activaction & deactivation hooks
 *
 * The namspace of this file must remain escaped by use of the
 * backslash (`\`) prepending the acivation hooks and corresponding
 * functions.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
\register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );
\register_deactivation_hook( __FILE__, __NAMESPACE__ . '\deactivate_plugin' );

/**
 * Run activation class
 *
 * The code that runs during plugin activation.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function activate_plugin() {
	activation_class();
}
activate_plugin();

/**
 * Run daactivation class
 *
 * The code that runs during plugin deactivation.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function deactivate_plugin() {
	deactivation_class();
}
deactivate_plugin();

/**
 * Disable plugin for PHP version
 *
 * Stop here if the minimum PHP version is not met.
 * Prevents breaking sites running older PHP versions.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
if ( version_compare( phpversion(), SCP_PHP_VERSION, '<' ) ) {
	return;
}

/**
 * Core plugin function
 *
 * Loads PHP classes.
 *
 * @since  1.0.0
 * @access public
 * @global string $pagenow Gets the filename of the current page.
 * @return void
 */
function sitecore() {

	// Register plugin classes.
	require_once SCP_PATH . 'includes/autoload.php';

	// Get the filename of the current page.
	global $pagenow;

	// Instantiate plugin classes.
	$init = new Classes\Init;

	// Instantiate backend plugin classes.
	if ( is_admin() ) {

		// Run the page header on all screens.
		// Classes\Admin :: instance();

		// Run the dashboard only on the backend index screen.
		if ( 'index.php' == $pagenow ) {
			// Classes\Dashboard :: instance();
		}
	}
}

// Run the plugin.
sitecore();

// Happy developing!
