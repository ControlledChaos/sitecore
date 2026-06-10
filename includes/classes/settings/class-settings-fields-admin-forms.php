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

		$fields = [
			[
				'id'       => 'email_from_name',
				'title'    => __( 'Email From Text', 'sitecore' ),
				'callback' => [ $this, 'email_from_name_callback' ],
				'page'     => 'options-admin',
				'section'  => 'scp-settings-section-admin-forms',
				'type'     => 'checkbox',
				'args'     => [
					'description' => sprintf(
						__( 'For the "From" line in emails sent from the %s platform.', 'sitecore' ),
						platform_name()
					),
					'label_for' => 'email_from_name',
					'class'     => 'admin-field'
				]
			]
		];

		parent :: __construct(
			null,
			$fields
		);
	}

	/**
	 * Sanitize Email From Line field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function email_from_name_sanitize() {

		$option = get_option( 'email_from_name', '' );

		if ( ! empty( $option ) && ! ctype_space( $option ) ) {
			$option = esc_html( $option );
			return apply_filters( 'scp_email_from_name', $option );
		}
		return null;
	}

	/**
	 * Email From Line field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function email_from_name_callback() {

		$fields   = $this->settings_fields;
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->email_from_name_sanitize();

		$html = sprintf(
			'<fieldset><legend class="screen-reader-text">%s</legend>',
			$fields[$order]['title']
		);
		$html .= sprintf(
			'<input type="text" id="%s" name="%s" value="%s" placeholder="%s" />',
			$field_id,
			$field_id,
			$option,
			__( 'Enter Text', 'sitecore' )
		);
		$html .= '</fieldset>';
		$html .= sprintf(
			'<p class="description">%s</p>',
			$fields[$order]['args']['description']
		);

		echo $html;
	}
}