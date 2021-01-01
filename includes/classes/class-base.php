<?php
/**
 * Sample/starter class
 *
 * @see `includes/classes/README.md`;
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   General
 * @since      1.0.0
 */

namespace SiteCore\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Base {

	/**
	 * Instance of the class
	 *
	 * This method can be used to call an instance
	 * of the class from outside the class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns an instance of the class.
	 */
	public static function instance() {

		// Varialbe for the instance of the class.
		static $class_instance = null;

		// Set variable for new instance.
		if ( is_null( $class_instance ) ) {
			$class_instance = new self;
		}

		// Return the instance.
		return $class_instance;
	}

	/**
	 * Constructor method
	 *
	 * Calls the parent constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		/**
		 * Scripts & styles
		 *
		 * Enqueue or print scripts & styles for back end or for front end.
		 *
		 * @since  1.0.0
		 */
		if ( is_admin() ) {

			// Enqueue admin parent scripts.
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_parent_enqueue_scripts' ] );

			// Print admin parent scripts to head.
			add_action( 'admin_head', [ $this, 'admin_parent_print_scripts' ] );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			// Print admin scripts to head.
			add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ] );

			// Enqueue admin parent styles.
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_parent_enqueue_styles' ] );

			// Print admin parent styles to head.
			add_action( 'admin_head', [ $this, 'admin_parent_print_styles' ] );

			// Enqueue admin styles.
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );

			// Print admin styles to head.
			add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ] );
		} else {

			// Enqueue frontend parent scripts.
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_parent_enqueue_scripts' ] );

			// Print frontend parent scripts to head.
			add_action( 'wp_head', [ $this, 'frontend_parent_print_scripts' ] );

			// Enqueue frontend scripts.
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ] );

			// Print frontend scripts to head.
			add_action( 'wp_head', [ $this, 'frontend_print_scripts' ] );

			// Enqueue frontend parent styles.
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_parent_enqueue_styles' ] );

			// Print frontend parent styles to head.
			add_action( 'wp_head', [ $this, 'frontend_parent_print_styles' ] );

			// Enqueue frontend styles.
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_styles' ] );

			// Print frontend styles to head.
			add_action( 'wp_head', [ $this, 'frontend_print_styles' ] );
		}
	}

	/**
	 * Enqueue admin parent scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_scripts() to
	 * enqueue scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_parent_enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Print admin parent scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_scripts() to
	 * print scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function admin_parent_print_scripts() {

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue admin scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Print admin scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function admin_print_scripts() {

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue admin parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_styles() to
	 * enqueue styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_parent_enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print admin parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_styles() to
	 * print styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function admin_parent_print_styles() {

		// <style></style>
		// file_get_contents();
	}

	/**
	 * Enqueue admin styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print admin styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function admin_print_styles() {

		// <style></style>
		// file_get_contents();
	}

	/**
	 * Enqueue frontend parent scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_scripts() to
	 * enqueue scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_parent_enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Print frontend parent scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_scripts() to
	 * print scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function frontend_parent_print_scripts() {

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue frontend scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_enqueue_scripts() {

		// wp_enqueue_script();
	}

	/**
	 * Print frontend scripts
	 *
	 * This is for scripts that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function frontend_print_scripts() {

		// <script></script>
		// file_get_contents();
	}

	/**
	 * Enqueue frontend parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_styles() to
	 * enqueue styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_parent_enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print frontend parent styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_styles() to
	 * print styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function frontend_parent_print_styles() {

		// <style></style>
		// file_get_contents();
	}

	/**
	 * Enqueue frontend styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function frontend_enqueue_styles() {

		// wp_enqueue_style();
	}

	/**
	 * Print frontend styles
	 *
	 * This is for styles that are
	 * spefific to a screen class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function frontend_print_styles() {

		// <style></style>
		// file_get_contents();
	}
}
