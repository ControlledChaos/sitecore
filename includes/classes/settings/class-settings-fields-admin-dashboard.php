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
				'callback' => [ $this, 'enable_custom_dashboard' ],
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
	 * Custom Dashboard field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_custom_dashboard() {

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
}
