<?php
/**
 * User options class
 *
 * Puts options values into user-friendly text.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\Classes\Users;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class User_Options {

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
		return new self;
	}

	/**
	 * User login (username)
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $username Default empty string.
	 * @return string Returns the username.
	 */
	public function user_login( $username = '' ) {

		// Get current user data.
		$user_data = get_userdata( get_current_user_id() );

		if ( isset( $user_data->user_login ) ) {
			$username = esc_html( $user_data->user_login );
		} else {
			$username = __( 'Not available', 'sitecore' );
		}

		// Return the username.
		return $username;
	}

	/**
	 * Get user roles
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $roles Default empty array.
	 * @return array Returns an array of user roles.
	 */
	function get_user_roles( $roles = [] ) {

		// Get current user roles as a variable.
		$user  = wp_get_current_user();
		$roles = (array) $user->roles;

		// Add Super Admin if applicable to current user.
		if ( is_multisite() && is_super_admin( get_current_user_id() ) ) {
			$super = [ __( 'Super Admin', 'sitecore' ) ];
			$roles = array_merge( $super, $roles );
		}

		// Return an array of user roles.
		return $roles;
	}

	/**
	 * User roles
	 *
	 * Comma-separated list of user roles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $role_i18n Default empty array.
	 * @return string Returns the list.
	 */
	function user_roles( $role_i18n = [] ) {

		// Get the array of user roles.
		$roles = $this->get_user_roles();

		// Translate and capitalize each role.
		if ( is_array( $roles ) ) {
			foreach( $roles as $role ) {
				$role_i18n[] = ucwords( __( $role, 'sitecore' ) );
			}
		} else {

			// Default array.
			$role_i18n = [ __( 'Undetermined', 'sitecore' ) ];
		}

		// Return a comma-separated list of user roles.
		return implode( ', ', $role_i18n );
	}

	/**
	 * Nickname
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $nickname Default empty string.
	 * @return string Returns the nickname.
	 */
	public function nickname( $nickname = '' ) {

		// Get current user data.
		$user_data = get_userdata( get_current_user_id() );

		if ( isset( $user_data->nickname ) ) {
			$nickname = esc_html( $user_data->user_login );
		} else {
			$nickname = __( 'Not available', 'sitecore' );
		}

		// Return the nickname.
		return $nickname;
	}

	/**
	 * Display name
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $display_name Default empty string.
	 * @return string Returns the display name.
	 */
	public function display_name( $display_name = '' ) {

		// Get current user data.
		$user_data = get_userdata( get_current_user_id() );

		if ( isset( $user_data->display_name ) ) {
			$display_name = esc_html( $user_data->display_name );
		} else {
			$display_name = __( 'Not available', 'sitecore' );
		}

		// Return the display name.
		return $display_name;
	}

	/**
	 * User email
	 *
	 * Current user email with mailto link.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $user_email Default empty string.
	 * @return string Returns the email address.
	 */
	public function email( $user_email = '' ) {

		// Get current user data.
		$user_data = get_userdata( get_current_user_id() );

		if ( isset( $user_data->user_email ) ) {
			$user_email = sprintf(
				'<a href="mailto:%s">%s</a>',
				sanitize_email( $user_data->user_email ),
				sanitize_email( $user_data->user_email )
			);
		} else {
			$user_email = __( 'Not available', 'sitecore' );
		}

		// Return the linked email address.
		return $user_email;
	}

	/**
	 * User website
	 *
	 * Current user website URL with link, if available.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $website Default empty string.
	 * @return string Returns the website URL or no website notice.
	 */
	public function website( $website = '' ) {

		if ( ! empty( get_user_option( 'user_url' ) ) ) {
			$website = sprintf(
				'<a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
				esc_url( get_user_option( 'user_url' ) ),
				esc_url( get_user_option( 'user_url' ) )
			);
		} else {
			$website = __( 'No website provided.', 'sitecore' );
		}

		// Return the linked website URL or notice.
		return $website;
	}

	/**
	 * Frontend toolbar
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $enabled Default empty string.
	 * @return string Returns Yes/No text based on user option.
	 */
	public function toolbar( $enabled = '' ) {

		// Check the toolbar user option.
		if ( 'true' == get_user_option( 'show_admin_bar_front' ) ) {
			$enabled = __( 'Yes', 'sitecore' );
		} else {
			$enabled = __( 'No', 'sitecore' );
		}

		// Return the string.
		return $enabled;
	}
}

/**
 * Instance of the class
 *
 * @since  1.0.0
 * @access public
 * @return object User_Options Returns an instance of the class.
 */
function user_options() {
	return User_Options :: instance();
}
