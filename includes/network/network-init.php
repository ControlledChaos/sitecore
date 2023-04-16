<?php
/**
 * Network classes & functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Network;

use SiteCore\Classes\Network as Network_Class,
	SiteCore\Classes\Admin   as Admin_Class;

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

	add_action( 'plugins_loaded', $ns( 'classes' ) );
}

/**
 * Network classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {
	// new Admin_Class\Sample_Network_Page;
	// new Admin_Class\Sample_Network_Subpage;
}
