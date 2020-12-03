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
