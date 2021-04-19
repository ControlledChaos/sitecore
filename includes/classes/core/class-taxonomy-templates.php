<?php
/**
 * Taxonomy templates
 *
 * Enables themes to use custom templates for posts in taxonomies,
 * in the same way that templates can be used for post types.
 * The template is registered in the file header.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 *
 * @example    In the template header: {Taxonomy Singular Label} Template: Grid
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'WPINC' ) ) {
	die;
}

final class Taxonomy_Templates {

	/**
	 * @var    mixed
	 * @access public
	 */
	var $meta_key;

	/**
	 * Constructor magic method.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'init' ], 100 );
		add_filter( 'category_template', [ $this, 'template' ] );
		add_filter( 'tag_template', [ $this, 'template' ] );
		add_filter( 'taxonomy_template', [ $this, 'template' ] );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function init() {

		$this->meta_key = apply_filters( 'custom_taxonomy_templates_meta_key', '_custom_template' );

		$taxonomies = get_taxonomies( [ 'public' => true ] );

		if ( empty( $taxonomies ) ) {
			return;
		}

		foreach( $taxonomies as $taxonomy ) {

			add_action( "{$taxonomy}_add_form_fields",[ $this, 'add_template_option' ] );
			add_action( "{$taxonomy}_edit_form_fields", [ $this, 'edit_template_option' ], 10, 2 );
			add_action( "created_{$taxonomy}", [ $this, 'save_option' ], 10, 2 );
			add_action( "edited_{$taxonomy}", [ $this, 'save_option' ], 10, 2 );
			add_action( "delete_{$taxonomy}", [ $this, 'delete_option' ] );

		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $template
	 * @return void
	 */
	public function template( $template ) {

		$term     = get_queried_object();
		$template = get_term_meta( $term->term_id, $this->meta_key, true );

		if ( ! empty( $template ) ) {
			$tmpl = locate_template( $template );

			// Verify that the template file exists.
			if ( '' !== $tmpl ) {
				add_filter( 'body_class',[ $this, 'body_class' ] );
				return $tmpl;
			}
		}
		return $template;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $classes
	 * @return void
	 */
	public function body_class( $classes ) {

		$term      = get_queried_object();
		$template  = get_term_meta( $term->term_id, $this->meta_key, true );
		$template  = sanitize_html_class( str_replace( '.', '-', $template ) );
		$classes[] = 'taxonomy-template-' . $template;

		return $classes;
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $term_id
	 * @return void
	 */
	public function save_option( $term_id ) {

		if ( isset( $_POST['custom-taxonomy-template'] ) ) {

			$template = trim( $_POST['custom-taxonomy-template'] );

			if ( 'default' == $template ) {
				delete_term_meta( $term_id, $this->meta_key );
			} else {
				update_term_meta( $term_id, $this->meta_key, $template );
			}
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $taxonomy
	 * @return void
	 */
	public function add_template_option( $taxonomy ) {

		$category_templates = $this->get_templates( $taxonomy );

		if ( empty( $category_templates ) ) {
			return;
		}

		?>
		<div class="form-field custom-taxonomy-template">
			<label for="custom-taxonomy-template"><?php _e( 'Template', 'sitecore' ); ?></label>
			<select name="custom-taxonomy-template" id="custom-taxonomy-template" class="postform">
				<option value="default"><?php _e( 'Default Template', 'sitecore' ); ?></option>
				<?php $this->templates_dropdown( $taxonomy ) ?>
			</select>
		</div><?php
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $tag
	 * @param [type] $taxonomy
	 * @return void
	 */
	public function edit_template_option( $tag, $taxonomy ) {

		$category_templates = $this->get_templates( $taxonomy );

		if ( empty( $category_templates ) ) {
			return;
		}

		$template = get_term_meta( $tag->term_id, $this->meta_key, true ); ?>

		<tr class="form-field custom-taxonomy-template">
			<th scope="row" valign="top">
				<label for="custom-taxonomy-template"><?php _e( 'Template', 'sitecore' ); ?></label>
			</th>
			<td>
				<select name="custom-taxonomy-template" id="custom-taxonomy-template" class="postform">
					<option value="default"><?php _e( 'Default Template', 'sitecore' ); ?></option>
					<?php $this->templates_dropdown( $taxonomy, $template ) ?>
				</select>
			</td>
		</tr><?php
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $term_id
	 * @return void
	 */
	public function delete_option( $term_id ) {
		delete_term_meta( $term_id, $this->meta_key );
	}

	/**
	 * Undocumented function
	 *
	 * @param string $taxonomy
	 * @param [type] $default
	 * @return void
	 */
	public function templates_dropdown( $taxonomy = 'category', $default = null ) {

		$templates = array_flip( $this->get_templates( $taxonomy ) );
		ksort( $templates );
		foreach( array_keys( $templates ) as $template ) {

			if ( $default == $templates[$template] ) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			echo "\n\t<option value='" . $templates[$template] . "' $selected>$template</option>";
		}
	}

	/**
	 * Get a list of taxonomy templates available in the current theme
	 *
	 * @param string $taxonomy
	 * @param [type] $template
	 * @return mixed[]
	 */
	public function get_templates( $taxonomy = 'category', $template = null ) {

		$tax = get_taxonomy( $taxonomy );

		if ( ! $tax ) {
			return [];
		}

		$templates = [];
		$theme     = wp_get_theme( $template );
		$files     = (array) $theme->get_files( 'php', 1 );

		foreach ( $files as $file => $full_path ) {

			if ( ! preg_match( "#({$tax->labels->singular_name}|{$tax->name}) Template:(.*)$#mi", file_get_contents( $full_path ), $header ) ) {
				continue;
			}
			$templates[ $file ] = _cleanup_header_comment( $header[2] );

		}

		if ( $theme->parent() ) {
			$templates += $this->get_templates( $taxonomy, $theme->get_template() );
		}
		return apply_filters( 'custom_taxonomy_templates', $templates, $taxonomy, $template );
	}
}
