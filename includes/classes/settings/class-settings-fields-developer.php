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

		$register = [
			'serialize' => true,
			'page'      => 'developer-tools',
			'section'   => 'scp-options-developer'
		];

		$fields = [
			[
				'id'       => 'fix_update_in_progress',
				'title'    => __( 'Update in Progress', 'sitecore' ),
				'callback' => [ $this, 'fix_update_in_progress_callback' ],
				'page'     => $register['page'],
				'section'  => $register['section'],
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
				'id'       => 'theme_test_drive',
				'title'    => __( 'Theme Test Drive', 'sitecore' ),
				'callback' => [ $this, 'theme_test_drive_callback' ],
				'page'     => $register['page'],
				'section'  => $register['section'],
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Safely test any theme while visitors view the active theme.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'direction_switch',
				'title'    => __( 'Direction Switcher', 'sitecore' ),
				'callback' => [ $this, 'direction_switch_callback' ],
				'page'     => $register['page'],
				'section'  => $register['section'],
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
				'page'     => $register['page'],
				'section'  => $register['section'],
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
				'page'     => $register['page'],
				'section'  => $register['section'],
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
				'page'     => $register['page'],
				'section'  => $register['section'],
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Disable Google\'s next-generation tracking technology.', 'sitecore' ),
					'class'       => 'admin-field'
				]
			],
			[
				'id'       => 'enable_sample_files',
				'title'    => __( 'Enable Sample Files', 'sitecore' ),
				'callback' => [ $this, 'enable_sample_files_callback' ],
				'page'     => $register['page'],
				'section'  => $register['section'],
				'type'     => 'checkbox',
				'args'     => [
					'description' => sprintf(
						__( 'Load content and pages that have been included in the %s plugin for demonstration purposes.', 'sitecore' ),
						SCP_NAME
					),
					'class'       => 'admin-field'
				]
			]
		];

		parent :: __construct(
			$register,
			$fields
		);
	}

	/**
	 * Sanitize Update in Progress field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function fix_update_in_progress_sanitize() {

		$option = get_option( 'fix_update_in_progress', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return $option;
	}

	/**
	 * Sanitize Theme Test Drive field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function theme_test_drive_sanitize() {

		$option = get_option( 'theme_test_drive', false );
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
	 * Sanitize Enable Sample Files field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_sample_files_sanitize() {

		$option = get_option( 'enable_sample_files', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_sample_files', $option );
	}

	/**
	 * Update in Progress field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function fix_update_in_progress_callback() {

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
		$order    = 0;
		$field_id = $fields[$order]['id'];
		$option   = $this->fix_update_in_progress_sanitize();

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

		if ( get_option( 'fix_update_in_progress' ) ) {

			if ( $lock ) {
				update_option( 'fix_update_in_progress', false );
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
	 * Theme Test Drive field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function theme_test_drive_callback() {

		$fields   = $this->settings_fields;
		$order    = 1;
		$field_id = $fields[$order]['id'];
		$option   = $this->theme_test_drive_sanitize();

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
			__( 'Adds a page under Appearance.', 'sitecore' )
		);

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
		$order    = 2;
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
		$order    = 3;
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
		$order    = 4;
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
		$order    = 5;
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

	/**
	 * Enable Sample Files field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_sample_files_callback() {

		$fields   = $this->settings_fields;
		$order    = 6;
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_sample_files_sanitize();

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

		echo $html;
	}
}
