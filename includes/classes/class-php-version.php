<?php
/**
 * Check PHP version
 *
 * Operations contingent on the version of PHP used
 * on the plugin's server, notably disable functionality
 * if the minimum version is not met.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

final class PHP_Version {

	/**
	 * Instance of the class
	 *
	 * This method can be used to call an instance
	 * of the class from outside the class.
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
	 * @since  1.0.0
	 * @access private
	 * @return self
	 */
	private function __construct() {}

	/**
	 * Minimum PHP version
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function minimum() {
		return '7.4';
	}

	/**
	 * Version compare
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean Returns true if the minimum is met.
	 */
	public function version() {

		// Compare versions.
		if ( version_compare( phpversion(), $this->minimum(), '<' ) ) {
			return false;
		}
		return true;
	}
}

/**
 * Instance of the class
 *
 * @since  1.0.0
 * @access public
 * @return object PHP_Version Returns an instance of the class.
 */
function scp_php() {
	return PHP_Version :: instance();
}
