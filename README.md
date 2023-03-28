# Site Core

Develop site-specific plugins for ClassicPress and WordPres.

![Minimum PHP version 7.4](https://img.shields.io/badge/PHP_minimum-7.4-8892bf.svg?style=flat-square)
![Tested on PHP version 8.0](https://img.shields.io/badge/PHP_tested-8.0-8892bf.svg?style=flat-square)
![ClassicPress tested on version 1.4.3](https://img.shields.io/badge/ClassicPress_tested-1.4.3-03768e.svg?style=flat-square)
![WordPress tested on version 6.1.1](https://img.shields.io/badge/WordPress_tested-6.1.1-2271b1.svg?style=flat-square)
![ACF Ready](https://img.shields.io/badge/ACF-Ready-00d3ae.svg?style=flat-square)

![Site Core Plugin Cover Image](https://github.com/ControlledChaos/sitecore/raw/main/cover.jpg)

## Plugin Overview

This is a means to an end, not intended to be used as is without further development. However it can be used as such. I use it to build site-specific plugins for clients.

This plugin is opinionated. However, many of the enhancements included are very popular modifications and are easily removed if not wanted.

## Approach

I have made every effort to use a simple, uniform naming system that can be quickly renamed for your project. Instructions are included in the plugin's core file.

Not every feature included with this plugin will be needed for my projects or yours. And one big reason for writing a site-specific plugin is to include only what the site needs and eliminate the overhead of plugins and themes that offer things that you don't need. So why have I packed so much into this plugin? Well, I find it to be much quicker and easier to remove unnecessary code that it is to write, or even copy & paste, new code into a project. And being that you will rename this plugin and that it will not update to overwrite your changes, modifications can be made ad libidum.

## Requests

If you would like to request development of a custom version of this plugin for your site, or to use as your own starter plugin, then contact Greg Sweet at [greg@ccdzine.com](mailto:greg@ccdzine.com).

## Cover Image

Anyone who wants to submit a replacement for the plugin's cover image into which I put three minutes of effort is welcome to do so.

---

## Features & Compatibility

Following are the highlights of this plugin's functionality.

### Advanced Custom Fields

Included is the basic version of the Advanced Custom Fields system for adding editor fields.

[https://www.advancedcustomfields.com](https://www.advancedcustomfields.com)

### Advanced Custom Fields: Extended

Included is the basic version of the Advanced Custom Fields: Extended enhancement suite to improve Advanced Custom Fields administration.

[https://www.acf-extended.com](https://www.acf-extended.com)

### Editor Options

If the plugin is used with WordPress 5.0 or higher then it provides various content editing options. Set the block editor or the rich text editor as default. Allow users to choose their editor preference to override the default editor. In networks, all site administrators to override editor defaults.

### Base Classes

Included are several base classes for adding features for your website project. Simply extend the relevant class. Sample, starter files for each are provided to copy, rename, and develop for your needs.

These classes include:

* Register post types
* Register taxonomies
* Register widget types
* Admin pages
* Admin subpages
* Title filters
* Content filters
* ACF options pages
* ACF options subpages

### User Options, Roles, & Capabilities

* Choose multiple roles per user
* Custom user avatar uploads
* Remove admin color scheme picker
* Developer role adds a 'develop' capability
* Programmatically add a developer back door user

## Configuration Constants

Several native features can be enabled or disabled using constants in the system config file to override options.

* Disable block widgets in WordPress 5.8+
* Disable Site Health page & widget in WordPress 5.2+
* Disable Site Health notifications in WordPress 5.2+
* Disable the user admin color picker
* Enable the old links manager
* Disable the bundled ACF plugin (activating via Plugins page does the same)

## Renaming the Plugin

First change the name of the core plugin file to reflect the new name of your plugin. Or leave the name since it clearly describes the purpose of the plugin.

Next change the information in the plugin header of the core plugin file and either change the plugin name in the License & Warranty notice or remove it.

Following is a list of strings to find and replace in all plugin files.

1. **Plugin name**
   Find `Site Core` and replace with your plugin name.

2. **Package**
   Find `Site_Core` and replace with your plugin name, include underscores between words. This will change the package name
   in file headers.

3. **Namespace**
   Find `SiteCore` and replace with something unique to your plugin name.

4. **Text domain**
   Find `sitecore` and replace with the new name of your primary plugin file (this file). It is standard to match the domain with the file name but this is not required to run the code.

5. **Admin page slug**
   Find `site-core` and replace with the new base slug of your plugin's admin pages.

6. **Constants prefix**
   Find `SCP` and replace with something unique to your plugin name. Use only uppercase letters.

7. **General prefix**
   Find `scp` and replace with something unique to your plugin name. Use only lowercase letters. This will change the prefix of all filters and settings, and the prefix of functions outside of a class.

8. **Constants**
   See the `includes/config.php` file to change developer/agency details, plugin name & URL, plus more personal or white-label constants.

Edit the README file in the root directory as needed, or delete it.

Finally, remember to modify or remove any instructional information in admin pages, including contextual help tabs. Remove these renaming instructions as desired.

## License & Warranty

Site Core is free software. It can be redistributed and/or modified ad libidum. There is no license distributed with this product.

Site Core is distributed WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

This project is an exercise in true open source code without the inherent ownership implied by the issuance of a license, however non-restrictive the license is.

## Disclaimer of Legalities

This statement is a disclaimer of all legalities concerning creative works. We reject the notion and the force of copyright law, and thus reject its presumed authority over our works. We therefore disclaim legalities including but not limited to access to copy and distribution protections provided by law, and we disclaim consent to be regulated by copy and distribution law.

## Distribution & Modification

This product, including images, graphical vector markup, documentation, and any works not described here, is released for public consumption ad libitum, ad infinitum.

In other words, this product shall remain available for use and for modification free of charge, free of regulation, and free of reprisal.
