<?php
/**
 * ACF compatability class
 *
 * Includes the files of Advanced Custom Fields if
 * the Pro version is not available, the files of
 * ACF Extended if the ACF Pro version is available,
 * and ACF field groups & options pages registered
 * by this plugin.
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

class ACF_Extend {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$this->include();
	}

	function include() {
		include_once( SCP_PATH . 'includes/vendor/acf-extended/acf-extended.php' );
	}
}
