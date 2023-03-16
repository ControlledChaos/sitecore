<?php
/**
 * Settings sections class
 *
 * Use to add settings sections for the
 * ClassicPress/WordPress settings API.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Sections {

	/**
	 * Settings sections
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array An array of settings sections.
	 */
	protected $settings_sections = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $settings_sections ) {

		/**
		 * Section array
		 *
		 * Sample sections class demonstrates the
		 * accepted array keys.
		 *
		 * @see `includes/classes/settings/class-settings-sections-sample.php`
		 */
		$sections = [];

		$this->settings_sections = wp_parse_args( $settings_sections, $sections );

		// Add settings sections.
		add_action( 'admin_init', [ $this, 'sections' ] );
	}

	/**
	 * Settings sections
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function sections() {
		return $this->add_sections();
	}

	/**
	 * Add settings sections
	 *
	 * Adds a settings section fpr each array supplied
	 * in the constructor method, if an ID is supplied.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function add_sections() {

		$sections = $this->settings_sections;

		if ( ! is_array( $sections ) ) {
			return;
		}

		foreach ( $sections as $section ) {

			if ( isset( $section['id'] ) && ! empty( $section['id'] ) ) :

				add_settings_section(
					$section['id'],
					$section['title'],
					$section['callback'],
					$section['page'],
					$section['args']
				);
			endif;
		}
	}
}
