<?php
/**
 * Customizer reset class
 *
 * Resets customizations to the active theme.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Classes\Tools;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

final class Customizer_Reset {

	/**
	 * Access the Customizer
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object WP_Customize_Manager An instance of the Customizer class.
	 */
	private $wp_customize;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Customizer reset scripts.
		add_action( 'customize_controls_print_scripts', [ $this, 'scripts' ] );

		// AJAX load the Customizer preview.
		add_action( 'wp_ajax_customizer_reset', [ $this, 'ajax' ] );

		// Customizer instance.
		add_action( 'customize_register', [ $this, 'register' ] );
	}

	/**
	 * Customizer reset scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function scripts() {

		// Script suffix.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '';
		} else {
			$suffix = '.min';
		}

		// Enqueue the script for button and AJAX.
		wp_enqueue_script( 'scp-customizer-reset', SCP_URL . 'assets/js/customizer-reset' . $suffix . '.js', [ 'jquery' ], '20150120' );

		// Localize the above script.
		wp_localize_script( 'scp-customizer-reset', 'SCP_CustomizerReset', [
			'reset'   => __( 'Reset', 'sitecore' ),
			'confirm' => __( 'Warning! This will remove all changes made to this theme via the Customizer. This action is irreversible.', 'sitecore' ),
			'nonce'   => [
				'reset' => wp_create_nonce( 'customizer-reset' ),
			]
		] );
	}

	/**
	 * Register Customizer
	 *
	 * Stores a reference to `WP_Customize_Manager` instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $wp_customize An instance of the Customizer class.
	 * @return void
	 */
	public function register( $wp_customize ) {
		$this->wp_customize = $wp_customize;
	}

	/**
	 * AJAX load the Customizer preview
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function ajax() {

		if ( ! $this->wp_customize->is_preview() ) {
			wp_send_json_error( 'not_preview' );
		}

		if ( ! check_ajax_referer( 'customizer-reset', 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		$this->reset();
		wp_send_json_success();
	}

	/**
	 * Reset the Customizer
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function reset() {

		$settings = $this->wp_customize->settings();

		// Remove theme_mod settings registered in customizer.
		foreach ( $settings as $setting ) {
			if ( 'theme_mod' == $setting->type ) {
				remove_theme_mod( $setting->id );
			}
		}
	}
}
