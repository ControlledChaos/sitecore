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
use SiteCore\Classes as Classes;
use SiteCore\Classes\Admin as Admin;

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

class Settings extends Classes\Base {

	/**
	 * Setting data
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $setting = [];

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

		parent :: __construct();

		new Settings_API;

		/**
		 * Admin settings page
		 *
		 * Use an ACF options page if ACF Pro is active.
		 *
		 * @todo Review whether the ACF condition is desirable.
		 */
		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
			new Admin\Admin_ACF_Settings_Page;
		} else {
			new Admin\Admin_Settings_Page;
		}

		// @todo Remove when testing is finished.
		new Admin\Add_Settings_Page;

		// Content settings.
		new Admin\Content_Settings;

		// Register settings sections and fields.
		add_action( 'admin_init', [ $this, 'settings' ] );
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
	 * Settings section
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function section() {}

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
	$dir_file = SCP_PATH .  'includes/settings' . "{/,/*/}" . 'settings-*.php';

	// Include each file matching the path patterns.
	foreach ( glob( $dir_file, GLOB_BRACE ) as $settings_file ) {
		if ( is_file( $settings_file ) && is_readable( $settings_file ) ) {
			require $settings_file;
		}
	}
}
get_settings();
