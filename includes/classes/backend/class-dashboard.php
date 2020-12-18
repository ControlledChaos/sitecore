<?php
/**
 * Admin class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Dashboard {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// "At a Glance" dashboard widget.
		add_action( 'dashboard_glance_items', [ $this, 'at_glance' ] );

		// Remove widgets.
		add_action('wp_dashboard_setup', [ $this, 'remove_widgets' ] );
	}

	/**
	 * At a Glance
	 *
	 * Adds custom post types to "At a Glance" dashboard widget.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function at_glance() {

		// Post type query arguments.
		$args       = [
			'public'   => true,
			'_builtin' => false
		];

		// Get post types according to above.
		$post_types = get_post_types( $args, 'object', 'and' );

		// Prepare an entry for each post type mathing the query.
		foreach ( $post_types as $post_type ) {

			// Count the number of posts.
			$count  = wp_count_posts( $post_type->name );

			// Get the number of published posts.
			$number = number_format_i18n( $count->publish );

			// Get the plural or single name based on the count.
			$name = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $count->publish ) );

			// Supply an edit link if the user can edit posts.
			if ( current_user_can( 'edit_posts' ) ) {
				echo sprintf(
					'<style>#dashboard_right_now .post-count.%s a:before, #dashboard_right_now .post-count.%s span:before{ display: none; } #dashboard_right_now li a:before, #dashboard_right_now li span:before { color: inherit; }</style>',
					$post_type->name . '-count',
					$post_type->name . '-count'
				);
				echo sprintf(
					'<li class="post-count %s-count"><a href="edit.php?post_type=%s"><icon class="dashicons %s"></icon> %s %s</a></li>',
					$post_type->name,
					$post_type->name,
					$post_type->menu_icon,
					$number,
					$name
				);

			// Otherwise just the count and post type name.
			} else {
				echo sprintf(
					'<style>#dashboard_right_now .post-count.%s a:before, #dashboard_right_now .post-count.%s span:before{ display: none; } #dashboard_right_now li a:before, #dashboard_right_now li span:before { color: inherit; }</style>',
					$post_type->name . '-count',
					$post_type->name . '-count'
				);
				echo sprintf(
					'<li class="post-count %s-count"><icon class="dashicons %s"></icon> %s %s</li>',
					$post_type->name,
					$post_type->menu_icon,
					$number,
					$name
				);

			}
		}
	}

	/**
	 * Remove widgets
	 *
	 * @since  1.0.0
	 * @access public
	 * @global array wp_meta_boxes The metaboxes array holds all the widgets for wp-admin.
	 * @return void
	 */
	public function remove_widgets() {

		global $wp_meta_boxes;

		/**
		 * WordPress News.
		 *
		 * @todo Confirm for ClassicPress petitions.
		 */
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );

		// Site Health.
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}
}
