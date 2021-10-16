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
	public function __construct() {}

	/**
	 * Add & update options
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function options() {
		update_option( 'avatar_default', 'mystery' );
	}
}
