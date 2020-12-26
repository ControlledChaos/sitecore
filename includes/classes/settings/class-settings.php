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
define( 'SCP_FORMS', [
	'forms'    => SCP_PATH . 'views/backend/forms/',
	'partials' => SCP_PATH . 'views/backend/forms/partials'
] );

class Settings extends Classes\Base {

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
}
