# Third-Party Files

This plugin includes files & functionality provided by outside sources, including compatible plugins. These plugins may need to be updated periodically so following are any changes made to third-party files and instructions for how to maintain these changes upon updating files.

## Advanced Custom Fields

"Customize WordPress with powerful, professional and intuitive fields."

### Updating ACF #1

The following docblock replaces the plugin header in the main file.

```php
/**
Advanced Custom Fields
 *
"Customize WordPress with powerful, professional and intuitive fields."
 *
@package    Site_Core
@subpackage Vendor
@category   Plugins
@version    x.x.x
@since      1.0.0
@author     Elliot Condon
@link       https://www.advancedcustomfields.com
 */
```

Other than the above file header there are no changes made to the Advanced Custom Fields plugin. Everything inside the `acf` directory can be replaced with all files from a new copy of the plugin.

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

Remove or comment out the following condition from the `has_acf()` function in the core ACFE file, `acf-extended.php`.

Remove:
`&& defined( 'ACF_PRO' )`

### Updating ACFE #3

The following code is added to the files listed below.

```php
if ( ! defined( 'ACF_PRO' ) ) {
    return;
}
```

* includes/admin/tools/dbt-export.php
* includes/admin/tools/dbt-import.php
* includes/admin/tools/dop-export.php
* includes/admin/tools/dop-import.php
* includes/admin/tools/form-export.php
* includes/admin/tools/form-import.php
* includes/fields/field-flexible-content.php
* includes/modules/dynamic-block-type.php
* includes/modules/dynamic-form.php
* includes/modules/dynamic-options-page.php

It is recommended to retain the `index.php` security file in the `acf-extended` directory.
