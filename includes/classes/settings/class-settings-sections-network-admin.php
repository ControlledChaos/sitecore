<?php
/**
 * Network admin settings sections
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Sections_Network_Admin extends Settings_Sections {

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
				'id'       => 'scp-settings-section-network-admin-menu',
				'title'    => __( 'Menu Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-network-admin-menu'
				]
			],
			[
				'id'       => 'scp-settings-section-network-admin-dashboard',
				'title'    => __( 'Dashboard Settings', 'sitecore' ),
				'callback' => '',
				'page'     => 'options-admin',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'settings-section-network-admin-dashboard'
				]
			]
		];

		parent :: __construct(
			$sections
		);
	}
}
