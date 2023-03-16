<?php
/**
 * Content settings sections
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Sections_Content extends Settings_Sections {

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
				'id'       => 'scp-settings-content-posts',
				'title'    => null,
				'callback' => [ $this, 'callback' ],
				'page'     => 'content-settings',
				'args'     => [
					'before_section' => '',
					'after_section'  => '',
					'section_class'  => 'content-settings-section'
				]
			]
		];

		parent :: __construct(
			$sections
		);
	}

	/**
	 * Section callback
	 *
	 * Adds content before the section fields.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function callback() {

		$html = sprintf(
			'<p>%s</p>',
			__( 'Choose options for the blog and widgets.', 'sitecore' )
		);

		echo apply_filters( 'scp_content_settings_callback', $html );
	}
}
