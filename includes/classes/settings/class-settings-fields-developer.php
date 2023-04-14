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

use function SiteCore\Core\platform_name;

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
				'id'       => 'update_in_progress',
				'title'    => __( 'Update in Progress', 'sitecore' ),
				'callback' => [ $this, 'update_in_progress_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => sprintf(
						__( 'Fix another %s update already in progress.', 'sitecore' ),
						platform_name()
					),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'direction_switch',
				'title'    => __( 'Direction Switcher', 'sitecore' ),
				'callback' => [ $this, 'direction_switch_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Easily switch backend and frontend screens between left-to-right and right-to-left orientations.', 'sitecore' ),
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
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'disable_site_health',
				'title'    => __( 'Disable Site Health', 'sitecore' ),
				'callback' => [ $this, 'disable_site_health_callback' ],
				'page'     => 'developer-tools',
				'section'  => 'scp-options-developer',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Disable WordPress\' site health feature.', 'sitecore' ),
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
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Update in Progress field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function update_in_progress_order() {
		return 0;
	}

	/**
	 * Direction Switcher field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function direction_switch_order() {
		return 1;
	}

	/**
	 * Customizer Reset field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function customizer_reset_order() {
		return 2;
	}

	/**
	 * Disable site health
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function disable_site_health_order() {
		return 3;
	}

	/**
	 * Disable FloC field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function disable_floc_order() {
		return 4;
	}

	/**
	 * Sanitize Update in Progress field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function update_in_progress_sanitize() {

		$option = get_option( 'update_in_progress', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
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
	 * Sanitize Disable Site Health field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function disable_site_health_sanitize() {

		$option = get_option( 'disable_site_health', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_disable_site_health', $option );
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
	 * Update in Progress field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function update_in_progress_callback() {

		if ( version_compare( get_bloginfo( 'version' ),'4.5', '>=' ) ) {
			$lock = get_option( 'core_updater.lock', null );
		} else {
			$lock = get_option( 'core_updater', null );
		}
		$no_lock = sprintf(
			__( '<p>There is no update lock issue.</p>', 'sitecore' ),
			admin_url( 'update-core.php' )
		);
		$no_lock .= sprintf(
			__( '<p class="description">Go to the <a href="%s">Updates page</a>.</p>', 'sitecore' ),
			admin_url( 'update-core.php' )
		);

		$fields   = $this->settings_fields;
		$order    = $this->update_in_progress_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->update_in_progress_sanitize();

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

		if ( get_option( 'update_in_progress' ) ) {

			if ( $lock ) {
				update_option( 'update_in_progress', false );
			}

			if ( version_compare( get_bloginfo( 'version' ),'4.5', '>=' ) ) {
				delete_option( 'core_updater.lock' );
			} else {
				delete_option( 'core_updater' );
			}
			$html = $no_lock;
		}

		if ( ! $lock ) {
			$html = $no_lock;
		}

		echo $html;
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
			__( 'Adds a button in the Customizer panel header.', 'sitecore' )
		);

		echo $html;
	}

	/**
	 * Disable Site Health field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_site_health_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->disable_site_health_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->disable_site_health_sanitize();

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
			__( 'Removes the dashboard widget and the menu entry, disables site health notifications.', 'sitecore' )
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
			__( 'Adds an http header to disable FLoC.', 'sitecore' )
		);

		echo $html;
	}
}
