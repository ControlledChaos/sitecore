<?php
/**
 * Admin settings fields
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
				'callback' => [ $this, 'enable_multi_user_roles' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-users',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to enable multiple user roles on profile edit screens.', 'sitecore' ),
					'label_for'   => 'enable_multi_user_roles',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'enable_user_avatars',
				'title'    => __( 'Custom User Avatars', 'sitecore' ),
				'callback' => [ $this, 'enable_user_avatars' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-users',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to enable user avatar uploads and extended, local options for the default avatar.', 'sitecore' ),
					'label_for'   => 'enable_user_avatars',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Multiple User Roles field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_multi_user_roles() {

		$fields   = $this->settings_fields;
		$field_id = $fields[0]['id'];
		$option   = get_option( $field_id, false );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[0]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}

	/**
	 * Custom User Avatars field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_user_avatars() {

		$fields   = $this->settings_fields;
		$field_id = $fields[1]['id'];
		$option   = get_option( $field_id, false );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[1]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}
}
