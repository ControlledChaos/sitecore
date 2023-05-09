<?php
/**
 * Developer content tools settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Developer_Content extends Settings_Fields {

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
				'id'       => 'enable_dynamic_post_types',
				'title'    => __( 'Enable Post Types', 'sitecore' ),
				'callback' => [ $this, 'enable_dynamic_post_types_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-content',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Allow the addition and management of custom post types via user interface.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'enable_dynamic_taxonomies',
				'title'    => __( 'Enable Taxonomies', 'sitecore' ),
				'callback' => [ $this, 'enable_dynamic_taxonomies_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-content',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Allow the addition and management of custom taxonomies via user interface.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'enable_dynamic_block_types',
				'title'    => __( 'Enable Block Types', 'sitecore' ),
				'callback' => [ $this, 'enable_dynamic_block_types_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-content',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Allow the addition and management of custom block types via user interface.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			]
		];

		if ( class_exists( 'acfe' ) ) {
			$fields = array_merge( $fields, $acfe_fields );
		}

		$acfe_pro_fields = [
			[
				'id'       => 'enable_dynamic_templates',
				'title'    => __( 'Enable Edit Templates', 'sitecore' ),
				'callback' => [ $this, 'enable_dynamic_templates_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-content',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Allow the addition and management of custom editing templates via user interface.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			]
		];

		if ( class_exists( 'acfe_pro' ) ) {
			$fields = array_merge( $fields, $acfe_pro_fields );
		}

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Sanitize Enable Post Types field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_dynamic_post_types_sanitize() {

		$option = get_option( 'enable_dynamic_post_types', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Sanitize Enable Taxonomies field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_dynamic_taxonomies_sanitize() {

		$option = get_option( 'enable_dynamic_taxonomies', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Sanitize Enable Block Types field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_dynamic_block_types_sanitize() {

		$option = get_option( 'enable_dynamic_block_types', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Sanitize Enable Templates field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_dynamic_templates_sanitize() {

		$option = get_option( 'enable_dynamic_templates', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Enable Post Types field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_dynamic_post_types_callback() {

		$fields   = $this->settings_fields;
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_dynamic_post_types_sanitize();

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
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a link under Content in the admin menu.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Enable Taxonomies field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_dynamic_taxonomies_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_dynamic_taxonomies_sanitize();

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
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a link under Content in the admin menu.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Enable Block Types field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_dynamic_block_types_callback() {

		$fields   = $this->settings_fields;
		$order    = 2;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_dynamic_block_types_sanitize();

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
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a link under Content in the admin menu.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Enable Templates field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_dynamic_templates_callback() {

		$fields   = $this->settings_fields;
		$order    = 3;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_dynamic_templates_sanitize();

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
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Adds a link under Content in the admin menu.', 'sitecore' )
		);

		echo $html;
	}
}
