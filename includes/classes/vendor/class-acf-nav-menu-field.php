<?php
/**
 * ACF navigation menu field
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Classes\Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class ACF_Nav_Menu_Field extends \acf_field {

	/**
	 * Initialize
	 *
	 * This function will setup the field type data.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function initialize() {

		$this->name     = 'nav_menu';
		$this->label    = __( 'Nav Menu', 'sitecore' );
		$this->category = 'choice';
		$this->defaults = [
			'save_format' => 'menu',
			'allow_null'  => 0,
			'container'   => 'div',
		];
	}

	/**
	 *  Render field
	 *
	 *  Create the HTML interface for the field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $field Holds all the field's data.
	 * @return string
	 */
	public function render_field( $field ) {

		$allow_null = $field['allow_null'];
		$nav_menus  = $this->get_nav_menus( $allow_null );

		if ( empty( $nav_menus ) ) {
			return;
		}

		?>
		<div class="custom-acf-nav-menu">

			<select id="<?php esc_attr( $field['id'] ); ?>" class="<?php echo esc_attr( $field['class'] ); ?>" name="<?php echo esc_attr( $field['name'] ); ?>">

			<?php

			foreach( $nav_menus as $nav_menu_id => $nav_menu_name ) :

			?>
				<option value="<?php echo esc_attr( $nav_menu_id ); ?>" <?php selected( $field['value'], $nav_menu_id ); ?>>
					<?php echo ( $nav_menu_name ); ?>
				</option>
			<?php

			endforeach;

			?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render field settings
	 *
	 * Create extra options for the field. This is rendered when editing a field.
	 * The value of $field['name'] can be used (like bellow) to save extra data to the $field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $field Holds all the field's data.
	 * @return void
	 */
	public function render_field_settings( $field ) {

		// Register the Return Value format setting.
		acf_render_field_setting( $field, [
			'label'        => __( 'Return Value', 'sitecore' ),
			'instructions' => __( 'Specify the returned value on front end', 'sitecore' ),
			'type'         => 'radio',
			'name'         => 'save_format',
			'layout'       => 'horizontal',
			'choices'      => [
				'menu'   => __( 'Nav Menu HTML', 'sitecore' ),
				'object' => __( 'Nav Menu Object', 'sitecore' ),
				'id'     => __( 'Nav Menu ID', 'sitecore' ),
			],
		] );

		// Register the Menu Container setting.
		acf_render_field_setting( $field, [
			'label'        => __( 'Menu Container', 'sitecore' ),
			'instructions' => __( 'What to wrap the Menu\'s ul with (when returning HTML only)', 'sitecore' ),
			'type'         => 'select',
			'name'         => 'container',
			'choices'      => $this->get_allowed_nav_container_tags(),
		] );

		// Register the Allow Null setting.
		acf_render_field_setting( $field, [
			'label'   => __( 'Allow Null?', 'sitecore' ),
			'type'    => 'radio',
			'name'    => 'allow_null',
			'layout'  => 'horizontal',
			'choices' => [
				1 => __( 'Yes', 'sitecore' ),
				0 => __( 'No', 'sitecore' ),
			],
		] );
	}

	/**
	 * Container tags
	 *
	 * Get the allowed wrapper tags for use with wp_nav_menu().
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array An array of allowed wrapper tags.
	 */
	private function get_allowed_nav_container_tags() {

		$tags = apply_filters(
			'wp_nav_menu_container_allowedtags',
			[ 'div', 'nav' ]
		);

		$formatted_tags = [
			'0' => __( 'None', 'sitecore' )
		];

		foreach ( $tags as $tag ) {
			$formatted_tags[$tag] = ucfirst( $tag );
		}
		return $formatted_tags;
	}

	/**
	 * Get nav menus
	 *
	 * Gets a list of ACF Nav Menus indexed by their Nav Menu IDs.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  boolean $allow_null If true, prepends the null option.
	 * @return array An array of Nav Menus indexed by their Nav Menu IDs.
	 */
	private function get_nav_menus( $allow_null = false ) {

		$get_terms = get_terms( 'nav_menu', [ 'hide_empty' => false ] );
		$nav_menus = [];

		if ( $allow_null ) {
			$nav_menus[''] = __( '- Select -', 'sitecore' );
		}

		foreach ( $get_terms as $term ) {
			$nav_menus[ $term->term_id ] = $term->name;
		}
		return $nav_menus;
	}

	/**
	 * Renders the ACF Nav Menu Field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  integer $value   The Nav Menu ID selected for this Nav Menu Field.
	 * @param  integer $post_id The Post ID this $value is associated with.
	 * @param  array $field   The array representation of the current Nav Menu Field.
	 * @return mixed The Nav Menu ID, or the Nav Menu HTML, or the Nav Menu Object, or false.
	 */
	public function format_value( $value, $post_id, $field ) {

		if ( empty( $value ) ) {
			return false;
		}

		// Check format.
		if ( 'object' == $field['save_format'] ) {

			$wp_menu_object = wp_get_nav_menu_object( $value );

			if ( empty( $wp_menu_object ) ) {
				return false;
			}

			$menu_object = new stdClass;
			$menu_object->ID    = $wp_menu_object->term_id;
			$menu_object->name  = $wp_menu_object->name;
			$menu_object->slug  = $wp_menu_object->slug;
			$menu_object->count = $wp_menu_object->count;

			return $menu_object;

		} elseif ( 'menu' == $field['save_format'] ) {

			ob_start();
			wp_nav_menu( [
				'menu'            => $value,
				'container'       => 'div',
       			'container_class' => 'acf-nav-menu',
				'container'       => $field['container'],
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			] );
			return ob_get_clean();
		}

		// Just return the Nav Menu ID.
		return $value;
	}

	/**
	 * Load value
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed $value
	 * @param  integer $post_id
	 * @param  string $field
	 * @return void
	 */
	public function load_value( $value, $post_id, $field ) {
		return $value;
	}
}
