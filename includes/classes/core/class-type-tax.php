<?php
/**
 * Enhancements to post types and taxonomies
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Includes
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Post types and taxonomies class.
 *
 * @since  1.0.0
 * @access public
 */
class Type_Tax {

	/**
	 * Class instance
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Replace default post title placeholders.
		add_filter( 'enter_title_here', [ $this, 'title_placeholders' ] );

		// Add excerpts to pages for use in meta data.
		add_action( 'init', [ $this, 'add_page_excerpts' ] );

		// Show excerpt metabox by default.
		add_filter( 'default_hidden_meta_boxes', [ $this, 'show_excerpt_metabox' ], 10, 2 );

		// Add page break button to visual editor.
		add_filter( 'mce_buttons', [ $this, 'add_page_break_button' ], 1, 2 );

		// Replace "Post" in the update messages.
		add_filter( 'post_updated_messages', [ $this, 'update_messages' ], 99 );
	}

	/**
	 * Replace default post title placeholders.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $title Stores the 'Enter title here" placeholder.
	 * @return object Returns the title placeholder.
	 * @throws Non-Object Throws an error on attachment edit screens since
	 *         there is no placeholder, so that post type is nullified.
	 */
	public function title_placeholders( $title ) {

		// Get the current screen as a variable.
		$screen = get_current_screen();

		$post_type_obj = get_post_type_object( get_post_type() );
		$name = $post_type_obj->labels->singular_name;
		$post_title = esc_html__( $name . ' Title', SCP_DOMAIN );

		// Apply a filter conditional modification.
		$title = apply_filters( 'ccp_post_title_placeholders', $post_title );

		// Return the new placeholder.
		return $title;
	}

	/**
	 * Replace "Post" in the update messages for custom post types.
	 *
	 * Example: where the edit screen reads "Post updated" and "View post"
	 * it would read "Project updated" and "View project" for post type Project.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object post
	 * @global int post_ID
	 * @param  array $messages
	 * @return string Returns the text appropriate for each condition.
	 */
	public function update_messages( $messages ) {

		global $post, $post_ID;

		$post_types = get_post_types(
			[
				'show_ui'  => true,
				'_builtin' => false
			],
			'objects' );

		foreach ( $post_types as $post_type => $post_object ) {

			$messages[ $post_type ] = [
				0  => '', // Unused. Messages start at index 1.

				1  => sprintf(
					__( '%1s updated. <a href="%2s">View %3s</a>', SCP_DOMAIN ), $post_object->labels->singular_name,
					esc_url( get_permalink( $post_ID ) ),
					$post_object->labels->singular_name
				),
				2  => __( 'Custom field updated.', SCP_DOMAIN ),
				3  => __( 'Custom field deleted.', SCP_DOMAIN ),
				4  => sprintf(
					__( '1%s updated.', SCP_DOMAIN ),
					$post_object->labels->singular_name
				),
				5  => isset( $_GET['revision']) ? sprintf(
					__( '%1s restored to revision from %2s', SCP_DOMAIN ),
					$post_object->labels->singular_name,
					wp_post_revision_title( (int) $_GET['revision'], false )
					) : false,
				6  => sprintf(
					__( '%1s published. <a href="%2s">View %3s</a>', SCP_DOMAIN ),
					$post_object->labels->singular_name,
					esc_url( get_permalink( $post_ID ) ),
					$post_object->labels->singular_name
				),
				7  => sprintf(
					__( '%1s saved.', SCP_DOMAIN ),
					$post_object->labels->singular_name
				),
				8  => sprintf(
					__( '%1s submitted. <a target="_blank" href="%2s">Preview %3s</a>', SCP_DOMAIN ),
					$post_object->labels->singular_name,
					esc_url( add_query_arg( 'preview', 'true',
					get_permalink( $post_ID ) ) ),
					$post_object->labels->singular_name
				),
				9  => sprintf(
					__( '%1s scheduled for: <strong>%2s</strong>. <a target="_blank" href="%3s">Preview %4s</a>', SCP_DOMAIN  ),
					$post_object->labels->singular_name,
					date_i18n( __( 'M j, Y @ G:i', SCP_DOMAIN ),
					strtotime( $post->post_date ) ),
					esc_url( get_permalink( $post_ID ) ),
					$post_object->labels->singular_name
				),
				10 => sprintf(
					__( '%1s draft updated. <a target="_blank" href="%2s">Preview %3s</a>', SCP_DOMAIN  ),
					$post_object->labels->singular_name,
					esc_url( add_query_arg( 'preview', 'true',
					get_permalink( $post_ID ) ) ),
					$post_object->labels->singular_name
				),
			];
		}
		return $messages;
	}

	/**
	 * Add excerpts to pages for use in meta data.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_page_excerpts() {
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Make excerpts visible by default if used as meta descriptions.
	 *
	 * Add your post types as necessary.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $hidden
	 * @param  object $screen
	 * @return array Unsets the hidden value in the screen base array.
	 *
	 * @todo   Programmatically apply to all registered post types.
	 * @todo   Review this if or when a check becomes available for the
	 *         new WordPress block editor (Gutenberg) as the classic
	 *         Excerpt metabox will not be displayed.
	 */
	public function show_excerpt_metabox( $hidden, $screen ) {

		// Post type screens to show excerpt.
		if ( 'post' == $screen->base || 'page' == $screen->base ) {

			// Look for hidden stuff.
			foreach( $hidden as $key=>$value ) {

				// If the excerpt is hidden, show it.
				if ( 'postexcerpt' == $value ) {
					unset( $hidden[$key] );
					break;
				}
			}
		}

		// Return the default for other post types.
		return $hidden;
	}

	/**
	 * Add page break button to visual editor.
	 *
	 * Used for creating a "Read More" link on your blog page and archive pages.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $buttons
	 * @param  string $id
	 * @return array Returns the TinyMCE buttons array.
	 *
	 * @todo   Review this if or when a check becomes available for the
	 *         new WordPress block editor (Gutenberg) since page breaks
	 *         will be included.
	 */
	public function add_page_break_button( $buttons, $id ) {

		if ( $id !== 'content' ) {
			return $buttons;
		}

		array_splice( $buttons, 13, 0, 'wp_page' );
		return $buttons;
	}
}
