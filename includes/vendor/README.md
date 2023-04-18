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

## Advanced Custom Fields: Extended

This plugin includes a bundled version of Advanced Custom Fields: Extended Pro version 10.8.8.6, modified to work with certain developer settings applied by the Site Core plugin.

### Updating ACFE #1

The following docblock replaces the plugin header in the main file.

```php
/**
 * Advanced Custom Fields: Extended
 *
 * "Enhancement Suite which improves Advanced Custom Fields administration."
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Vendor
 * @version    x.x.x
 * @since      1.0.0
 * @author     ACF Extended
 * @link       https://www.acf-extended.com
 */
```

### Updating ACFE #2

If the basic version of Advanced Custom Fields has replaced the Applied Content Fields files in the `includes/vendor/acf` directory then apply the following changes to ACFE files.

* Remove or comment out the following condition from the `acf()` function in the core ACFE file, `acf-extended.php`.  
  Remove: `&& defined('ACF_PRO')`

* Replace the condition for the `acfe_dynamic_block_types` class in `acf-extended/includes/modules/block-types.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_block_types' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_dynamic_block_types_import` class in `acf-extended/includes/admin/tools/block-types-import.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_block_types_import' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_dynamic_block_types_export` class in `acf-extended/includes/admin/tools/block-types-export.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_block_types_export' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_dynamic_options_pages` class in `acf-extended/includes/modules/options-pages.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_options_pages' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_screen_options_page` class in `acf-extended/includes/forms/form-options-page.php`.  
  Replace with: `if ( ! class_exists( 'acfe_screen_options_page' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_dynamic_options_pages_import` class in `acf-extended/includes/admin/tools/block-types-import.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_options_pages_import' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_dynamic_options_pages_export` class in `acf-extended/includes/admin/tools/block-types-export.php`.  
  Replace with: `if ( ! class_exists( 'acfe_dynamic_options_pages_export' ) && class_exists( 'acf_pro' ) ) :`

* Replace the condition for the `acfe_field_flexible_content` class in `acf-extended/includes/fields/field-flexible-content.php`.  
  Replace with: `if ( ! class_exists( 'acfe_field_flexible_content' ) && class_exists( 'acf_pro' ) ) :`

* In the `acfe_upgrades` class add a check for ACF Pro in the `do_reset()` method.
  ```
  // Modules
  if ( class_exists( 'acf_pro' ) ) {
    acf_get_instance('acfe_dynamic_block_types')->reset();
    acf_get_instance('acfe_dynamic_options_pages')->reset();
    acf_get_instance('acfe_dynamic_post_types')->reset();
    acf_get_instance('acfe_dynamic_taxonomies')->reset();
  }
  ```

It is recommended to retain the `index.php` security file in the `acf-extended` directory.
