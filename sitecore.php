<?php
/**
 * Site Core plugin
 *
 * Develop site-specific plugins for ClassicPress and WordPres.
 *
 * @package  Site_Core
 * @category Core
 * @since    1.0.0
 * @link     https://github.com/ControlledChaos/sitecore
 *
 * Plugin Name:  Site Core
 * Plugin URI:   https://github.com/ControlledChaos/sitecore
 * Description:  Develop site-specific plugins for ClassicPress and WordPres.
 * Version:      1.0.0
 * Author:       Controlled Chaos Design
 * Author URI:   https://ccdzine.com/
 * Text Domain:  sitecore
 * Domain Path:  /languages
 * Requires PHP  5.4
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * License & Warranty
 *
 * This product is free software. It can be redistributed and/or modified
 * ad libidum. There is no license distributed with this product.
 *
 * This product is distributed WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @see DISCLAIMER.md
 */

/**
 * Author's Note
 *
 * To all who may read this,
 *
 * I hope you find this code to be easily deciphered. I have
 * learned much by examining the code of well written & well
 * documented products so I have done my best to document this
 * code with comments where necessary, even where not necessary,
 * and by using logical, descriptive names for PHP classes &
 * methods, HTML IDs, CSS classes, etc.
 *
 * Beginners, note that the short array syntax ( `[]` rather than
 * `array()` ) is used. Use of the `array()` function is encouraged
 * by some to make the code more easily read by beginners. I argue
 * that beginners will inevitably encounter the short array syntax
 * so they may as well learn to recognize this early. If the code
 * is well documented then it will be clear when the brackets (`[]`)
 * represent an array. And someday you too will be writing many
 * arrays in your code and you will find the short syntax to be
 * a time saver. Let's not unnecessarily dumb-down code; y'all
 * are smart folk if you are reading this and you'll figure it out
 * like I did.
 *
 * Greg Sweet, Controlled Chaos Design, former mule packer, cook,
 * landscaper, & janitor who learned PHP by breaking stuff and by
 * reading code comments.
 */

/**
 * Renaming & rebranding the plugin
 *
 * First change the name of this file to reflect the new name of your plugin.
 * Or leave the name since it clearly describes the purpose of the plugin.
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
 *    Find `Site_Core` and replace with your plugin name. This will
 *    change the package name in file headers.
 *
 * 3. Namespace
 *    Find `SiteCore` and replace with something unique to your plugin.
 *
 * 4. Text domain
 *    Find `sitecore` and replace with the new name of your
 *    primary plugin file (this file). It is standard to match the domain
 *    with the file name but this is not required to run the code.
 *
 * 5. Admin page slug
 *    Find `site-core` and replace with the new base slug of your
 *    plugin's admin pages.
 *
 * 6. Constants prefix
 *    Find `SCP` and replace with something unique to your plugin. Use
 *    only uppercase letters.
 *
 * 7. General prefix
 *    Find `scp` and replace with something unique to your plugin name. Use
 *    only lowercase letters. This will change the prefix of all filters and
 *    settings, and the prefix of functions outside of a class.
 *
 * 8. Constants
 *    See the `includes/config.php` file to change developer/agency details,
 *    plugin name & URL, plus more personal or white-label constants.
 *
 * 9. Plugin URI:
 *    Find `https://github.com/ControlledChaos/sitecore` and replace with the
 *    URI of your plugin.
 *
 * 10. Change the developer backup account info in the `Users` class.
 *     @see includes/classes/users/class-users.php
 *
 * 11. Edit the README file in the root directory as needed, or delete it.
 *
 * 12. Finally, remember to modify or remove any instructional information in
 * admin pages, including contextual help tabs. Remove these renaming
 * instructions as desired.
 */

/**
 * Constant: Plugin base name
 *
 * @since 1.0.0
 * @var   string The base name of this plugin file.
 */
define( 'SCP_BASENAME', plugin_basename( __FILE__ ) );


/**
 * Plugin page link
 *
 * Adds a link to the plugin's action links
 * under the plugin description.
 *
 * Currently links to the top-level sample page.
 * Change URL as needed.
 *
 * @param  array $links Default plugin links on the 'Plugins' admin page.
 * @since  1.0.0
 * @return array Returns an array of links.
 */
function scp_plugin_page_link( $links ) {

	$url  = apply_filters( 'scp_plugin_page_page_url', 'index.php?page=manage-website' );
	$html = sprintf(
			'<a href="%s" class="scp-plugin-page-link">%s</a>',
			esc_url( admin_url( $url ) ),
			__( 'Help', 'sitecore' )
	);
	$link = [ $html ];

	return array_merge( $link, $links );
}
add_filter( 'plugin_action_links_' . SCP_BASENAME, __NAMESPACE__ . '\scp_plugin_page_link' );

// Get plugin configuration file.
require plugin_dir_path( __FILE__ ) . 'config.php';

/**
 * Activation & deactivation
 *
 * The activation & deactivation methods run here before the check
 * for PHP version which otherwise disables the functionality of
 * the plugin.
 */
include_once SCP_PATH . 'includes/activate/activate.php';
include_once SCP_PATH . 'includes/activate/deactivate.php';

/**
 * Register the activation & deactivation hooks
 *
 * The namespace of this file must remain escaped by use of the
 * backslash (`\`) prepending the activation hooks and corresponding
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

	// Update options.
	Activate\options();
}

/**
 * Run deactivation class
 *
 * The code that runs during plugin deactivation.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function deactivate_plugin() {

	// Update options.
	Deactivate\options();
}

/**
 * Disable plugin for PHP version
 *
 * Stop here if the minimum PHP version is not met.
 * Prevents breaking sites running older PHP versions.
 *
 * A notice is added to the plugin row on the Plugins
 * screen as a more elegant and more informative way
 * of disabling the plugin than putting the PHP minimum
 * in the plugin header, which activates a die() message.
 * However, the Requires PHP tag is included in the
 * plugin header with a minimum of version 5.4
 * because of the namespaces.
 *
 * @since  1.0.0
 * @return void
 */
if ( ! min_php_version() ) {

	// First add a notice to the plugin row.
	Activate\get_row_notice();

	// Stop here.
	return;
}

// Get the plugin initialization file.
require_once SCP_PATH . 'init.php';
