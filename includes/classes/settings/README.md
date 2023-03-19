# Settings Classes

The `Settings_Sections` class and the `Settings_Fields` class streamline the native settings API a bit, however developers still need to add callback and sanitize methods per field.

The classes automatically run the `add_settings_section()` and `add_settings_field()` functions for each section or field in the constuctor arrays, as well as adding them to the relevant hooks.

Sample child classes of the settings class and the fields class are provided to copy and rename when adding your own.
