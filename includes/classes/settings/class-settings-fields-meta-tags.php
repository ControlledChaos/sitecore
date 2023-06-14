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

use function SiteCore\Meta_Tags\copyright_default;

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
					'label_for'   => 'meta_site_keywords',
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

		$blog_front = [
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
					'label_for'   => 'meta_description_blog_index',
					'class'       => 'meta-tags-field'
				]
			],
			[
				'id'       => 'meta_image_blog_index',
				'title'    => sprintf(
					__( '%s Image', 'sitecore' ),
					ucwords( $this->posts_name() )
				),
				'callback' => [ $this, 'meta_image_blog_index_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'text',
				'args'     => [
					'description' => null,
					'label_for'   => 'meta_image_blog_index',
					'class'       => 'meta-tags-field'
				]
			]
		];

		if (
			! get_option( 'remove_blog', false ) &&
			'posts' === get_option( 'show_on_front' )
		) {
			$fields = array_merge( $fields, $blog_front );
		}

		$more_fields = [
			[
				'id'       => 'meta_image_archive',
				'title'    => __( 'Archive Image', 'sitecore' ),
				'callback' => [ $this, 'meta_image_archive_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'text',
				'args'     => [
					'description' => null,
					'label_for'   => 'meta_image_archive',
					'class'       => 'meta-tags-field'
				]
			],
			[
				'id'       => 'meta_site_copyright',
				'title'    => __( 'Site Copyright', 'sitecore' ),
				'callback' => [ $this, 'meta_site_copyright_callback' ],
				'page'     => 'custom-content',
				'section'  => 'scp-options-meta-tags',
				'type'     => 'text',
				'args'     => [
					'description' => null,
					'label_for'   => 'meta_site_copyright',
					'class'       => 'meta-tags-field'
				]
			]
		];
		$fields = array_merge( $fields, $more_fields );

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
	 * Sanitize Blog Image field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_image_blog_index_sanitize() {

		$option = get_option( 'meta_image_blog_index' );
		return $option;
	}

	/**
	 * Sanitize Archive Image field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_image_archive_sanitize() {

		$option = get_option( 'meta_image_archive' );
		return $option;
	}

	/**
	 * Sanitize Site Copyright field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean
	 */
	public function meta_site_copyright_sanitize() {

		$default = copyright_default();
		$option  = wp_strip_all_tags( get_option( 'meta_site_copyright' ), false );

		if ( ! empty( $option ) ) {
			$option = $option;
		} else {
			$option = $default;
		}
		return apply_filters( 'scp_meta_site_copyright', $option );
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
			'<p>%s</p>',
			__( 'Add one keyword or phrases per line.', 'sitecore' )
		);
		$html .= sprintf(
			'<textarea id="%s" name="%s" rows="5" cols="50">%s</textarea>',
			$field_id,
			$field_id,
			$option
		);
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( 'The keywords meta tag will not print if this field is left empty.', 'sitecore' )
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

	/**
	 * Blog Image field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_image_blog_index_callback() {

		$option = $this->meta_image_blog_index_sanitize();
		$src    = $this->image_preview_src( $option );

		$disabled = '';
		if ( empty( $option ) ) {
			$disabled = 'disabled';
		}

		$upload = __( 'Add Image', 'sitecore' );
		if ( ! empty( $option ) ) {
			$upload = __( 'Replace Image', 'sitecore' );
		}

		?>
		<figure style="margin: 0; max-width: 360px;">
			<img style="max-width: 100%; height: auto;" id="meta-image-blog-index-preview" src="<?php echo esc_attr( $src ); ?>" />
			<figcaption class="screen-reader-text"><?php _e( 'Posts index meta image preview', 'sitecore' ); ?></figcaption>
		</figure>
		<p>
			<input type="hidden" id="meta-image-blog-index-upload-field" name="meta_image_blog_index" id="meta_image_blog_index" value="<?php echo esc_attr( $option ); ?>" />
			<input type="button" id="meta-image-blog-index-upload-button" class="button button-primary" value="<?php echo $upload; ?>" />
			<input type="button" id="meta-image-blog-index-remove-button" class="button" value="<?php _e( 'Remove', 'sitecore' ); ?>" <?php echo $disabled; ?> />
		</p>
	<?php
	}

	/**
	 * Archive Image field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_image_archive_callback() {

		$option = $this->meta_image_archive_sanitize();
		$src    = $this->image_preview_src( $option );

		$disabled = '';
		if ( empty( $option ) ) {
			$disabled = 'disabled';
		}

		$upload = __( 'Add Image', 'sitecore' );
		if ( ! empty( $option ) ) {
			$upload = __( 'Replace Image', 'sitecore' );
		}

		?>
		<figure style="margin: 0; max-width: 360px;">
			<img style="max-width: 100%; height: auto;" id="meta-image-archive-preview" src="<?php echo esc_attr( $src ); ?>" />
			<figcaption class="screen-reader-text"><?php _e( 'Archive meta image preview', 'sitecore' ); ?></figcaption>
		</figure>
		<p>
			<input type="hidden" id="meta-image-archive-upload-field" name="meta_image_archive" id="meta_image_archive" value="<?php echo esc_attr( $option ); ?>" />
			<input type="button" id="meta-image-archive-upload-button" class="button button-primary" value="<?php echo $upload; ?>" />
			<input type="button" id="meta-image-archive-remove-button" class="button" value="<?php _e( 'Remove', 'sitecore' ); ?>" <?php echo $disabled; ?> />
		</p>
	<?php
	}

	/**
	 * Site Copyright field callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function meta_site_copyright_callback() {

		$fields   = $this->settings_fields;
		$field_id = 'meta_site_copyright';
		$option   = $this->meta_site_copyright_sanitize();

		$html = '<fieldset>';
		$html .= sprintf(
			'<legend class="screen-reader-text">%s</legend>',
			__( 'Site Copyright', 'sitecore' )
		);
		$html .= sprintf(
			'<input id="%s" class="regular-text" name="%s" type="text" value="%s" placeholder="%s" />',
			$field_id,
			$field_id,
			$option,
			__( 'Enter text&hellip;', 'sitecore' )
		);
		$html .= sprintf(
			__( '<p class="description">Use %s for the copyright symbol. Use %s for the current year. Use %s for the website name.</p>', 'sitecore' ),
			'<code style="user-select: all">%copy%</code>',
			'<code style="user-select: all">%year%</code>',
			'<code style="user-select: all">%name%</code>'
		);
		$html .= '</fieldset>';

		echo $html;
	}

	/**
	 * Image preview src attribute
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $id Thr attachment ID.
	 * @return string Returns the image URL or
	 *                empty if no default filtered.
	 */
	public function image_preview_src( $id = '' ) {

		if ( empty( $id ) ) {
			$src = apply_filters( 'scp_default_meta_image_src', '' );

			if ( ! has_filter( 'scp_default_meta_image_src', false ) ) {
				return '';
			}
		}

		if ( has_image_size( 'meta-image' ) ) {
			$size = 'meta-image';
		} else {
			$size = 'large';
		}

		$image = wp_get_attachment_image_src( $id, $size, false );

		if ( is_array( $image ) ) {
			$src = $image[0];
		}
		return $src;
	}
}
