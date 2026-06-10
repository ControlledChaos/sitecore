# Third-Party Files

This plugin includes files & functionality provided by outside sources, including compatible plugins. These plugins may need to be updated periodically so following are any changes made to third-party files and instructions for how to maintain these changes upon updating files.

## Applied Content Fields

This plugin includes a bundled fork of Advanced Custom Fields Pro version 5.9.6, the last version of the plugin released before it was sold to by its originator, Elliot Condon, to the Delicious Brains corporation.

## Advanced Custom Fields

The Advanced Custom Fields plugin, basic version or Pro version, should work in place of the Applied Content Fields plugin without issue.

### Adding Advanced Custom Fields #1

Delete all files in the `includes/vendor/acf` directory. Replace with all files from the Advanced Custom Fields plugin.

It is recommended to retain the `index.php` security file in the `acf` directory.

### Adding Advanced Custom Fields #2

The following docblock replaces the plugin header in the main file.

```php
/**
 * Advanced Custom Fields
 *
 * "Customize WordPress with powerful, professional and intuitive fields."
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Vendor
 * @version    x.x.x
 * @since      1.0.0
 * @author     Elliot Condon, Delicious Brains
 * @link       https://www.advancedcustomfields.com
 */
```

### Adding Advanced Custom Fields #3

If bundling the basic version, remove the upsell (since Delicious Brains) in `includes/admin/views/html-admin-navigation.php`.
