<?php
/**
 * Plugin deactivation class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Activate
 * @since      1.0.0
 */

namespace SiteCore\Classes\Activate;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Deactivate {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		// Add actions & filters.
	}
}

/**
 * Deactivate plugin
 *
 * Puts an instance of the class into a function.
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function deactivation_class() {
	return new Deactivate;
}
