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
	 * The class object
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected static $class_object;

	/**
	 * Minimum PHP version
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The version number.
	 */
	protected $minimum = '7.4';

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

		if ( is_null( self :: $class_object ) ) {
			self :: $class_object = new self();
		}

		// Return the instance.
		return self :: $class_object;
	}

	/**
	 * Minimum PHP version
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function minimum() {
		return $this->minimum;
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

			// Return false if the minimum is not met.
			return false;
		}

		// Return true by default.
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
function php() {
	return PHP_Version :: instance();
}
