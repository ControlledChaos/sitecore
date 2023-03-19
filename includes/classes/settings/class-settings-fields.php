<?php
/**
 * Settings fields class
 *
 * Use to add settings fields for the
 * ClassicPress/WordPress settings API.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields {

	/**
	 * Settings sections
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array An array of settings sections.
	 */
	protected $settings_fields = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $settings_fields ) {

		/**
		 * Fields array
		 *
		 * Sample fields class demonstrates the
		 * accepted array keys.
		 *
		 * @see `includes/classes/settings/class-settings-fields-sample.php`
		 */
		$fields = [];

		$this->settings_fields = wp_parse_args( $settings_fields, $fields );

		// Register and add settings fields.
		add_action( 'admin_init', [ $this, 'settings' ] );
	}

	/**
	 * Settings fields
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function settings() {
		return $this->add_fields();
	}

	/**
	 * Add settings fields
	 *
	 * Adds a settings section fpr each array supplied
	 * in the constructor method, if an ID is supplied.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function add_fields() {

		$fields = $this->settings_fields;

		if ( ! is_array( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {

			if ( isset( $field['id'] ) && ! empty( $field['id'] ) ) :

				$callback = sprintf(
					'%s_callback',
					$field['id']
				);
				$callback = [ $this, $callback ];

				if ( isset( $field['callback'] ) && ! empty( $field['callback'] ) ) {
					$callback = $field['callback'];
				}

				register_setting(
					$field['page'],
					$field['id'],
					[
						'type' => $field['type']
					]
				);

				add_settings_field(
					$field['id'],
					$field['title'],
					$callback,
					$field['page'],
					$field['section'],
					$field['args']
				);
			endif;
		}
	}
}
