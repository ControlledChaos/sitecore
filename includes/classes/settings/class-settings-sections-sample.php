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

class Settings_Sections_Sample extends Settings_Sections {

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
				'id'       => 'scp-settings-section-sample',
				'title'    => __( 'Sample Settings Section', 'sitecore' ),
				'callback' => '',
				'page'     => 'general',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => ''
				]
			]
		];

		parent :: __construct(
			$sections
		);
	}
}
