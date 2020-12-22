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
use SiteCore\Classes\Admin as Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Define forms directory.
define( 'SCP_FORMS', [
	'forms'    => SCP_PATH . 'views/backend/forms/',
	'partials' => SCP_PATH . 'views/backend/forms/partials'
] );

class Settings {

	/**
	 * Sample string
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Returns the string.
	 *                Document what is expected or required.
	 */
	protected $sample_string = 'Sample string variable';

	/**
	 * Sample integer
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer Returns the integer.
	 *                Document what is expected or required.
	 */
	protected $sample_integer = 33;

	/**
	 * Sample array
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array Returns the array.
	 */
	protected $sample_array = [];

	/**
	 * Sample boolean
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Returns true or false.
	 */
	protected $sample_boolean = false;

	/**
	 * Instance of the class
	 *
	 * This method can be used to call an instance
	 * of the class from outside the class.
	 *
	 * Delete this method if not needed.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns an instance of the class.
	 */
	public static function instance() {
		return new self;
	}

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

		// Register settings sections and fields.
		add_action( 'admin_init', [ $this, 'settings' ] );
	}

	/**
	 * Plugin site settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @link  https://codex.wordpress.org/Settings_API
	 */
	public function settings() {

		// Admin menu settings section.
		add_settings_section(
			'ccp-site-admin-menu',
			__( 'Admin Menu Settings', SCP_DOMAIN ),
			[],
			'ccp-site-admin-menu'
		);

		// Site Settings page position.
		add_settings_field(
			'ccp_site_settings_position',
			__( 'Another Checkbox', SCP_DOMAIN ),
			[ $this, 'test_settings'],
			'ccp-site-admin-menu',
			'ccp-site-admin-menu',
			[ esc_html__( 'Make this settings page a top-level link and hide other settings links.', SCP_DOMAIN ) ]
		);

		register_setting(
			'ccp-site-admin-menu',
			'ccp_site_settings_position'
		);
	}

	public function test_settings() {
		echo '<label for="checkbox"><input type="checkbox" id="checkbox" /> Check this box</label>';
	}
}
