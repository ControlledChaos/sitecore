<?php
/**
 * Sample settings sections
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Sections_Admin extends Settings_Sections {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$sections = [
			[
				'id'       => 'scp-settings-section-admin-menu',
				'title'    => __( 'Menu Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-menu'
				]
			],
			[
				'id'       => 'scp-settings-section-admin-dashboard',
				'title'    => __( 'Dashboard Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-dashboard'
				]
			],
			[
				'id'       => 'scp-settings-section-admin-toolbar',
				'title'    => __( 'Toolbar Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-toolbar'
				]
			],
			[
				'id'       => 'scp-settings-section-admin-header',
				'title'    => __( 'Header Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-header'
				]
			],[
				'id'       => 'scp-settings-section-admin-header',
				'title'    => __( 'header Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-header'
				]
			],
			[
				'id'       => 'scp-settings-section-admin-users',
				'title'    => __( 'User Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-admin-users'
				]
			]
		];

		parent :: __construct(
			$sections
		);
	}
}
