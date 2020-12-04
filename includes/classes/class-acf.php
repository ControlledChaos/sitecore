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

// Define path and URL to the ACF plugin.
define( 'SCP_ACF_PATH', SCP_PATH . 'includes/vendor/acf/' );
define( 'SCP_ACF_URL', SCP_URL . 'includes/vendor/acf/' );

class ACF {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$this->include();

		// Customize the url setting to fix incorrect asset URLs.
		add_filter( 'acf/settings/url', [ $this, 'acf_settings_url' ] );

		// (Optional) Hide the ACF admin menu item.
		add_filter( 'acf/settings/show_admin', [ $this, 'acf_settings_show_admin' ] );
	}

	function include() {

		// Include the ACF plugin.
		include_once( SCP_ACF_PATH . 'acf.php' );
	}

	function acf_settings_url( $url ) {
		return SCP_ACF_URL;
	}

	function acf_settings_show_admin( $show_admin ) {
		return true;
	}
}