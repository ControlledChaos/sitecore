<?php
/**
 * Initiate vendor classes
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Vendor;

use SiteCore\Classes\Vendor as Vendor_Class;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'plugins_loaded', $ns( 'acf' ), 11 );
	add_action( 'plugins_loaded', $ns( 'acfe' ), 11 );
}

/**
 * Advanced Custom Fields
 *
 * @since  1.0.0
 * @return void
 */
function acf() {
	$scp_acf = new Vendor_Class\Plugin_ACF;
	$scp_acf->include();
}

/**
 * Advanced Custom Fields: Extended
 *
 * @since  1.0.0
 * @return void
 */
function acfe() {
	$scp_acfe = new Vendor_Class\Plugin_ACFE;
	$scp_acfe->include();
}
