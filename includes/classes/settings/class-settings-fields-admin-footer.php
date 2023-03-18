<?php
/**
 * Admin footer settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

use function SiteCore\Core\platform_name;

class Settings_Fields_Admin_Footer extends Settings_Fields {

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
				'id'       => 'enable_custom_admin_footer',
				'title'    => __( 'Enable Custom Footer', 'sitecore' ),
				'callback' => [ $this, 'enable_custom_admin_footer_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-footer',
				'type'     => 'boolean',
				'args'     => [
					'description' => sprintf(
						__( 'Check to replace the default %s footer on admin screens.', 'sitecore' ),
						platform_name()
					),
					'label_for'   => 'enable_custom_admin_footer',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Custom Dashboard field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function enable_custom_admin_footer_order() {
		return 0;
	}

	/**
	 * Sanitize Custom Dashboard field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_custom_admin_footer_sanitize() {

		$option = get_option( 'enable_custom_admin_footer', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_custom_admin_footer', $option );
	}

	/**
	 * Custom Dashboard field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_custom_admin_footer_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->enable_custom_admin_footer_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_custom_admin_footer_sanitize();

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</p>';

		echo $html;
	}
}
