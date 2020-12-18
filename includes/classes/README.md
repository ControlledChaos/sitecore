# Class File Documentation

The PHP class files used for plugin functionality are in the `includes/classes/` directory, with the exception of the activation & deactivation classes which are in `activate/classes/`. This documantation does not apply to the activation classes.

## Class Directory Names

Class files are organized into subdirectories of the `includes/classes/` directory. This is primarily for ease of locating a class file by knowing the capacity in which it is used. Classes more generally used may be placed directly in the `includes/classes/` directory.

When directories are added or renamed the `includes/autoloader.php` file must be edited accordingly.

The `SCP_CLASS` constant in the autoloader defines an array of directory names and their relative paths. Add, edit, or remove these definitions to reflect your directory structure. The class path constant is implement by referencing the keyword of the directory.

Example: `SCP_CLASS['core']` is used to register class files in the `includes/classes/core/` directory if defined as such by the `SCP_CLASS` constant.

## Class Namespaces

Class namespaces begin with the base namespace of the plugin, which for the purposes here of generic documentation shall be `NameSpace`, followed by `\Classes`. For self-documenting specificity the class namespaces end with a name describing the subdirectory where the class file can be found. Example: `NameSpace\Classes\Core` or `NameSpace\Classes\Admin`. Class files not in a subdirectory are namespaced as simply `NameSpace\Classes`.

Class namespaces are aliased according to the final sub-level of the namespace. Example: `use NameSpace\Classes\Vendor as Vendor`. The exception is that class files not in a subdirectory are aliases are aliased as `General`.

Class namespaces are not aliased in the autoloader. They are "fully qualified" using the complete namespace as it is in the class file.

**More information:**\
[https://www.php.net/manual/en/language.namespaces.php](https://www.php.net/manual/en/language.namespaces.php)

## Class Autoloader

The class autoloader makes the rest of the plugin's code aware of the classes it registers. The registered classes can then be called without requiring or including the class file.

Quite frankly, this autoloader is not as sophisticated as desired. It does not automatically register class files in a directory. This will be addressed and hopefully will be updated. As it is, classes need to be manually registered in the autoloader array of namespaced classes. On the other hand, this may be desirable for those who want specific control of what is registered.

### Adding Class Directories

Section forthcoming.

### Adding Class Files

Section forthcoming.

## Class Properties

Section forthcoming.

**More information:**\
[https://www.php.net/manual/en/language.oop5.properties.php](https://www.php.net/manual/en/language.oop5.properties.php)

## Class Methods

Section forthcoming.
