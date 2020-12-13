<?php
/**
 * Frontend class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Frontend {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Remove the ClassicPress/WordPress logo from the admin bar.
		add_action( 'admin_bar_menu', [ $this, 'remove_toolbar_logo' ], 999 );

		// Remove search from frontend admin toolbar.
		add_action( 'wp_before_admin_bar_render', [ $this, 'remove_toolbar_search' ] );
	}

	/**
	 * Remove the ClassicPress/WordPress logo from the admin bar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $wp_admin_bar
	 * @return void
	 *
	 * @todo Make this optional.
	 */
	public function remove_toolbar_logo( $wp_admin_bar ) {

		$wp_admin_bar->remove_node( 'wp-logo' );
	}

	/**
	 * Remove the search bar from the frontend admin toolbar.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object wp_admin_bar
	 * @return void
	 *
	 * @todo Make this optional.
	 */
	public function remove_toolbar_search() {

		global $wp_admin_bar;

		$wp_admin_bar->remove_menu( 'search' );
	}
}
