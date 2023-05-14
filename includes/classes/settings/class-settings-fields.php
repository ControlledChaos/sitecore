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
	 * Settings fields
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array An array of settings fields.
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
	}

	/**
	 * Fields init
	 *
	 * Register and add settings fields.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function fields() {
		add_action( 'admin_init', [ $this, 'get_fields' ] );
	}

	/**
	 * Get fields
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fields() {
		return $this->add_fields();
	}

	/**
	 * Add settings fields
	 *
	 * Adds a setting fpr each array supplied in the
	 * constructor method, if an ID is supplied.
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

	public function settings() {
		return $this->get_settings();
	}

	/**
	 * Get settings
	 *
	 * Gets all settings supplied in the constructor
	 * method, if an ID is supplied.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function get_settings() {

		$settings = [];
		$fields   = $this->settings_fields;

		if ( ! is_array( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			if ( isset( $field['id'] ) && ! empty( $field['id'] ) ) {
				$settings[] = $field['id'];
			}
		}
		return $settings;
	}
}
