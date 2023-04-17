<?php
/**
 * Admin forms settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

use function SiteCore\Core\platform_name;

class Settings_Fields_Admin_Forms extends Settings_Fields {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$fields = [];

		$acfe_fields = [
			[
				'id'       => 'acfe_enable_forms_ui',
				'title'    => __( 'Enable ACFE Forms UI', 'sitecore' ),
				'callback' => [ $this, 'acfe_enable_forms_ui_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-forms',
				'type'     => 'checkbox',
				'args'     => [
					'description' => sprintf(
						__( 'Makes native %s form pages two columns with the publish action to the side.', 'sitecore' ),
						platform_name()
					),
					'class'       => 'admin-field'
				]
			]
		];

		if ( class_exists( 'acfe' ) ) {
			$fields = array_merge( $acfe_fields, $fields );
		}

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Platform Link field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function acfe_enable_forms_ui_order() {
		return 0;
	}

	/**
	 * Sanitize Platform Link field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function acfe_enable_forms_ui_sanitize() {

		$option = get_option( 'acfe_enable_forms_ui', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_acfe_enable_forms_ui', $option );
	}

	/**
	 * Platform Link field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acfe_enable_forms_ui_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->acfe_enable_forms_ui_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->acfe_enable_forms_ui_sanitize();

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
}
