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

		$array = [
			'section'    => 'scp-settings-section-sample',
			'add_fields' => false
		];

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
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_two',
				'title'    => __( 'Sample Field #2', 'sitecore' ),
				'callback' => [ $this, 'sample_field_two_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'radio',
				'args'     => [
					'description' => __( 'Sample field two description.', 'sitecore' ),
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_three',
				'title'    => __( 'Sample Field #3', 'sitecore' ),
				'callback' => [ $this, 'sample_field_three_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'select',
				'args'     => [
					'description' => __( 'Sample field three description.', 'sitecore' ),
					'label_for'   => 'sample_field_three',
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_four',
				'title'    => __( 'Sample Field #4', 'sitecore' ),
				'callback' => [ $this, 'sample_field_four_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'text',
				'args'     => [
					'description' => __( 'Sample field four description.', 'sitecore' ),
					'label_for'   => 'sample_field_four',
					'class'       => 'sample-field'
				]
			],
			[
				'id'       => 'sample_field_five',
				'title'    => __( 'Sample Field #5', 'sitecore' ),
				'callback' => [ $this, 'sample_field_five_callback' ],
				'page'     => 'general',
				'section'  => 'scp-settings-section-sample',
				'type'     => 'textarea',
				'args'     => [
					'description' => __( 'Sample field five description.', 'sitecore' ),
					'label_for'   => 'sample_field_five',
					'class'       => 'sample-field'
				]
			]
		];

		parent :: __construct(
			null,
			$fields
		);
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

		$option = get_option( 'sample_field_two' );
		$valid  = [ 'a', 'b' ];

		if ( in_array( $option, $valid ) ) {
			$option = $option;
		} else {
			$option = 'b';
		}
		return apply_filters( 'scp_sample_field_two', $option );
	}

	/**
	 * Sanitize Sample Field #3 field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function sample_field_three_sanitize() {

		$option = get_option( 'sample_field_three' );
		$valid  = [ 'one', 'two' ];

		if ( in_array( $option, $valid ) ) {
			$option = $option;
		} else {
			$option = '';
		}
		return apply_filters( 'scp_sample_field_three', $option );
	}

	/**
	 * Sanitize Sample Field #4 field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function sample_field_four_sanitize() {
		$option = wp_strip_all_tags( get_option( 'sample_field_four' ), false );
		return apply_filters( 'scp_sample_field_four', $option );
	}

	/**
	 * Sanitize Sample Field #5 field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function sample_field_five_sanitize() {
		$option = wp_strip_all_tags( get_option( 'sample_field_five' ), false );
		return apply_filters( 'scp_sample_field_five', $option );
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
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_one_sanitize();

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
	 * Sample Field #2 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_two_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_two_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<label for="%s">',
			$field_id . '_a'
		);
		$html .= sprintf(
			'<input type="radio" id="%s" name="%s" value="a" %s /> %s',
			$field_id . '_a',
			$field_id,
			checked( 'a', $option, false ),
			__( 'Option A', 'sitecore' )
		);
		$html .= '</label><br />';
		$html .= sprintf(
			'<label for="%s">',
			$field_id . '_b'
		);
		$html .= sprintf(
			'<input type="radio" id="%s" name="%s" value="b" %s /> %s',
			$field_id . '_b',
			$field_id,
			checked( 'b', $option, false ),
			__( 'Option B', 'sitecore' )
		);
		$html .= '</label>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			$fields[$order]['args']['description']
		);
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Sample Field #3 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_three_callback() {

		$fields   = $this->settings_fields;
		$order    = 2;
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_three_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<select id="%s" name="%s">',
			$field_id,
			$field_id
		);
		$html .= sprintf(
			'<option value="">%s</option>',
			__( 'Choose your option&hellip;', 'sitecore' )
		);
		$html .= sprintf(
			'<option value="one" %s>%s</option>',
			selected( $option, 'one', false ),
			__( 'Option One', 'sitecore' )
		);
		$html .= sprintf(
			'<option value="two" %s>%s</option>',
			selected( $option, 'two', false ),
			__( 'Option Two', 'sitecore' )
		);
		$html .= '</select>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			$fields[$order]['args']['description']
		);
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Sample Field #4 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_four_callback() {

		$fields   = $this->settings_fields;
		$order    = 3;
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_four_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<input id="%s" class="regular-text" name="%s" type="text" value="%s" placeholder="%s" />',
			$field_id,
			$field_id,
			$option,
			__( 'Enter text&hellip;', 'sitecore' )
		);
		$html .= sprintf(
			'<p class="description">%s</p>',
			$fields[$order]['args']['description']
		);
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Sample Field #5 callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_field_five_callback() {

		$fields   = $this->settings_fields;
		$order    = 4;
		$field_id = $fields[$order]['id'];
		$option   = $this->sample_field_five_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<textarea id="%s" name="%s" rows="6" cols="50">%s</textarea>',
			$field_id,
			$field_id,
			$option
		);
		$html .= sprintf(
			'<p class="description">%s</p>',
			$fields[$order]['args']['description']
		);
		$html .= '</fieldset>';

		echo $html;
	}
}
