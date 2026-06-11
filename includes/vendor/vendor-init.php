<?php
/**
 * Initiate vendor classes
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Vendor;

use SiteCore\{
	Classes\Vendor as Vendor_Class,
	Compatibility  as Compat
};

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

	add_action( 'plugins_loaded', $ns( 'acf' ) );
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
