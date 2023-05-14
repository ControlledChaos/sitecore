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
	 * Settings array
	 *
	 * Optionally register settings as array.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array An array of settings fields.
	 */
	protected $settings_register = [];

	/**
	 * Settings fields
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array An array of settings fields.
	 */
	protected $settings_fields = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $settings_register, $settings_fields ) {

		$register = [];

		/**
		 * Fields array
		 *
		 * Sample fields class demonstrates the
		 * accepted array keys.
		 *
		 * @see `includes/classes/settings/class-settings-fields-sample.php`
		 */
		$fields = [];

		$this->settings_register = wp_parse_args( $settings_register, $register );
		$this->settings_fields   = wp_parse_args( $settings_fields, $fields );
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

	protected function register_type() {

		$register = $this->settings_register;

		if ( is_array( $register ) && array_key_exists( 'serialize', $register ) ) {
			if ( true == $register['serialize'] ) {
				return $this->settings_array();
			}
		}
		return $this->add_fields();
	}

	protected function settings_array() {

		$register = $this->settings_register;
		$fields   = $this->settings_fields;

		if ( ! is_array( $register ) ) {
			return;
		}
		if ( ! is_array( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {

			if ( isset( $field['id'] ) && ! empty( $field['id'] ) ) {
				if ( isset( $register['section'] ) && ! empty( $register['section'] ) ) {

					register_setting(
						$register['section'],
						$field['id']
					);
				}
			}
		}
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

		$register = $this->settings_register;
		$fields   = $this->settings_fields;

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

				if (
					is_array( $register ) &&
					( array_key_exists( 'serialize', $register ) && ! $register['serialize'] ) ||
					! array_key_exists( 'serialize', $register ) ||
					! is_array( $register )
				) {
					add_settings_field(
						$field['id'],
						$field['title'],
						$callback,
						$field['page'],
						$field['section'],
						$field['args']
					);
				}
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
