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

class Settings_Sections_Developer extends Settings_Sections {

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
				'id'       => 'scp-options-developer',
				'title'    => '',
				'callback' => '',
				'page'     => 'developer-tools',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'options-developer'
				]
			],
			[
				'id'       => 'scp-options-developer-users',
				'title'    => '',
				'callback' => '',
				'page'     => 'developer-tools',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'options-developer-users'
				]
			]
		];

		parent :: __construct(
			$sections
		);
	}
}
