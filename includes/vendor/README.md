# Third-Party Files

This plugin includes files & functionality provided by outside sources, including compatible plugins. These plugins may need to be updated periodically so following are any changes made to third-party files and instructions for how to maintain these changes upon updating files.

## Advanced Custom Fields

"Customize WordPress with powerful, professional and intuitive fields."

### Updating ACF #1

The following docblock replaces the plugin header in the main file.

```php
/**
 * Advanced Custom Fields
 *
 * "Customize WordPress with powerful, professional and intuitive fields."
 *
 * @package    Site_Core
 * @subpackage Vendor
 * @category   Plugins
 * @version    x.x.x
 * @since      1.0.0
 * @author     Elliot Condon, Delicious Brains
 * @link       https://www.advancedcustomfields.com
 */
```

### Updating ACF #2

Remove the upsell (since Delicious Brains) in `includes/admin/views/html-admin-navigation.php`.

It is recommended to retain the `index.php` security file in the `acf` directory.

## Advanced Custom Fields: Extended

"Enhancement Suite which improves Advanced Custom Fields administration."

### Updating ACFE #1

The following docblock replaces the plugin header in the main file.

```php
/**
 * Advanced Custom Fields: Extended
 *
 * "Enhancement Suite which improves Advanced Custom Fields administration."
 *
 * @package    Site_Core
 * @subpackage Vendor
 * @category   Plugins
 * @version    x.x.x
 * @since      1.0.0
 * @author     ACF Extended
 * @link       https://www.acf-extended.com
 */
```

### Updating ACFE #2

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
