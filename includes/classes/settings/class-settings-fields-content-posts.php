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
				'title'    => __( 'Change Posts to News', 'sitecore' ),
				'callback' => [ $this, 'posts_to_news' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to change blog posts to news posts.', 'sitecore' ),
					'label_for'   => 'posts_to_news',
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'remove_blog',
				'title'    => __( 'Remove Blog', 'sitecore' ),
				'callback' => [ $this, 'remove_blog' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to entirely remove the blogging feature and associated content or widgets.', 'sitecore' ),
					'label_for'   => 'remove_blog',
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'disable_block_widgets',
				'title'    => __( 'Disable Block Widgets', 'sitecore' ),
				'callback' => [ $this, 'disable_block_widgets' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to disable block widgets in favor of classic widgets.', 'sitecore' ),
					'label_for'   => 'disable_block_widgets',
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'enable_link_manager',
				'title'    => __( 'Enable Classic Links', 'sitecore' ),
				'callback' => [ $this, 'enable_link_manager' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'boolean',
				'args'     => [
					'description' => __( 'Check to enable the link manager and links widget.', 'sitecore' ),
					'label_for'   => 'enable_link_manager',
					'class'       => 'content-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Posts to news field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function posts_to_news() {

		$fields   = $this->settings_fields;
		$field_id = $fields[0]['id'];
		$option   = get_option( $field_id, false );

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

	/**
	 * Remove blog field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function remove_blog() {

		$fields   = $this->settings_fields;
		$field_id = $fields[1]['id'];
		$option   = get_option( $field_id, false );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[1]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}

	/**
	 * Block widgets field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_block_widgets() {

		$fields   = $this->settings_fields;
		$field_id = $fields[2]['id'];
		$option   = get_option( $field_id, true );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[2]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}

	/**
	 * Link manager field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_link_manager() {

		$fields   = $this->settings_fields;
		$field_id = $fields[3]['id'];
		$option   = get_option( $field_id, false );

		$html = '<p>';
		$html .= sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s /> %s',
			$field_id,
			$field_id,
			checked( 1, $option, false ),
			$fields[3]['args']['description']
		);
		$html .= '<p>';

		echo $html;
	}
}
