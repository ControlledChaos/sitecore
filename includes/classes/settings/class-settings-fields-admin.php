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

class Settings_Fields_Admin extends Settings_Fields {

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
			],
			[
				'id'       => 'enable_custom_dashboard',
				'title'    => __( 'Custom Dashboard', 'sitecore' ),
				'callback' => [ $this, 'enable_custom_dashboard' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-dashboard',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to replace the default dashboard with a custom dasnboard for this website.', 'sitecore' ),
					'label_for'   => 'enable_custom_dashboard',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'toolbar_remove_platform_link',
				'title'    => sprintf(
					'%s %s',
					platform_name(),
					__( 'Link', 'sitecore' )

				),
				'callback' => [ $this, 'toolbar_remove_platform_link' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-toolbar',
				'type'     => 'boolean',
				'args'     => [
					'description' => sprintf(
						__( 'Check to remove the %s logo link.', 'sitecore' ),
						platform_name()
					),
					'label_for'   => 'toolbar_remove_platform_link',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

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
		$html .= '<p>';

		echo $html;
	}

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
		$html .= '<p>';

		echo $html;
	}

	public function enable_custom_dashboard() {

		$fields   = $this->settings_fields;
		$field_id = $fields[2]['id'];
		$option   = get_option( $field_id, false );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[2]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}

	public function toolbar_remove_platform_link() {

		$fields   = $this->settings_fields;
		$field_id = $fields[3]['id'];
		$option   = get_option( $field_id, true );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[3]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}
}
