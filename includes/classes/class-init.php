<?php
/**
 * Plugin initialization class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Includes
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Init {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Compatability with other products.
		$this->compat();
	}

	/**
	 * Compatability with other products
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function compat() {

		if ( ! class_exists( 'acf' ) ) {
			$acf = new ACF;
		} else {
			$acf = null;
		}

		if ( ! class_exists( 'ACFE' ) ) {
			$acf_extend = new ACF_Extend;
		} else {
			$acf_extend = null;
		}
		return [ $acf, $acf_extend ];
	}
}
