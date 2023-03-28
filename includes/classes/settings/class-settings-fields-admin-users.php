<?php
/**
 * Admin users settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

use function SiteCore\Core\platform_name;

class Settings_Fields_Admin_Users extends Settings_Fields {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$fields = [
			[
				'id'       => 'enable_multi_user_roles',
				'title'    => __( 'Multiple User Roles', 'sitecore' ),
				'callback' => [ $this, 'enable_multi_user_roles_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-users',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to enable multiple user roles on profile edit screens.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'enable_user_avatars',
				'title'    => __( 'Custom User Avatars', 'sitecore' ),
				'callback' => [ $this, 'enable_user_avatars_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-users',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to enable user avatar uploads and extended, local options for the default avatar.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'disable_admin_color_schemes',
				'title'    => __( 'Disable Admin Color Schemes', 'sitecore' ),
				'callback' => [ $this, 'disable_admin_color_schemes_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-users',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to disable the user admin color scheme picker.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Multiple User Roles field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function enable_multi_user_roles_order() {
		return 0;
	}

	/**
	 * Custom User Avatars field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function enable_user_avatars_order() {
		return 1;
	}

	/**
	 * Disable Admin Color Schemes field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function disable_admin_color_schemes_order() {
		return 2;
	}

	/**
	 * Sanitize Multiple User Roles field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_multi_user_roles_sanitize() {

		$option = get_option( 'enable_multi_user_roles', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_multi_user_roles', $option );
	}

	/**
	 * Sanitize Custom User Avatars field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_user_avatars_sanitize() {

		$option = get_option( 'enable_user_avatars', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_user_avatars', $option );
	}

	/**
	 * Sanitize Disable Admin Color Schemes field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function disable_admin_color_schemes_sanitize() {

		$option = get_option( 'disable_admin_color_schemes', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_disable_admin_color_schemes', $option );
	}

	/**
	 * Multiple User Roles field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_multi_user_roles_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->enable_multi_user_roles_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_multi_user_roles_sanitize();

		$html = sprintf(
			'<fieldset><legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<label for="%s">',
			$field_id
		);
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</label></fieldset>';

		echo $html;
	}

	/**
	 * Custom User Avatars field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_user_avatars_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->enable_user_avatars_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_user_avatars_sanitize();

		$html = sprintf(
			'<fieldset><legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<label for="%s">',
			$field_id
		);
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</label></fieldset>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Avatar uploads are added to user profile screens and the default avatars are available on the Discussion Settings screen.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Disable Admin Color Schemes field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_admin_color_schemes_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->disable_admin_color_schemes_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->disable_admin_color_schemes_sanitize();

		if ( defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) && false == SCP_ALLOW_ADMIN_COLOR_PICKER ) {
			$html = sprintf(
				'<p>%s</p>',
				__( 'This option has been disabled in the wp-config file.', 'sitecore' )
			);

		} else {
			$html = sprintf(
				'<fieldset><legend class="screen-reader-text">%s</legend>',
				$fields[$order]['title']
			);
			$html .= sprintf(
				'<label for="%s">',
				$field_id
			);
			$html .= sprintf(
				'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
				$field_id,
				$field_id,
				checked( 1, $option, false ),
				$fields[$order]['args']['description']
			);
			$html .= '</label></fieldset>';
			$html .= sprintf(
				'<p class="description">%s</p>',
				__( 'This is handy for custom admin themes.', 'sitecore' )
			);
		}

		echo $html;
	}
}
