<?php
/**
 * Initiate widget classes and functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Widgets
 * @since      1.0.0
 */

namespace SiteCore\Widgets;

use SiteCore\Classes\Widgets as Widgets_Class;

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

	if ( get_option( 'enable_link_manager', false ) ) {
		add_filter( 'widget_links_args', $ns( 'widget_links_args' ) );
	}
}

/**
 * Frontend classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {
	// instantiate classes.
}

/**
 * Links widget arguments
 *
 * Applies to the classic links manager widget.
 *
 * @since  1.0.0
 * @return array
 */
function widget_links_args( $args ) {
	$args['title_li'] = __( 'Featured Links', 'sitecore' );
	return $args;
}
