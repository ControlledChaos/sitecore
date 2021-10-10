<?php
/**
 * Settings class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

// Alias namespaces.
use
SiteCore\Classes        as Classes,
SiteCore\Classes\Admin  as Admin,
SiteCore\Classes\Vendor as Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Define forms directory.
if ( ! defined( 'SCP_FORMS' ) ) {
	define( 'SCP_FORMS', [
		'forms'    => SCP_PATH . 'views/backend/forms/',
		'partials' => SCP_PATH . 'views/backend/forms/partials'
	] );
}

class Settings {

	/**
	 * Setting data
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $setting = [];

	/**
	 * Settings page class
	 *
	 * The fully qualified page class with namespace.
	 *
	 * @example 'SiteCore\Classes\Admin\Sample_Settings_Page'
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Namespace & class.
	 */
	protected $api_page_class = '';

	/**
	 * ACF settings page class
	 *
	 * The fully qualified page class with namespace.
	 *
	 * @example 'SiteCore\Classes\Vendor\Sample_ACF_Options'
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Namespace & class.
	 */
	protected $acf_page_class = '';

	/**
	 * Constructor method
	 *
	 * Calls the parent constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Settings page.
		$this->settings_page();
	}

	/**
	 * Settings page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns a settings page object if available,
	 *               returns null otherwise.
	 */
	public function settings_page() {

		// First check if ACF Pro is active.
		if ( class_exists( 'acf_pro' ) ) {
			$acf_active = true;
		} else {
			$acf_active = false;
		}

		// Native & ACF page classes.
		$api_page = $this->api_page_class;
		$acf_page = $this->acf_page_class;

		// Run ACF settings page class if available.
		if ( $acf_active && class_exists( $acf_page ) ) {
			return new $acf_page;

		// Run native settings page class if available.
		} elseif ( class_exists( $api_page ) ) {
			return new $api_page;
		}

		// Default to null.
		return null;
	}

	/**
	 * Settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings() {}

	/**
	 * Existing settings section
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function existing_section() {

		// Stop here if the class settings are on their own page.
		if ( is_null( $this->settings_page() ) ) {
			return;
		}
	}

	/**
	 * Setting
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function setting( $args ) {

		$defaults = [
			'id'            => null,
			'id_before'     => 'scp_',
			'id_after'      => null,
			'capability'    => 'read',
			'section'       => null,
			'label'         => null,
			'label_before'  => null,
			'label_after'   => null,
			'class'         => 'scp-setting',
			'icon'          => null,
			'description'   => null,
			'hide-if-no-js' => false,
			'callback'      => null,
			'priority'      => 10,
		];

		$args       = wp_parse_args( $args, $defaults );
		$args['id'] = sanitize_html_class( $args['id'] );

		// Ensure there is an an ID.
		if ( ! $args['id'] ) {
			return;
		}

		// Allows for overriding existing with that ID.
		$this->setting[ $args['id'] ] = $args;
	}
}

/**
 * Get settings
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function get_settings() {

	/**
	 * Path to settings files
	 *
	 * Only gets files prefixed with `settings-`.
	 *
	 * This includes main directory (`/`) and any
	 * subdirectories (`* /`).
	 */
	$dir_file = SCP_PATH . 'includes/settings' . "{/,/*/}" . 'settings-*.php';

	// Include each file matching the path patterns.
	foreach ( glob( $dir_file, GLOB_BRACE ) as $settings_file ) {
		if ( is_file( $settings_file ) && is_readable( $settings_file ) ) {
			include $settings_file;
		}
	}
}
// get_settings();
