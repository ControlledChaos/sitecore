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
				'callback' => [ $this, 'sample_field_one_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Sample field one description.', 'sitecore' ),
					'label_for'   => 'sample_field_one',
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_two',
				'title'    => __( 'Sample Field #2', 'sitecore' ),
				'callback' => [ $this, 'sample_field_two_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'checkbox',
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

	/**
	 * Sample Field #1 field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function sample_field_one_order() {
		return 0;
	}

	/**
	 * Sample Field #2 field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function sample_field_two_order() {
		return 1;
	}

	/**
	 * Sanitize Sample Field #1 field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function sample_field_one_sanitize() {

		$option = get_option( 'sample_field_one', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_sample_field_one', $option );
	}

	/**
	 * Sanitize Sample Field #2 field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function sample_field_two_sanitize() {

		$option = get_option( 'sample_field_two', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_sample_field_two', $option );
	}

	/**
	 * Sample Field #1 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_one_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->sample_field_one_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_one_sanitize();

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

	/**
	 * Sample Field #2 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_two_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->sample_field_two_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_two_sanitize();

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
