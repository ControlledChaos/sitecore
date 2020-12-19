<?php
/**
 * Remove blog
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Remove_Blog {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Remove from admin menu.
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 9999 );

		// Delete default post.
		add_action( 'init', [ $this, 'delete_default' ] );

		// Remove posts from user toolbar.
		add_action( 'admin_bar_menu', [ $this, 'posts_toolbar' ], 999 );

		// Remove comments from user toolbar.
		add_action( 'admin_init', [ $this, 'comments_toolbar' ] );

		// Remove dashboard metaboxes.
		add_action( 'wp_dashboard_setup', [ $this, 'dashboard_metaboxes' ] );

		// Remove widgets.
		add_action( 'widgets_init', [ $this, 'remove_widgets' ] );

		// Hide dashboard components with CSS.
		add_action( 'admin_head', [ $this, 'dashboard_css_hide' ] );

		// Hide dashboard components with JS.
		add_action( 'admin_print_footer_scripts-index.php', [ $this, 'dashboard_js_hide' ] );

		// Close comments.
		add_action( 'template_redirect', [ $this, 'comment_feed' ], 9 );

		// Disable existing comments.
		add_filter( 'comments_array', [ $this, 'existing_comments' ], 20, 2 );

		// Disable posts comments.
		add_filter( 'comments_open', [ $this, 'disable_posts_comments' ], 20, 2 );
		add_filter( 'pings_open', [ $this, 'disable_posts_comments' ], 	20, 2 );

		// Disable media comments.
		add_filter( 'comments_open', [ $this, 'disable_media_comments' ], 20 , 2 );
		add_filter( 'pings_open', [ $this, 'disable_media_comments' ], 	20, 2 );

		// Meta widget comments link.
		add_action( 'wp_head', [ $this, 'meta_widget_link' ], 100 );

		// Unset headers.
		add_filter( 'wp_headers', array( $this, 'unset_headers' ) );

		// Redirect post list pages.
		add_action( 'admin_init', [ $this, 'redirect' ] );

		// Remove by filter.
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
		add_filter( 'pre_option_default_pingback_flag', '__return_zero' );
		add_filter( 'get_comments_number', [ $this, 'comments_number' ] );
	}

	/**
	 * Remove from admin menu
	 *
	 * @since  1.0.0
	 * @access public
	 * @global string $pagenow Gets the filename of the current page.
	 * @return void
	 */
	public function admin_menu() {

		global $pagenow;

		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	/**
	 * Delete default post
	 *
	 * @todo Uncomment wp_delete_post when setting is done.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function delete_default() {

		// Get all posts by post type.
		$posts = get_posts( [
			'post_type'   => 'post',
			'numberposts' => -1
		] );

		// Get the option to trash or delete.
		$force = get_option( 'scp_force_delete_posts' );

		foreach ( $posts as $post ) {
			// wp_delete_post( $post->ID, $force );
		}
	}

	/**
	 * Remove posts from admin toolbar
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function posts_toolbar( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
	}

	/**
	 * Remove comments from user toolbar
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function comments_toolbar() {

		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	/**
	 * Remove dashboard metaboxes
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dashboard_metaboxes(){
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	/**
	 * Remove widgets
	 *
	 * Removes the recent posts and recent comments widgets.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function remove_widgets() {
		unregister_widget( 'WP_Widget_Recent_Posts' );
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}

	/**
	 * Hide dashboard components with CSS
	 *
	 * @since  1.0.0
	 * @access public
	 * @global string $pagenow Gets the filename of the current page.
	 * @return string Returns the style block.
	 */
	public function dashboard_css_hide() {

		global $pagenow;

		if ( 'index.php' == $pagenow ) :

		?>
		<style>
		#dashboard_right_now .comment-count,
		#latest-comments,
		.welcome-panel-last ul li:nth-of-type(3),
		#welcome-panel .welcome-comments {
			display: none !important;
		}
		</style>
		<?php

		endif;
	}

	/**
	 * Hide dashboard components with JS
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script block.
	 */
	public function dashboard_js_hide() {

		?>
		<script>
		jQuery(function($){
			$( '#dashboard_right_now .comment-count, #latest-comments' ).hide();
		 	$( '#welcome-panel .welcome-comments' ).parent().hide();
		});
		</script>
		<?php
	}

	/**
	 * Close comments
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function comment_feed() {

		if ( is_comment_feed() ) {
			wp_die( __( 'Comments are closed.' ), '', [ 'response' => 403 ] );
		}
	}

	/**
	 * Disable existing comments
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $comments
	 * @param  integer $post_id The ID of the post.
	 * @return array Returns an empty array of comments for the post.
	 */
	public function existing_comments( $comments, $post_id ) {

		$post = get_post( $post_id );

		if ( 'post' == get_post_type() ) {
			return [];
		}
		return $comments;
	}

	/**
	 * Comments number
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  integer $post_id The ID of the post.
	 * @return string|int $count Returns 0 or a string representing
	 *                           the number of comments a post has.
	 */
	public function comments_number( $post_id ) {

		$post = get_post( $post_id );

		if ( 'post' == get_post_type() ) {
			return 0;
		}
		return $post->comment_count;
	}

	/**
	 * Disable posts comments
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  boolean $open Whether comments are open.
	 * @param  integer $post_idThe ID of the post.
	 * @return boolean Returns true to close posts comments.
	 */
	public function disable_posts_comments( $open, $post_id ) {

		$post = get_post( $post_id );

		if ( 'post' == get_post_type() ) {
			return false;
		}
		return $open;
	}

	/**
	 * Disable media comments
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  boolean $open Whether comments are open.
	 * @param  integer $post_idThe ID of the post.
	 * @return boolean Returns false, close comments.
	 */
	public function disable_media_comments( $open, $post_id ) {

		$post = get_post( $post_id );

		if ( 'attachment' == $post->post_type ) {
			return false;
		}
		return $open;
	}

	/**
	 * Meta widget comments link
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns a CSS style block.
	 */
	public function meta_widget_link() {
		echo '<style>.widget_meta ul li:nth-of-type(4) { display: none; }</style>';
	}

	/**
	 * Unset headers
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $headers
	 * @return array Returns the array of filtered headers.
	 */
	public function unset_headers( $headers ) {

		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Redirect pages
	 *
	 * A temporary redirect to the dashboard is created.
	 * Checks the URL for the `post_type` parameter to exclude
	 * pages and custom post types.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object pagenow Gets the current admin screen.
	 * @return void
	 */
	public function redirect() {

		global $pagenow;

		// Redirect if the user is on one of the posts list pages.
		if ( 'edit.php' == $pagenow && ! isset( $_GET['post_type'] )  ) {
			wp_redirect( admin_url( '/', 'http' ), 302 );
			exit;
		}

		// Redirect if the user is on the new post page.
		elseif ( 'post-new.php' == $pagenow && ! isset( $_GET['post_type'] )  ) {
			wp_redirect( admin_url( '/', 'http' ), 302 );
			exit;
		}

		// Redirect if the user is on any of the comments pages.
		elseif ( 'comment.php' == $pagenow || 'edit-comments.php' == $pagenow || 'options-discussion.php' == $pagenow ) {
			wp_redirect( admin_url( '/', 'http' ), 302 );
			exit;
		}
	}
}
