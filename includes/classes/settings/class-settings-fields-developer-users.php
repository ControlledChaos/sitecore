<?php
/**
 * Developer user tools settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Developer_Users extends Settings_Fields {

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
				'id'       => 'enable_dynamic_forms',
				'title'    => __( 'Enable Forms', 'sitecore' ),
				'callback' => [ $this, 'enable_dynamic_forms_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-users',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Allow dynamic forms to which field groups are added. For frontend user forms.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'dev_access',
				'title'    => __( 'Developer Access', 'sitecore' ),
				'callback' => [ $this, 'dev_access_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer-users',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Programmatically add a developer back door user.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			null,
			$fields
		);
	}

	/**
	 * Sanitize Enable Forms field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_dynamic_forms_sanitize() {

		$option = get_option( 'enable_dynamic_forms', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Sanitize Developer Access field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function dev_access_sanitize() {

		$option = get_option( 'dev_access', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_dev_access', $option );
	}

	/**
	 * Enable Forms field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_dynamic_forms_callback() {

		$fields   = $this->settings_fields;
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_dynamic_forms_sanitize();

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
			__( 'Adds a link under Tools in the admin menu.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Developer Access field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dev_access_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->dev_access_sanitize();

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
			__( 'This account has the "Developer" user role. Default username, email, and password set in includes/users/users.php.', 'sitecore' )
		);

		echo $html;
	}
}
