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

	/**
	 * Platform Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function toolbar_remove_platform_link() {

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
}
