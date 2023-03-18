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

class Settings_Fields_Admin_Dashboard extends Settings_Fields {

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
				'id'       => 'enable_custom_dashboard',
				'title'    => __( 'Custom Dashboard', 'sitecore' ),
				'callback' => [ $this, 'enable_custom_dashboard_field_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-dashboard',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to replace the default dashboard with a custom dasnboard for this website.', 'sitecore' ),
					'label_for'   => 'enable_custom_dashboard',
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
	public function enable_custom_dashboard_order() {
		return 0;
	}

	/**
	 * Sanitize Custom Dashboard field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_custom_dashboard_sanitize() {

		$option = get_option( 'enable_custom_dashboard', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_custom_dashboard', $option );
	}

	/**
	 * Custom Dashboard field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_custom_dashboard_field_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->enable_custom_dashboard_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_custom_dashboard_sanitize();

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
