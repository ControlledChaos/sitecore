<?php
/**
 * Developer tools settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Developer extends Settings_Fields {

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
				'id'       => 'direction_switch',
				'title'    => __( 'Direction Switcher', 'sitecore' ),
				'callback' => [ $this, 'direction_switch_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Easily switch backend and frontend screens between left-to-right and right-to-left orientations.', 'sitecore' ),
					'label_for'   => 'direction_switch',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'customizer_reset',
				'title'    => __( 'Customizer Reset', 'sitecore' ),
				'callback' => [ $this, 'customizer_reset_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Enable the ability to reset customizations to the active theme.', 'sitecore' ),
					'label_for'   => 'customizer_reset',
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'disable_floc',
				'title'    => __( 'Disable FloC', 'sitecore' ),
				'callback' => [ $this, 'disable_floc_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Disable Google\'s next-generation tracking technology.', 'sitecore' ),
					'label_for'   => 'disable_floc',
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Direction Switcher field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function direction_switch_order() {
		return 0;
	}

	/**
	 * Customizer Reset field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function customizer_reset_order() {
		return 1;
	}

	/**
	 * Disable FloC field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function disable_floc_order() {
		return 2;
	}

	/**
	 * Sanitize Direction Switcher field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function direction_switch_sanitize() {

		$option = get_option( 'direction_switch', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_direction_switch', $option );
	}

	/**
	 * Sanitize Customizer Reset field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function customizer_reset_sanitize() {

		$option = get_option( 'customizer_reset', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_customizer_reset', $option );
	}

	/**
	 * Sanitize Disable FloC field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function disable_floc_sanitize() {

		$option = get_option( 'disable_floc', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_disable_floc', $option );
	}

	/**
	 * Direction Switcher field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function direction_switch_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->direction_switch_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->direction_switch_sanitize();

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</p>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a button in the user toolbar.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Customizer Reset field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function customizer_reset_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->customizer_reset_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->customizer_reset_sanitize();

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</p>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a button in the Customizer panel header.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Disable FloC field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_floc_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->disable_floc_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->disable_floc_sanitize();

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[$order]['args']['description']
		);
		$html .= '</p>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds an http header to disable FLoC.', 'sitecore' )
		);

		echo $html;
	}
}