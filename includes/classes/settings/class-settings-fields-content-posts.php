<?php
/**
 * Sample settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Content_Posts extends Settings_Fields {

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
				'id'       => 'posts_to_news',
				'title'    => __( 'Posts to News', 'sitecore' ),
				'callback' => [ $this, 'posts_to_news' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to change blog posts to news posts.', 'sitecore' ),
					'label_for'   => 'posts_to_news',
					'class'       => 'content-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Posts to news field
	 *
	 * Check to change blog posts to news posts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function posts_to_news() {

		$fields   = $this->settings_fields;
		$field_id = $fields[0]['id'];
		$option   = get_option( $field_id );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[0]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}
}
