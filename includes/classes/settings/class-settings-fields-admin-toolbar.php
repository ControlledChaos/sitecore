<?php
/**
 * Admin toolbar settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

use function SiteCore\Core\platform_name;

class Settings_Fields_Admin_Toolbar extends Settings_Fields {

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
				'id'       => 'toolbar_remove_platform_link',
				'title'    => sprintf(
					__( '%s Link', 'sitecore' ),
					platform_name()

				),
				'callback' => [ $this, 'toolbar_remove_platform_link_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-toolbar',
				'type'     => 'checkbox',
				'args'     => [
					'description' => sprintf(
						__( 'Check to remove the %s logo link.', 'sitecore' ),
						platform_name()
					),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'disable_user_toolbar',
				'title'    => __( 'Disable Toolbar', 'sitecore' ),
				'callback' => [ $this, 'disable_user_toolbar_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-toolbar',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to add option for disabling the user toolbar on the front end.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			null,
			$fields
		);
	}

	/**
	 * Sanitize Platform Link field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function toolbar_remove_platform_link_sanitize() {

		$option = get_option( 'toolbar_remove_platform_link', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_toolbar_remove_platform_link', $option );
	}

	/**
	 * Sanitize Disable Toolbar field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function disable_user_toolbar_sanitize() {

		$option = get_option( 'disable_user_toolbar', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_disable_user_toolbar', $option );
	}

	/**
	 * Platform Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function toolbar_remove_platform_link_callback() {

		$fields   = $this->settings_fields;
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->toolbar_remove_platform_link_sanitize();

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
	 * Disable Toolbar field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_user_toolbar_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->disable_user_toolbar_sanitize();

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
		$html .= __( '<p class="description">Adds a link under Users in the admin menu.</p>', 'sitecore' );

		echo $html;
	}
}
