<?php
/**
 * Content settings fields
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
				'callback' => [ $this, 'posts_to_news_callback' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to change blog posts to news posts.', 'sitecore' ),
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'remove_blog',
				'title'    => __( 'Remove Blog', 'sitecore' ),
				'callback' => [ $this, 'remove_blog_callback' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to entirely remove the blogging feature and associated content or widgets.', 'sitecore' ),
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'type_tax_sort_order',
				'title'    => __( 'Post Types & Taxonomies Sort Order', 'sitecore' ),
				'callback' => [ $this, 'type_tax_sort_order_callback' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to enable drag & drop sort order of post types and taxonomies.', 'sitecore' ),
					'class'       => 'content-field'
				]
			],
						[
				'id'       => 'disable_block_widgets',
				'title'    => __( 'Disable Block Widgets', 'sitecore' ),
				'callback' => [ $this, 'disable_block_widgets_callback' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to disable block widgets in favor of classic widgets.', 'sitecore' ),
					'class'       => 'content-field'
				]
			],
			[
				'id'       => 'enable_link_manager',
				'title'    => __( 'Enable Classic Links', 'sitecore' ),
				'callback' => [ $this, 'enable_link_manager_callback' ],
				'page'     => 'content-settings',
				'section'  => 'scp-settings-content-posts',
				'type'     => 'checkbox',
				'args'     => [
					'description' => __( 'Check to enable the link manager and links widget.', 'sitecore' ),
					'class'       => 'content-field'
				]
			]
		];

		parent :: __construct(
			$fields
		);
	}

	/**
	 * Posts to News field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function posts_to_news_order() {
		return 0;
	}

	/**
	 * Remove Blog field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function remove_blog_order() {
		return 1;
	}

	/**
	 * Remove Blog field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function type_tax_sort_order_order() {
		return 2;
	}

	/**
	 * Block Widgets field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function disable_block_widgets_order() {
		return 3;
	}

	/**
	 * Link Manager field order
	 *
	 * @since  1.0.0
	 * @access public
	 * @return integer Returns the placement of the field in the fields array.
	 */
	public function enable_link_manager_order() {
		return 4;
	}

	/**
	 * Sanitize Posts to News field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function posts_to_news_sanitize() {

		$option = get_option( 'posts_to_news', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_posts_to_news', $option );
	}

	/**
	 * Sanitize Remove Blog field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function remove_blog_sanitize() {

		$option = get_option( 'remove_blog', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_remove_blog', $option );
	}

	/**
	 * Sanitize Sort Order field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function type_tax_sort_order_sanitize() {

		$option = get_option( 'type_tax_sort_order', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_type_tax_sort_order', $option );
	}

	/**
	 * Sanitize Block Widgets field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function disable_block_widgets_sanitize() {

		$option = get_option( 'disable_block_widgets', true );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_disable_block_widgets', $option );
	}

	/**
	 * Sanitize Link Manager field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function enable_link_manager_sanitize() {

		$option = get_option( 'enable_link_manager', false );
		if ( true == $option ) {
			$option = true;
		} else {
			$option = false;
		}
		return apply_filters( 'scp_enable_link_manager', $option );
	}

	/**
	 * Posts to News field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function posts_to_news_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->posts_to_news_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->posts_to_news_sanitize();

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

	/**
	 * Remove Blog field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function remove_blog_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->remove_blog_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->remove_blog_sanitize();

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

	/**
	 * Sort Order field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function type_tax_sort_order_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->type_tax_sort_order_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->type_tax_sort_order_sanitize();

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

	/**
	 * Block widgets field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_block_widgets_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->disable_block_widgets_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->disable_block_widgets_sanitize();

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

	/**
	 * Link manager field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enable_link_manager_callback() {

		$fields   = $this->settings_fields;
		$order    = $this->enable_link_manager_order();
		$field_id = $fields[$order]['id'];
		$option   = $this->enable_link_manager_sanitize();

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
			'<p class="description">%s <a href="%s" target="_blank" rel="nofollow noindex">%s</a></p>',
			__( 'More information at', 'sitecore' ),
			esc_url( 'https://codex.wordpress.org/Links_Manager' ),
			'https://codex.wordpress.org/Links_Manager'
		);

		echo $html;
	}
}
