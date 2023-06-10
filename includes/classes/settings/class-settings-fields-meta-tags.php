<?php
/**
 * Meta tags settings fields
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

class Settings_Fields_Meta_Tags extends Settings_Fields {

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
				'id'       => 'meta_site_keywords',
				'title'    => __( 'Site Keywords', 'sitecore' ),
				'callback' => [ $this, 'meta_site_keywords_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'textarea',
				'args'     => [
					'description' => null,
					'class'       => 'meta-tags-field'
				]
			]
		];

		$front_desc = [
			[
				'id'       => 'meta_description_front_page',
				'title'    => __( 'Front Page Description', 'sitecore' ),
				'callback' => [ $this, 'meta_description_front_page_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'radio',
				'args'     => [
					'description' => null,
					'class'       => 'meta-tags-field'
				]
			]
		];

		if ( 'page' === get_option( 'show_on_front' ) ) {
			$fields = array_merge( $fields, $front_desc );
		}

		$blog_desc = [
			[
				'id'       => 'meta_description_blog_index',
				'title'    => sprintf(
					__( '%s Description', 'sitecore' ),
					ucwords( $this->posts_name() )
				),
				'callback' => [ $this, 'meta_description_blog_index_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'text',
				'args'     => [
					'description' => null,
					'class'       => 'meta-tags-field'
				]
			]
		];

		if ( ! get_option( 'remove_blog', false ) ) {
			$fields = array_merge( $fields, $blog_desc );
		}

		parent :: __construct(
			null,
			$fields
		);
	}

	/**
	 * Posts name
	 *
	 * Wether posts index is 'news' or 'blog'.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the name of the posts index.
	 */
	public function posts_name() {

		$name = __( 'blog', 'sitecore' );

		if ( get_option( 'posts_to_news' ) ) {
			$name = __( 'news', 'sitecore' );
		}
		return $name;
	}

	/**
	 * Sanitize Site Keywords field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function meta_site_keywords_sanitize() {
		$option = wp_strip_all_tags( get_option( 'meta_site_keywords' ), false );
		return apply_filters( 'scp_meta_site_keywords', $option );
	}

	/**
	 * Sanitize Front Page Description field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function meta_description_front_page_sanitize() {

		$option = get_option( 'meta_description_front_page' );
		$valid  = [ 'tagline', 'excerpt' ];

		if ( in_array( $option, $valid ) ) {
			$option = $option;
		} else {
			$option = 'tagline';
		}
		return apply_filters( 'scp_meta_description_front_page', $option );
	}

	/**
	 * Sanitize Blog Description field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function meta_description_blog_index_sanitize() {

		$option = wp_strip_all_tags( get_option( 'meta_description_blog_index' ), false );
		return apply_filters( 'scp_meta_description_blog_index', $option );
	}

	/**
	 * Site Keywords callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_site_keywords_callback() {

		$fields   = $this->settings_fields;
		$field_id = 'meta_site_keywords';
		$option   = $this->meta_site_keywords_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			__( 'Site Keywords', 'sitecore' )
		);
		$html .= sprintf(
			'<textarea id="%s" name="%s" rows="3" cols="50">%s</textarea>',
			$field_id,
			$field_id,
			$option
		);
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'Separate keywords and phrases with commas. The keywords meta tag will not print if this field is left empty.', 'sitecore' )
		);
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Front Page Description callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_description_front_page_callback() {

		$fields   = $this->settings_fields;
		$field_id = 'meta_description_front_page';
		$option   = $this->meta_description_front_page_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			__( 'Front Page Description', 'sitecore' )
		);
		$html .= sprintf(
			'<label for="%s">',
			$field_id . '_tagline'
		);
		$html .= sprintf(
			'<input type="radio" id="%s" name="%s" value="tagline" %s /> %s',
			$field_id . '_tagline',
			$field_id,
			checked( 'tagline', $option, false ),
			__( 'Site Tagline', 'sitecore' )
		);
		$html .= '</label><br />';
		$html .= sprintf(
			'<label for="%s">',
			$field_id . '_excerpt'
		);
		$html .= sprintf(
			'<input type="radio" id="%s" name="%s" value="excerpt" %s /> %s',
			$field_id . '_excerpt',
			$field_id,
			checked( 'excerpt', $option, false ),
			__( 'Page Summary', 'sitecore' )
		);
		$html .= '</label>';
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Blog Description field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_description_blog_index_callback() {

		$fields   = $this->settings_fields;
		$field_id = 'meta_description_blog_index';
		$option   = $this->meta_description_blog_index_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			__( '<legend class="screen-reader-text">%s Description</legend>', 'sitecore' ),
			ucwords( $this->posts_name() )
		);
		$html .= sprintf(
			'<input id="%s" class="regular-text" name="%s" type="text" value="%s" placeholder="%s" />',
			$field_id,
			$field_id,
			$option,
			__( 'Enter text&hellip;', 'sitecore' )
		);
		$html .= sprintf(
			__( '<p class="description">Description tag for %s index pages.</p>', 'sitecore' ),
			$this->posts_name()
		);
		$html .= '</fieldset>';

		echo $html;
	}
}
