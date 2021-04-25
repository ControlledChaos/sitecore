<?php
/**
 * User colors class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace Site_Core\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class User_Colors {

	/**
	 * The class object
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected static $class_object;

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

		if ( is_null( self :: $class_object ) ) {
			self :: $class_object = new self();
		}

		// Return the instance.
		return self :: $class_object;
	}

	/**
	 * Get user color scheme
	 *
	 * Gets the name of the user's color scheme preference.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the name of the color scheme.
	 */
	public function get_user_color_scheme() {

		// Access global variables.
		global $_wp_admin_css_colors;

		// Get the name of the user's color scheme.
		$scheme = get_user_option( 'admin_color' );
		$name   = $_wp_admin_css_colors[$scheme]->name;

		// Rename "Default" as "Fresh".
		if ( 'fresh' == $scheme ) {
			return __( 'Fresh', DS_DOMAIN );
		}

		// The name if not the default scheme.
		return $name;
	}

	/**
	 * User colors
	 *
	 * Returns CSS hex codes for admin user schemes.
	 * These colors are used to fill base64/SVG background
	 * images with colors corresponding to current user's
	 * color scheme preference. Also used rendering the
	 * tab effect by applying the color scheme background
	 * color to the bottom border of the active tab.
	 *
	 * @see assets/js/svg-icon-colors.js
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $colors Array of CSS hex codes.
	 * @global integer $wp_version
	 * @return array Returns an array of color scheme CSS hex codes.
	 */
	public function user_colors( $colors = [] ) {

		// Get WordPress version.
		global $wp_version;

		// Get the user color scheme option.
		$color_scheme = get_user_option( 'admin_color' );

		/**
		 * Older color schemes for ClassicPress and
		 * older WordPress versions.
		 */
		if (
			function_exists( 'classicpress_version' ) ||
			( ! function_exists( 'classicpress_version' ) && version_compare( $wp_version,'4.9.9' ) <= 0 )
		) {

			/**
			 * The Fresh (default) scheme in older WordPress & in ClassicPress
			 * has a link hover/focus color different than the others.
			 */
			if ( ! $color_scheme || 'fresh'== $color_scheme ) {
				$colors = [ 'colors' =>
					[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#00a0d2', 'focus' => '#00a0d2' ]
				];
			} else {
				$colors = [ 'colors' =>
					[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#0096dd', 'focus' => '#0096dd' ]
				];
			}

		/**
		 * The Modern scheme in WordPress is the
		 * only one other than the default (Fresh)
		 * with unique link colors.
		 */
		} elseif ( 'modern' == $color_scheme ) {
			$colors = [ 'colors' =>
				[ 'background' => '#f1f1f1', 'link' => '#3858e9', 'hover' => '#183ad6', 'focus' => '#183ad6' ]
			];

		// All other default color schemes.
		} else {
			$colors = [ 'colors' =>
				[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#006799', 'focus' => '#006799' ]
			];
		}

		// Apply a filter for custom color schemes.
		return apply_filters( 'ds_user_colors', $colors );
	}

	/**
	 * User notification colors
	 *
	 * Used to print a style block for update count
	 * colors in the default wdget, depending on the
	 * user's color scheme preference. The color
	 * likely does not match any in the color scheme
	 * array so it is defined here by the color scheme
	 * slug.
	 *
	 * Accounts for the Admin Color Schemes plugin and
	 * a filter is applied for custom admin themes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the hex code of a matching color.
	 */
	public function user_notify_colors() {

		// Get the name of the user's color scheme.
		$scheme = get_user_option( 'admin_color' );

		// Modern scheme.
		if ( 'modern' == $scheme ) {
			$color = [
				'background' => '#3858e9',
				'text'       => '#ffffff'
			];

		// Light scheme.
		} elseif ( 'light' == $scheme ) {
			$color = [
				'background' => '#d64e07',
				'text'       => '#ffffff'
			];

		// 80's Kid scheme.
		} elseif ( '80s-kid' == $scheme ) {
			$color = [
				'background' => '#43db2a',
				'text'       => '#ffffff'
			];

		// Adderley scheme.
		} elseif ( 'adderley' == $scheme ) {
			$color = [
				'background' => '#bde7f0',
				'text'       => '#216bce'
			];

		// Aubergine scheme.
		} elseif ( 'aubergine' == $scheme ) {
			$color = [
				'background' => '#d97042',
				'text'       => '#ffffff'
			];

		// Blue scheme.
		} elseif ( 'blue' == $scheme ) {
			$color = [
				'background' => '#e1a948',
				'text'       => '#ffffff'
			];

		// Coffee scheme.
		} elseif ( 'coffee' == $scheme ) {
			$color = [
				'background' => '#9ea476',
				'text'       => '#ffffff'
			];

		// High Contrast Blue scheme.
		} elseif ( 'contrast-blue' == $scheme ) {
			$color = [
				'background' => '#9d2f4d',
				'text'       => '#ffffff'
			];

		// Cruise scheme.
		} elseif ( 'cruise' == $scheme ) {
			$color = [
				'background' => '#d2ac1f',
				'text'       => '#ffffff'
			];

		// Ectoplasm scheme.
		} elseif ( 'ectoplasm' == $scheme ) {
			$color = [
				'background' => '#d46f15',
				'text'       => '#ffffff'
			];

		// Flat scheme.
		} elseif ( 'flat' == $scheme ) {
			$color = [
				'background' => '#d35401',
				'text'       => '#ffffff'
			];

		// Kirk scheme.
		} elseif ( 'kirk' == $scheme ) {
			$color = [
				'background' => '#bd3854',
				'text'       => '#fefcf7'
			];

		// Lawn scheme.
		} elseif ( 'lawn' == $scheme ) {
			$color = [
				'background' => '#456a7f',
				'text'       => '#ffffff'
			];

		// Midnight scheme.
		} elseif ( 'midnight' == $scheme ) {
			$color = [
				'background' => '#69a8bb',
				'text'       => '#ffffff'
			];

		// Ocean scheme.
		} elseif ( 'ocean' == $scheme ) {
			$color = [
				'background' => '#aa9d88',
				'text'       => '#ffffff'
			];

		// Primary scheme.
		} elseif ( 'primary' == $scheme ) {
			$color = [
				'background' => '#f48236',
				'text'       => '#ffffff'
			];

		// Seashore scheme.
		} elseif ( 'seashore' == $scheme ) {
			$color = [
				'background' => '#73340f',
				'text'       => '#f8f6f1'
			];

		// Sunrise scheme.
		} elseif ( 'sunrise' == $scheme ) {
			$color = [
				'background' => '#ccaf0b',
				'text'       => '#ffffff'
			];

		// Vinyard scheme.
		} elseif ( 'vinyard' == $scheme ) {
			$color = [
				'background' => '#ba8752',
				'text'       => '#ffffff'
			];

		// The default and remaining native schemes.
		} else {
			$color = [
				'background' => '#f56e28',
				'text'       => '#ffffff'
			];
		}

		// The array of colors.
		return apply_filters( 'user_notify_colors', $color );
	}
}

/**
 * Instance of the class
 *
 * @since  1.0.0
 * @access public
 * @return object User_Colors Returns an instance of the class.
 */
function user_colors() {
	return User_Colors :: instance();
}
