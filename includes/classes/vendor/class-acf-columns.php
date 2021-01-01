<?php
/**
 * Admin columns for ACF fields
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

class ACF_Columns {

	/**
	 * Exclude field types
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $exclude_field_types = [
		'accordion',
		'clone',
		'flexible_content',
		'google_map',
		'group',
		'message',
		'repeater',
		'tab',
	];

	/**
	 * Admin columns
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $admin_columns = [];

	/**
	 * Post type index
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    boolean
	 */
	private $screen_is_post_type_index = false;

	/**
	 * Taxonomy index
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    boolean
	 */
	private $screen_is_taxonomy_index = false;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		// ACF related
		add_action( 'acf/init', [ $this, 'acf_actions' ] ); // add ACF fields

		// Prepare columns.
		add_action( 'parse_request', [ $this, 'prepare_columns' ], 10 );
		add_action( 'pre_get_terms', [ $this, 'prepare_columns' ], 10 );

		// Prepare query sort.
		add_action( 'pre_get_posts', [ $this, 'prepare_query_sort' ] );
	}

	/**
	 * Is ACF active
	 *
	 * Checks whether the ACF plugin is active.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool
	 */
	private function is_acf_active() {
		return ( function_exists( 'acf_get_field_groups' ) && function_exists( 'acf_get_fields' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( ! $this->is_acf_active() || ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		// Get the current admin screen.
		$screen = get_current_screen();

		// Script suffix.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '';
		} else {
			$suffix = '.min';
		}

		// Only hook on ACF field editor page.
		if ( $screen && $screen->id == 'acf-field-group' ) {
			wp_enqueue_script( 'acf-admin-column', SCP_URL . 'assets/js/acf-columns' . $suffix . '.js', [ 'acf-field-group' ], null, true );
		}
	}

	/**
	 * ACF actions
	 *
	 * Adds ACF hooks and handle versions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_actions() {

		$exclude     = apply_filters( 'acf/admin_columns/exclude_field_types', $this->exclude_field_types );
		$acf_version = acf_get_setting( 'version' );
		$sections    = acf_get_field_types();

		if (
			(
			version_compare( $acf_version, '5.5.0', '<' ) ||
			version_compare( $acf_version, '5.6.0', '>=' )
			) &&
			version_compare( $acf_version, '5.7.0', '<' )
		) {
			foreach ( $sections as $section ) {
				foreach ( $section as $type => $label ) {
					if ( ! in_array( $type, $exclude ) ) {
						add_action( 'acf/render_field_settings/type=' . $type, [ $this, 'render_field_settings' ], 1 );
					}
				}
			}

		} else {
			// >= 5.5.0 || < 5.6.0
			foreach ( $sections as $type => $settings ) {

				if ( ! in_array( $type, $exclude ) ) {
					add_action( 'acf/render_field_settings/type=' . $type, [ $this, 'render_field_settings' ], 1 );
				}
			}
		}
	}

	/**
	 * Prepare columns
	 *
	 * Checks which columns to show on the current screen and
	 * attaches to the respective WP hooks.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function prepare_columns() {

		$screen = $this->is_valid_admin_screen();

		if ( ! $this->is_acf_active() || $this->admin_columns || ! $screen ) {
			return;
		}

		$is_post_type_index = $screen->base == 'edit' && $screen->post_type;
		$is_taxonomy_index  = $screen->base == 'edit-tags' && $screen->taxonomy;
		$field_groups       = false;
		$field_groups_args  = [];

		if ( $this->screen_is_post_type_index ) {
			$field_groups_args['post_type'] = $screen->post_type;

		} elseif ( $this->screen_is_taxonomy_index ) {
			$field_groups_args['taxonomy'] = $screen->taxonomy;
		}

		/**
		 * Get all field groups for the current post type and
		 * check every containing field if it should become.
		 */
		$field_groups = acf_get_field_groups( $field_groups_args );

		foreach ( $field_groups as $fgroup ) {

			$fgroup_fields = acf_get_fields( $fgroup );

			foreach ( $fgroup_fields as $field ) {

				if ( ! isset( $field['acf-column-enabled'] ) || $field['acf-column-enabled'] == false ) {
					continue;
				}

				if ( $is_taxonomy_index && ( ! isset( $field['acf-column-taxonomies'] ) || ( is_array( $field['acf-column-taxonomies'] ) && array_search( $screen->taxonomy, $field['acf-column-taxonomies'] ) === false ) ) ) {
					continue;
				}
				if ( $is_post_type_index && ( ! isset( $field['acf-column-post-types' ] ) || ( is_array( $field['acf-column-post-types'] ) && array_search( $screen->post_type, $field['acf-column-post-types'] ) === false ) ) ) {
					continue;
				}

				$this->admin_columns[ 'acf_' . $field['name'] ] = $field['label'];
			}
		}

		$this->admin_columns = apply_filters( 'acf/admin_columns/admin_columns', $this->admin_columns );

		if ( ! empty( $this->admin_columns ) ) {

			if ( $is_post_type_index ) {

				// Creates the columns.
				add_filter( 'manage_' . $screen->post_type . '_posts_columns', [ $this, 'filter_manage_posts_columns' ] );

				// Make columns sortable.
				add_filter( 'manage_' . $screen->id . '_sortable_columns', [ $this, 'filter_manage_sortable_columns' ] );

				// Outputs the columns values for each post.
				add_action( 'manage_' . $screen->post_type . '_posts_custom_column', [ $this, 'action_manage_posts_custom_column' ], 10, 2 );

			} elseif ( $is_taxonomy_index ) {

				// Creates the columns.
				add_filter( 'manage_edit-' . $screen->taxonomy . '_columns', [ $this, 'filter_manage_posts_columns' ] );

				// Outputs the columns values for each post.
				add_filter( 'manage_' . $screen->taxonomy . '_custom_column', [ $this, 'filter_manage_taxonomy_custom_column' ], 10, 3 );
			}
		}
	}

	/**
	 * Prepare query sort
	 *
	 * Prepares WPs query object when ordering by ACF field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param $query
	 * @return mixed
	 */
	public function prepare_query_sort( $query ) {

		if ( $this->is_acf_active() && $this->is_valid_admin_screen() && $query->query_vars && isset( $query->query_vars['orderby'] ) ) {

			$orderby = $query->query_vars['orderby'];

			if ( array_key_exists( $orderby, $this->admin_columns ) ) {

				// this makes sure we sort also when the custom field has never been set on some posts before
				$meta_query = [
					'relation' => 'OR',

					// 'NOT EXISTS' needs to go first for proper sorting.
					[ 'key' => $this->get_clean_column( $orderby ), 'compare' => 'NOT EXISTS' ],
					[ 'key' => $this->get_clean_column( $orderby ), 'compare' => 'EXISTS' ],
				];

				$query->set( 'meta_query', $meta_query );
				$query->set( 'orderby', 'meta_value' );
			}
		}
		return $query;
	}

	/**
	 * Manage columns
	 *
	 * Adds the designated columns to Wordpress admin post list table.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param $columns array passed by Wordpress
	 * @return array
	 */
	public function filter_manage_posts_columns( $columns ) {

		if ( ! empty( $this->admin_columns ) ) {
			$columns = array_merge( $columns, $this->admin_columns );
		}

		return $columns;
	}

	/**
	 * Filter sortable columns
	 *
	 * Makes our columns rendered as sortable.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param $columns
	 * @return mixed
	 */
	public function filter_manage_sortable_columns( $columns ) {

		foreach ( $this->admin_columns as $idx => $acol) {
			$columns[$idx] = $idx;
		}

		$columns = apply_filters( 'acf/admin_columns/sortable_columns', $columns );

		return $columns;
	}

	/**
	 * Manage CPT columns
	 *
	 * WP Hook for displaying the field value inside of a columns cell in posts index pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $column
	 * @param  $post_id
	 * @return string
	 */
	public function action_manage_posts_custom_column( $column, $post_id ) {

		if ( array_key_exists( $column, $this->admin_columns ) ) {

			$clean_column = $this->get_clean_column( $column );
			$field_value  = $this->render_column_field( $column, $post_id );
			$field_value  = apply_filters( 'acf/admin_columns/column/' . $clean_column, $field_value );

			echo $field_value;
		}
	}

	/**
	 * WP Hook for displaying the field value inside of a columns cell taxonomy index pages
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $column
	 * @param  $post_id
	 * @return array
	 */
	public function filter_manage_taxonomy_custom_column( $content, $column, $post_id ) {

		if ( array_key_exists( $column, $this->admin_columns ) ) {

			$clean_column = $this->get_clean_column( $column );
			$screen       = get_current_screen();
			$taxonomy     = $screen->taxonomy;
			$field_value  = $this->render_column_field( $column, $post_id, $taxonomy );
			$field_value  = apply_filters( 'acf/admin_columns/column/' . $clean_column, $field_value );
			$content      = $field_value;
		}

		return $content;
	}

	/**
	 * Retrieves a field and returns the formatted value based on field type for displaying in "All posts" screen table columns
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $column
	 * @param  $post_id
	 * @return mixed|string
	 */
	public function render_column_field( $column, $post_id, $taxonomy = false ) {

		$clean_column = $this->get_clean_column( $column );

		if ( $taxonomy ) {
			$post_id = $taxonomy . '_' . $post_id;
		}

		$field_value = get_field( $clean_column, $post_id );

		if ( $field_value !== '' ) {

			$render_output    = '';
			$field_properties = acf_get_field( $clean_column, $post_id );
			$field_images     = $field_value;
			$items_more       = 0;

			switch ( $field_properties['type'] ) {

				case 'color_picker' :
					$render_output .= '<div style="display:inline-block;height:20px;width:100%;background-color:' . $field_value . ';white-space:nowrap;">' . $field_value . '</div><br>';
					break;

				case 'taxonomy' :
					$render_output = $field_value;
					break;

				case 'file' :
					$render_output = $field_value['filename'] ?: ''; // @todo multiple values.
					break;

				case 'wysiwyg' :
					$render_output = wp_trim_excerpt( strip_tags( $field_value ) );
					break;

				case 'link' :
					if ( is_array( $field_value ) && isset( $field_value['url'] ) ) {
						$render_output = $field_value['url'];
					}
					break;

				case 'post_object' :
				case 'relationship' :
					$p = $field_value;

					if ( is_array( $field_value ) && ! empty( $field_value ) ) {
						$p          = $field_value[0];
						$items_more = count( $field_value ) - 1;
					}
					$render_output = '<a href="' . get_edit_post_link( $p, false ) . '">' . $p->post_title . '</a>';

					break;

				case 'user' :
					$u = $field_value;

					if ( is_array( $field_value ) && ! empty( $field_value ) ) {
						$u          = $field_value[0];
						$items_more = count( $field_value ) - 1;
					}
					$render_output = '<a href="' . get_edit_user_link( $u) . '">' . $u['display_name'] . '</a>';
					break;

				case 'image' :
					$field_images = [ $field_value ];
				case 'gallery' :

					if ( ! empty( $field_images ) ) {

						// Use first image as preview.
						$preview_image     = $field_images[0];
						$preview_image_id  = 0;
						$preview_image_url = '';

						if ( is_array( $field_value ) && isset( $preview_image['ID'] ) ) {
							$preview_image_id = $preview_image['ID'];

						} else if ( intval( $preview_image ) > 0 ) {
							$preview_image_id = intval( $preview_image );
						}

						if ( filter_var( $preview_image, FILTER_VALIDATE_URL ) ) {
							$preview_image_url = $preview_image;

						} else if ( $preview_image_id > 0) {

							$img = wp_get_attachment_image_src( $preview_image_id, 'thumbnail' );

							if ( is_array( $img ) && isset( $img[0] ) ) {
								$preview_image_url = $img[0];
							}
						}

						if ( $preview_image_url) {
							$render_output = "<img style='width:100%;height:auto;' src='$preview_image_url'>";
						}

						$items_more = count( $field_value ) - 1;
					}
					break;

				case 'text' :
				case 'textarea' :
				case 'number' :
				case 'range' :
				case 'email' :
				case 'url' :
				case 'password' :
				case 'select' :
				case 'checkbox' :
				case 'radio' :
				case 'button_group' :
				case 'true_false' :
				case 'page_link' :
				case 'date_picker' :
				case 'time_picker' :
				default :
					$render_output = $field_value;
			}

			if ( is_array( $render_output ) ) {
				$render_output = implode( ', ', $render_output );
			}

			if ( $items_more ) {
				$render_output .= "<br>and $items_more more";
			}

			return $render_output;
		}
	}

	/**
	 * Render field settings
	 *
	 * Outputs the field within ACF's field setting UI
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  $field
	 * @return void
	 */
	public function render_field_settings( $field ) {

		$setting_name_enabled = 'acf-column-enabled';
		$setting_active       = isset( $field[ $setting_name_enabled ] ) && $field[ $setting_name_enabled ] == true ? true : false;

		/*
		 * General settings switch
		 */
		$field_settings = [
			[
				'type'         => 'true_false',
				'ui'           => 1,
				'label'        => 'Admin Column',
				'name'         => $setting_name_enabled,
				'instructions' => 'Enable admin column for this field in post archive pages.',
			],
		];

		/*
		 * Field for Post Types
		 */

		$post_types = $this->get_supported_post_types();

		if ( isset( $post_types['attachment'] ) ) {
			unset( $post_types['attachment'] );
		}

		// post types option
		$field_settings[] = [
			'type'          => 'checkbox',
			'choices'       => $post_types,
			'ui'            => 1,
			'label'         => 'Admin Column Post Types',
			'name'          => 'acf-column-post_types',
			'instructions'  => 'Show admin column on archive pages of these post types.',
			'allow_null'    => 0,
			'default_value' => 1,
		];

		$taxonomies = get_taxonomies( [ 'show_ui' => true ] );

		// taxonomy option
		$field_settings[] = [
			'type'          => 'checkbox',
			'choices'       => $taxonomies,
			'ui'            => 1,
			'label'         => 'Admin Column Taxonomies',
			'name'          => 'acf-column-taxonomies',
			'instructions'  => 'Show admin column on archive pages of these taxonomies.',
			'allow_null'    => 0,
			'default_value' => 1,
		];

		foreach ( $field_settings as $settings_args ) {

			$settings_args['class']  = isset( $settings_args['class'] ) ? $settings_args['class'] : '';
			$settings_args['class'] .= 'aac-field-settings-' . $settings_args['name'];

			acf_render_field_setting( $field, $settings_args, false );
		}

	}

	/**
	 * Get supported post types
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns an array of post types.
	 */
	private function get_supported_post_types() {
		return $post_types = get_post_types( [ 'show_ui' => true, 'show_in_menu' => true ] );
	}

	/**
	 * Valid admin screen
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns a screen ID (integer) or false (boolean).
	 */
	private function is_valid_admin_screen() {

		if ( function_exists( 'get_current_screen' ) && $screen = get_current_screen() ) {

			$this->screen_is_post_type_index = $screen->base == 'edit' && $screen->post_type;
			$this->screen_is_taxonomy_index  = $screen->base == 'edit-tags' && $screen->taxonomy;

			if ( $this->screen_is_post_type_index || $this->screen_is_taxonomy_index ) {
				return $screen;
			}
		}

		return false;
	}

	/**
	 * Get clean column
	 *
	 * @since  1.0.0
	 * @access public
	 * @param $dirty_column
	 * @return string Return the "real" ACF field name without the prefix.
	 */
	private function get_clean_column( $dirty_column ) {

		$clean_column = str_replace( 'acf_', '', $dirty_column );

		return $clean_column;
	}
}
