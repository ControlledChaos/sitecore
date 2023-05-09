<?php
/**
 * Media image fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Media_Images extends Settings_Fields {

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
				'id'       => 'hard_crop_medium',
				'title'    => __( 'Medium Size Crop', 'sitecore' ),
				'callback' => [ $this, 'hard_crop_medium_callback' ],
				'page'     => 'media',
				'section'  => 'default',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Crop medium images to exact dimensions.', 'sitecore' ),
					'class'       => 'media-field'
				]
			],
			[
				'id'       => 'hard_crop_large',
				'title'    => __( 'Large Size Crop', 'sitecore' ),
				'callback' => [ $this, 'hard_crop_large_callback' ],
				'page'     => 'media',
				'section'  => 'default',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Crop large images to exact dimensions.', 'sitecore' ),
					'class'       => 'media-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Sanitize Medium Size Crop field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function hard_crop_medium_sanitize() {

		$option = get_option( 'hard_crop_medium', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_hard_crop_medium', $option );
	}

	/**
	 * Sanitize Large Size Crop field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function hard_crop_large_sanitize() {

		$option = get_option( 'hard_crop_large', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_hard_crop_large', $option );
	}

	/**
	 * Medium Size Crop callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function hard_crop_medium_callback() {

		$fields   = $this->settings_fields;
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->hard_crop_medium_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
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
		$html .= '</label>';
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Large Size Crop callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function hard_crop_large_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->hard_crop_large_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
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
		$html .= '</label>';
		$html .= '</fieldset>';

		echo $html;
	}
}
