<?php
/**
 * Remove Customizer class
 *
 * @todo Add to settings.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Remove_Customizer {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Remove Customizer parts.
		add_action( 'admin_init', [ $this, 'admin_init' ], 10 );

		// Remove customize capability.
		add_filter( 'map_meta_cap', [ $this, 'capability' ], 10, 4 );
	}

	/**
	 * Remove Customizer parts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_init() {

		// Remove Customizer actions.
		remove_action( 'plugins_loaded', '_wp_customize_include', 10 );
		remove_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings', 11 );

		// Disable direct access to Customizer.
		add_action( 'load-customize.php', [ $this, 'direct_access' ] );
	}

	/**
	 * Disable direct access to Customizer
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function direct_access() {
		wp_die( __( 'The Customizer is currently disabled.', 'sitecore' ) );
	}

	/**
	 * Remove customize capability
	 *
	 * This needs to be public for the user toolbar link for 'Customize' to be hidden.
	 *
	 * Replaces `customize` capability with a fake capability.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns an array of capabilities.
	 */
	public function capability( $caps = [], $cap = '', $user_id = 0, $args = [] ) {

		if ( $cap == 'customize' ) {
			return [ 'fake_capability' ];
		}
		return $caps;
	}
}
