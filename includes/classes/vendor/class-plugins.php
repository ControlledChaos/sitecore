<?php
/**
 * Plugin initialization class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Classes\Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Plugins {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Compatability with other products.
		$this->acf();
		$this->acf_extended();
		$this->acf_columns();
	}

	/**
	 * Include plugins file from system
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function plugins() {

		/**
		 * Get plugins path
		 *
		 * Used to check for active plugins with the `is_plugin_active` function.
		 */

		// Compatibility with ClassicPress and WordPress.
		if ( file_exists( ABSPATH . 'wp-admin/includes/plugin.php' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Compatibility with the antibrand system.
		} elseif ( defined( 'APP_INC_PATH' ) && file_exists( APP_INC_PATH . '/backend/plugin.php' ) ) {
			include_once( APP_INC_PATH . '/backend/plugin.php' );
		}

		// Stop here if the plugin functions file can not be accessed.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			return;
		}
	}

	/**
	 * Compatability with other products
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf() {

		$this->plugins();

		/**
		 * Compatability constants
		 *
		 * Define constants for conditional loading of files
		 * if not defined in the system config file.
		 *
		 * The use of constants rather than settings is to
		 * prevent site owners and administrators disabling
		 * code which is needed to operate the site. Non-
		 * developers are less likely to edit the config file.
		 */

		/**
		 * ACF constant
		 *
		 * When set to true this loads the included files for
		 * the Advanced Custom Fields plugin.
		 *
		 * @since 1.0.0
		 * @var   boolean Default is true.
		 */
		if ( ! defined( 'SCP_USE_ACF' ) ) {
			define( 'SCP_USE_ACF', true );
		}

		/**
		 * Use ACF
		 *
		 * Instatiates this plugin's ACF class to
		 * include Advanced Custom Fields. Does so
		 * only if the original, third party plugin
		 * is not active and if the SCP_USE_ACF is
		 * defined as true.
		 *
		 * @since 1.0.0
		 */
		if (
			(
				! class_exists( 'acf' ) ||
				! is_plugin_active( 'advanced-custom-fields/acf.php' ) ||
				! is_plugin_active( 'advanced-custom-fields-pro/acf.php' )
			)
			&& SCP_USE_ACF
		) {
			$acf = new ACF;
		} else {
			$acf = null;
		}
		return $acf;
	}

	/**
	 * Compatability with other products
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_extended() {

		$this->plugins();

		/**
		 * Compatability constants
		 *
		 * Define constants for conditional loading of files
		 * if not defined in the system config file.
		 *
		 * The use of constants rather than settings is to
		 * prevent site owners and administrators disabling
		 * code which is needed to operate the site. Non-
		 * developers are less likely to edit the config file.
		 */

		/**
		 * ACFE constant
		 *
		 * When set to true this loads the included files for
		 * the Advanced Custom Fields Extended plugin.
		 *
		 * @since 1.0.0
		 * @var   boolean Default is true.
		 */
		if ( ! defined( 'SCP_USE_ACFE' ) ) {
			define( 'SCP_USE_ACFE', true );
		}

		/**
		 * Use ACF Extended
		 *
		 * Instatiates this plugin's ACF class to
		 * include ACF  Extended. Does so only if
		 * the original, third party plugin is not
		 * active and if the SCP_USE_ACF is defined
		 * as true.
		 *
		 * @since 1.0.0
		 */
		if (
			(
				! class_exists( 'ACFE' ) ||
				! is_plugin_active( 'acf-extended/acf-extended.php' ) ||
				! is_plugin_active( 'acf-extended-pro/acf-extended-pro.php' )
			)
			&& SCP_USE_ACFE
		) {
			include_once( SCP_PATH . 'includes/vendor/acf-extended/acf-extended.php' );

			// Remove pages in menu.
			add_action( 'admin_menu', [ $this, 'acfe_remove_menu' ], 9 );
		}

		// Enable ACFE rich text editor module by default.
		if ( function_exists( 'acf_update_setting' ) ) {
			acf_update_setting( 'acfe/modules/classic_editor', true );
		}
	}

	/**
	 * Remove pages in menu
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acfe_remove_menu() {
		remove_action( 'admin_menu', 'acfe_options_menu', 10 );
		remove_action( 'admin_menu', 'acfe_admin_settings_menu', 10 );
	}

	/**
	 * Admin columns for ACF fields
	 *
	 * Adds options in the edit field interface to add the field to
	 * list pages, such as "All Posts".
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_columns() {
		if ( is_admin() ) {
			return new ACF_Columns;
		}
	}
}
