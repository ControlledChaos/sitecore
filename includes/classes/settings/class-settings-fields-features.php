<?php
/**
 * Features settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Features extends Settings_Fields {

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
				'id'       => 'meta_site_keywords',
				'title'    => __( 'Site Keywords', 'sitecore' ),
				'callback' => [ $this, 'meta_site_keywords_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-features',
				'type'     => 'textarea',
				'args'     => [
					'description' => null,
					'label_for'   => 'meta_site_keywords',
					'class'       => 'meta-tags-field'
				]
			]
		];

		parent :: __construct(
			null,
			$fields
		);
	}

	/**
	 * Sanitize Site Keywords field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function meta_site_keywords_sanitize() {
		$option = wp_strip_all_tags( get_option( 'meta_site_keywords' ), false );
		return apply_filters( 'scp_meta_site_keywords', $option );
	}

	/**
	 * Site Keywords callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_site_keywords_callback() {

		$fields   = $this->settings_fields;
		$field_id = 'meta_site_keywords';
		$option   = $this->meta_site_keywords_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			__( 'Site Keywords', 'sitecore' )
		);
		$html .= sprintf(
			'<p>%s</p>',
			__( 'Add one keyword or phrase per line.', 'sitecore' )
		);
		$html .= sprintf(
			'<textarea id="%s" name="%s" rows="5" cols="50">%s</textarea>',
			$field_id,
			$field_id,
			$option
		);
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'The keywords meta tag will not print if this field is left empty.', 'sitecore' )
		);
		$html .= '</fieldset>';

		echo $html;
	}
}
