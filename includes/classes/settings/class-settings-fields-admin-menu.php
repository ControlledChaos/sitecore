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

class Settings_Fields_Admin_Menu extends Settings_Fields {

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
				'id'       => 'admin_menu_menus_top',
				'title'    => __( 'Navigation Link', 'sitecore' ),
				'callback' => [ $this, 'admin_menu_menus_top' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-menu',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to make the link to the navigation menus screen a top-level menu entry.', 'sitecore' ),
					'label_for'   => 'admin_menu_menus_top',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'admin_menu_widgets_top',
				'title'    => __( 'Widgets Link', 'sitecore' ),
				'callback' => [ $this, 'admin_menu_widgets_top' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-menu',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to make the link to the widgets screen a top-level menu entry.', 'sitecore' ),
					'label_for'   => 'admin_menu_widgets_top',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Navigation Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu_menus_top() {

		$fields   = $this->settings_fields;
		$field_id = $fields[0]['id'];
		$option   = get_option( $field_id, true );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[0]['args']['description']
		);
		$html .= '</p>';

		echo $html;
	}

	/**
	 * Menus Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu_widgets_top() {

		$fields   = $this->settings_fields;
		$field_id = $fields[1]['id'];
		$option   = get_option( $field_id, true );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[1]['args']['description']
		);
		$html .= '</p>';

		echo $html;
	}
}
