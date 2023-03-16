<?php
/**
 * Sample settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Sample extends Settings_Fields {

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
				'id'       => 'sample_field_one',
				'title'    => __( 'Sample Field #1', 'sitecore' ),
				'callback' => [ $this, 'sample_field_one' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Sample field one description.', 'sitecore' ),
					'label_for'   => 'sample_field_one',
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_two',
				'title'    => __( 'Sample Field #2', 'sitecore' ),
				'callback' => [ $this, 'sample_field_two' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Sample field two description.', 'sitecore' ),
					'label_for'   => 'sample_field_two',
					'class'       => 'sample-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	public function sample_field_one() {

		$fields   = $this->settings_fields;
		$field_id = $fields[0]['id'];
		$option   = get_option( $field_id );

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

	public function sample_field_two() {

		$fields   = $this->settings_fields;
		$field_id = $fields[1]['id'];
		$option   = get_option( $field_id );

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
