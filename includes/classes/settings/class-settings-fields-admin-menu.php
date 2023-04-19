<?php
/**
 * Admin menu settings fields
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
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-menu',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to make the link to the navigation menus screen a top-level menu entry.', 'sitecore' ),
					'label_for'   => 'admin_menu_menus_top',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'admin_menu_widgets_top',
				'title'    => __( 'Widgets Link', 'sitecore' ),
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-menu',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to make the link to the widgets screen a top-level menu entry.', 'sitecore' ),
					'label_for'   => 'admin_menu_widgets_top',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'admin_menu_custom_order',
				'title'    => __( 'Custom Order', 'sitecore' ),
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-menu',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to use the custom menu order.', 'sitecore' ),
					'label_for'   => 'admin_menu_custom_order',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Navigation Link field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function admin_menu_menus_top_order() {
		return 0;
	}

	/**
	 * Widgets Link field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function admin_menu_widgets_top_order() {
		return 1;
	}

	/**
	 * Custom Order field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function admin_menu_custom_order_order() {
		return 2;
	}

	/**
	 * Sanitize Navigation Link field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function admin_menu_menus_top_sanitize() {

		$option = get_option( 'admin_menu_menus_top', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_admin_menu_menus_top', $option );
	}

	/**
	 * Sanitize Widgets Link field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function admin_menu_widgets_top_sanitize() {

		$option = get_option( 'admin_menu_widgets_top', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_admin_menu_widgets_top', $option );
	}

	/**
	 * Sanitize Custom Order field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function admin_menu_custom_order_sanitize() {

		$option = get_option( 'admin_menu_custom_order', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_admin_menu_custom_order', $option );
	}

	/**
	 * Navigation Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu_menus_top_callback() {

		if ( ! current_theme_supports( 'menus' ) ) {
			return;
		}

		$fields   = $this->settings_fields;
		$order    = $this->admin_menu_menus_top_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->admin_menu_menus_top_sanitize();

		$html = sprintf(
			'<label for="%s">',
			$field_id
		);
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> <span>%s</span>',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</label>';

		echo $html;
	}

	/**
	 * Menus Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu_widgets_top_callback() {

		if ( ! current_theme_supports( 'widgets' ) ) {
			return;
		}

		$fields   = $this->settings_fields;
		$order    = $this->admin_menu_widgets_top_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->admin_menu_widgets_top_sanitize();

		$html = sprintf(
			'<label for="%s">',
			$field_id
		);
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> <span>%s</span>',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</label>';

		echo $html;
	}

	/**
	 * Custom Order field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu_custom_order_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->admin_menu_custom_order_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->admin_menu_custom_order_sanitize();

		$html = sprintf(
			'<label for="%s">',
			$field_id
		);
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> <span>%s</span>',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</label>';

		echo $html;
	}
}
