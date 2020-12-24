# Settings Framework

The settings framework is a wrapper around the compatible settings API, making it simple to create and maintain
settings pages.

## Settings Page Example

```php
class Sample_Settings_Page extends Add_Menu_Page {
    /**
     * @var string
     */
    private $plugin_path;

    /**
     * @var WordPressSettingsFramework
     */
    private $wpsf;

    /**
     * WPSFTest constructor.
     */
    function __construct() {
        $this->plugin_path = plugin_dir_path( __FILE__ );

        // Include and create a new WordPressSettingsFramework
        require_once( $this->plugin_path . 'wp-settings-framework/wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path . 'settings/settings-general.php', 'prefix_settings_general' );

        // Add admin menu
        add_action( 'admin_menu', [ $this, 'add_settings_page' ], 20 );
        
        // Add an optional settings validation filter (recommended)
        add_filter( $this->wpsf->get_option_group() . '_settings_validate', [ &$this, 'validate_settings' ] );
    }

    /**
     * Add settings page.
     */
    function add_settings_page() {

        $this->wpsf->add_settings_page( [
            'parent_slug' => 'options-general.php',
            'page_title'  => __( 'Sample Settinges Page', 'text-domain' ),
            'menu_title'  => __( 'Sample Settinges', 'text-domain' ),
            'capability'  => 'manage_options',
        ] );
    }

    /**
     * Validate settings.
     * 
     * @param $input
     *
     * @return mixed
     */
    function validate_settings( $input ) {
        // Do your settings validation here
        // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
        return $input;
    }

    // ...
}
```

Your settings values can be accessed like so:

```php
// Get settings
$this->wpsf->get_settings();
```

This will get either the saved setting values, or the default values that you set in your settings file.

Or by getting individual settings:

```php
// Get individual setting
$setting = wpsf_get_setting( 'prefix_settings_general', 'general', 'text' );
```

## Settings File Example

The settings files work by filling the global `$wpsf_settings` array with data in the following format:

```php
$wpsf_settings[] = [
    'section_id'          => 'general', // The section ID (required)
    'section_title'       => 'General Settings', // The section title (required)
    'section_description' => 'Some intro description about this section.', // The section description (optional)
    'section_order'       => 5, // The order of the section (required)
    'fields'              => [
        [
            'id'          => 'text',
            'title'       => 'Text',
            'desc'        => 'This is a description.',
            'placeholder' => 'This is a placeholder.',
            'type'        => 'text',
            'default'     => 'This is the default value'
        ],
        [
            'id'      => 'select',
            'title'   => 'Select',
            'desc'    => 'This is a description.',
            'type'    => 'select',
            'default' => 'green',
            'choices' => [
                'red' => 'Red',
                'green' => 'Green',
                'blue'  => 'Blue'
            ]
        ],
        // Add as many fields as needed…
    ]
];
```

Valid `fields` values are:

* `id` - Field ID
* `title` - Field title
* `desc` - Field description
* `placeholder` - Field placeholder
* `type` - Field type (text/password/textarea/select/radio/checkbox/checkboxes/color/file)
* `default` - Default value (or selected option)
* `choices` - Array of options (for select/radio/checkboxes)

See `settings/example-settings.php` for an example of possible values.

## API Details

`new WordPressSettingsFramework( string $settings_file [, string $option_group = ''] )`

Creates a new settings [option_group](http://codex.wordpress.org/Function_Reference/register_setting) based on a setttings file.

* `$settings_file` - path to the settings file
* `$option_group` - optional "option_group" override (by default this will be set to the basename of the settings file)

`wpsf_get_setting( $option_group, $section_id, $field_id )`

Get a setting from an option group

* `$option_group` - option group id.
* `$section_id` - section id (change to `[{$tab_id}_{$section_id}]` when using tabs.
* `$field_id` - field id.

`wpsf_delete_settings( $option_group )`

Delete all the saved settings from a option group

* `$option_group` - option group id

## Filters

* `wpsf_register_settings_[option_group]` - The filter used to register your settings. See `settings/example-settings.php` for an example.
* `[option_group]_settings_validate` - Basically the `$sanitize_callback` from [register_setting](http://codex.wordpress.org/Function_Reference/register_setting). Use `$wpsf->get_option_group()` to get the option group id.
* `wpsf_defaults_[option_group]` - Default args for a settings field

## Actions

* `wpsf_before_field_[option_group]` - Before a field HTML is output
* `wpsf_before_field_[option_group]_[field_id]` - Before a field HTML is output
* `wpsf_after_field_[option_group]` - After a field HTML is output
* `wpsf_after_field_[option_group]_[field_id]` - After a field HTML is output
* `wpsf_before_settings_[option_group]` - Before settings form HTML is output
* `wpsf_after_settings_[option_group]` - After settings form HTML is output
* `wpsf_before_settings_fields_[option_group]` - Before settings form fields HTML is output (inside the `<form>`)
* `wpsf_do_settings_sections_[option_group]` - Settings form fields HTMLoutput (inside the `<form>`)
* `wpsf_do_settings_sections_[option_group]` - Settings form fields HTMLoutput (inside the `<form>`)
* `wpsf_before_tab_links_[option_group]` - Before tabs HTML is output
* `wpsf_after_tab_links_[option_group]` - After tabs HTML is output
