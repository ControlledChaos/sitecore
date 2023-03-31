<?php
/**
 * Register admin pages post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

use SiteCore\Classes\Admin as Backend_Class;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Admin extends Register_Type {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'singular'    => __( 'admin page', 'sitecore' ),
			'plural'      => __( 'admin pages', 'sitecore' ),
			'description' => '',
			'menu_icon'   => 'dashicons-clipboard'
		];

		$options = [
			'public'              => false,
			'menu_position'       => 99,
			'exclude_from_search' => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'supports'            => [
				'title',
				'editor',
				'thumbnail',
			],
			'taxonomies'  => [],
			'has_archive' => false
		];

		parent :: __construct(
			'admin',
			$labels,
			$options,
			20,
			true
		);

		// Modify row actions in list UI.
		add_filter( 'post_row_actions', [ $this, 'row_actions' ], 10, 1 );

		// Add a page per post.
		add_action( 'plugins_loaded', [ $this, 'add_pages' ], 11 );

		// Enqueue admin scripts & styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Print CSS to the admin head.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ] );
	}

	/**
	 * Modify row actions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of actions.
	 */
	public function row_actions( $actions ) {

		if ( $this->type_key === get_post_type() ) {

			// Remove the view link.
			unset( $actions['view'] );
		}
		return $actions;
	}

	/**
	 * New post type options
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Array of arguments for registering a post type.
	 * @param  string $post_type Post type key.
	 * @return array Returns an array of new option arguments.
	 */
	public function post_type_options( $args, $post_type ) {

		// Look for the content settings page and set as a variable.
		$content = get_plugin_page_hookname( 'content-settings', 'content-settings' );

		// Only modify this post type.
		if ( $this->type_key != $post_type ) {
			return $args;
		}

		// Only show under content settings if the page exists & if developer.
		if ( $content && current_user_can( 'develop' ) ) {

			// Set content settings as menu parent.
			$args['show_in_menu'] = 'content-settings';
		}

		// Only allow developer role to add & edit.
		if ( ! current_user_can( 'develop' ) ) {
			$args['capabilities'] = [
				'edit_'   . $this->type_key => false,
				'delete_' . $this->type_key => false,
				'edit_posts'   => false,
				'delete_posts' => false
			];
		}

		return $args;
	}

	/**
	 * Rewrite post type labels
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns new values for array label arguments.
	 */
	public function rewrite_labels() {

		// Post type.
		$post_type = $this->type_key;
		$type_obj  = get_post_type_object( $post_type );

		// New post type labels.
		$type_obj->labels->all_items = __( 'Admin Pages', 'sitecore' );
	}

	/**
	 * Rewrite rules
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of rewrite rules.
	 */
	public function rewrite() {

		// No rewrite rules.
		$rewrite = [
			'with_front' => false,
			'feeds'      => false,
			'pages'      => false
		];

		return $rewrite;
	}

	/**
	 * Add admin pages
	 *
	 * This uses this class' post type to add
	 * admin pages, applying the post title,
	 * post content, and included fields for
	 * Advanced Custom Fields.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_pages() {

		if ( ! class_exists( 'acf' ) ) {
			return;
		}

		$posts = get_posts( [
			'post_type'      => [ $this->type_key ],
			'post_status'    => [ 'publish' ]
		] );

		foreach ( $posts as $post ) {

			setup_postdata( $post );

			$post_id    = $post->ID;
			$capability = get_field( 'admin_post_capability', $post_id );
			$slug       = get_field( 'admin_post_page_slug', $post_id );
			$parent     = get_field( 'admin_post_parent_slug', $post_id );
			$menu_title = get_field( 'admin_post_menu_title', $post_id );
			$icon       = get_field( 'admin_post_icon_url', $post_id );
			$position   = get_field( 'admin_post_position', $post_id );
			$priority   = get_field( 'admin_post_hook_priority', $post_id );
			$desc       = get_field( 'admin_post_description', $post_id );

			if ( $capability ) {
				$capability = $capability;
			} else {
				$capability = 'manage_options';
			}

			if ( $menu_title ) {
				$menu_title = $menu_title;
			} else {
				$menu_title = $post->post_title;
			}

			if ( $position ) {
				$position = $position;
			} else {
				$position = 85;
			}

			if ( $priority ) {
				$priority = $priority;
			} else {
				$priority = 10;
			}

			if ( $slug ) {

				$labels = [
					'page_title'  => $post->post_title,
					'menu_title'  => $menu_title,
					'description' => $desc
				];

				$options = [
					'capability'    => $capability,
					'menu_slug'     => $slug,
					'parent_slug'   => $parent,
					'icon_url'      => $icon,
					'position'      => $position,
					'add_help'      => false
				];

				new Backend_Class\Add_Page( $labels, $options, $priority );

				$content = function() use ( $post ) {

					$id = $post->ID;

					// If ACF Pro is not active then get the post content,
					if ( ! class_exists( 'acf_pro' ) ) {
						printf(
							'<div class="admin-post-type-content">%s</div>',
							apply_filters( 'the_content', $post->post_content )
						);
						return;
					}

					$get_tabs = get_field( 'admin_post_content_tabs', $id );

					if ( ! is_array( $get_tabs ) ) {
						printf(
							'<h2>%s</h2>',
							__( 'No content available.', 'sitecore' )
						);
						return;
					}

					if ( count( $get_tabs ) > 1 ) {
						$tabbed         = ' data-tabbed="tabbed"';
						$wrap_class     = 'admin-post-type-content registered-content-wrap admin-tabs';
						$content_class  = 'registered-content tab-content';
					} else {
						$tabbed         = '';
						$wrap_class     = 'admin-post-type-content registered-content-wrap';
						$content_class  = 'registered-content';
					}

					?>
					<div class="<?php echo $wrap_class; ?>" <?php echo $tabbed; ?> data-tabdeeplinking="true" >

					<?php

					if ( count( $get_tabs ) > 1 ) : ?>

					<ul class="admin-tabs-list hide-if-no-js">
					<?php
					foreach ( $get_tabs as $tab ) :

						$tab_id = strtolower( str_replace( [ ' ', '-' ], '_', $tab['admin_post_content_tab_label'] ) );

						if ( current_user_can( $tab['admin_post_content_tab_user_cap'] ) ) :

							$href = "#$tab_id";

							if ( ! empty( $tab['icon'] ) ) {
								$icon = sprintf(
									'<span class="content-tab-icon %1s"></span> ',
									$tab['icon']
								);
							} else {
								$icon = null;
							}
							?>
								<li class="content-tab">
									<a href="<?php echo esc_url( $href ); ?>" aria-controls="<?php echo esc_attr( $tab_id ); ?>">
										<?php echo $icon . $tab['admin_post_content_tab_label']; ?>
									</a>
							<?php
						endif;
					endforeach;

					?>
					</ul>
					<?php endif; ?>

					<?php
					foreach ( $get_tabs as $tab ) :

						$tab_id = strtolower( str_replace( [ ' ', '-' ], '_', $tab['admin_post_content_tab_label'] ) );

						if ( current_user_can( $tab['admin_post_content_tab_user_cap'] ) ) :
						?>
						<div id="<?php echo esc_attr( $tab_id ); ?>" class="<?php echo $content_class; ?>">
							<?php

							printf(
								'<h2>%s</h2>',
								$tab['admin_post_content_tab_heading']
							);
							echo $tab['admin_post_content_tab_content'];

							?>
						</div>
						<?php
						endif;
					endforeach; ?>
				</div>

				<?php
				};
				add_action( 'render_screen_tabs_' . $slug, $content );
			}
		}
		wp_reset_postdata();
	}

	/**
	 * Enqueue scripts & styles
	 *
	 * Filter available for themes to style the admin pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		return apply_filters( 'scp_admin_pages_enqueue_scripts', null );
	}

	/**
	 * Undocumented function
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_styles() {

		$style = '<style>';
		$style .= '.admin-post-type-content { overflow-x: hidden; }';
		$style .= '.admin-post-type-content img { max-width: 100%; height: auto; }';
		$style .= '</style>';

		echo $style;
	}
}
