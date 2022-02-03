<?php
/**
 * Post edit screens
 *
 * @package    Site_Core
 * @subpackage Admin
 * @category   Post Edit
 * @since      1.0.0
 */

namespace SiteCore\Admin\Post_Edit;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Replace default post title placeholders.
	add_filter( 'enter_title_here', $ns( 'title_placeholders' ) );

	// Add taxonomies to the page post type.
	add_action( 'init', $ns( 'page_taxonomies' ) );

	// Add excerpts to the page post type.
	add_action( 'init', $ns( 'add_page_excerpts' ) );

	// Show excerpt metabox by default.
	add_filter( 'default_hidden_meta_boxes', $ns( 'show_excerpt_metabox' ), 10, 2 );

	// Post type excerpt metaboxes.
	add_action( 'add_meta_boxes', $ns( 'excerpt_metabox' ), 20 );

	// Add page break button to visual editor.
	add_filter( 'mce_buttons', $ns( 'add_page_break_button' ), 1, 2 );

	// Replace "Post" in the update messages.
	add_filter( 'post_updated_messages', $ns( 'update_messages' ), 99 );
}

/**
 * Replace default post title placeholders.
 *
 * @since  1.0.0
 * @param  object $title Stores the 'Enter title here" placeholder.
 * @return object Returns the title placeholder.
 * @throws Non-Object Throws an error on attachment edit screens since
 *         there is no placeholder, so that post type is nullified.
 */
function title_placeholders( $title ) {

	// Get the current screen as a variable.
	$screen = get_current_screen();

	$post_type_obj = get_post_type_object( get_post_type() );

	if ( $post_type_obj ) {
		$name = $post_type_obj->labels->singular_name;
		$post_title = esc_html__( $name . ' Title', 'sitecore' );
	} else {
		$post_title = esc_html__( 'Title', 'sitecore' );
	}

	// Apply a filter conditional modification.
	$title = apply_filters( 'scp_post_title_placeholders', $post_title );

	// Return the new placeholder.
	return $title;
}

/**
 * Page taxonomies
 *
 * Adds taxonomies to the page post type.
 *
 * @since  1.0.0
 * @return void
 */
function page_taxonomies() {
	register_taxonomy_for_object_type( 'category', 'page' );
	register_taxonomy_for_object_type( 'post_tag', 'page' );
}

/**
 * Add excerpts to page post type
 *
 * @since  1.0.0
 * @return void
 */
function add_page_excerpts() {
	add_post_type_support( 'page', 'excerpt' );
}

/**
 * Make excerpts visible by default if used as meta descriptions.
 *
 * Add your post types as necessary.
 *
 * @since  1.0.0
 * @param  array $hidden
 * @param  object $screen
 * @return array Unsets the hidden value in the screen base array.
 *
 * @todo   Programmatically apply to all registered post types.
 * @todo   Review this if or when a check becomes available for the
 *         new WordPress block editor (Gutenberg) as the classic
 *         Excerpt metabox will not be displayed.
 */
function show_excerpt_metabox( $hidden, $screen ) {

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
 * Post type excerpt metaboxes
 *
 * @since  1.0.0
 * @global array $wp_meta_boxes Access the metaboxes array.
 * @return void
 */
function excerpt_metabox() {

	if ( ! post_type_supports( get_post_type(), 'excerpt' ) ) {
		return;
	}

	global $wp_meta_boxes;

	// Arrays of post type query arguments.
	$query = [
		'public'   => true,
		'_builtin' => false
	];

	$get_types = get_post_types( $query, 'names', 'and' );

	$builtin = [ 'post', 'page' ];

	// Merge the post type arrays.
	$post_types = array_merge( $builtin, $get_types );
	if ( $post_types ) {

		foreach ( $post_types as $post_type ) {

			$type = get_post_type_object( $post_type );

			// Metabox title with post type name.
			if (
				isset( $type->labels->singular_name ) &&
				is_string( $type->labels->singular_name ) &&
				! empty( $type->labels->singular_name )
			) {
				$title = sprintf(
					'%s %s',
					$type->labels->singular_name,
					__( 'Summary', 'sitecore' )
				);
			} else {
				$title = __( 'Content Summary', 'sitecore' );
			}

			$wp_meta_boxes[ $type->name ]['normal']['core']['postexcerpt']['title']    = $title;
			$wp_meta_boxes[ $type->name ]['normal']['core']['postexcerpt']['callback'] = __NAMESPACE__ . '\excerpt_metabox_callback';
		}
	}
}

/**
 * Display post excerpt form fields.
 *
 * @since  1.0.0
 * @param  WP_Post $post The post object.
 * @return void
 */
function excerpt_metabox_callback( $post ) {

?>
<p class="description">
	<?php _e( 'Add a brief summary of this content to be used in archive pages, depending upon the active theme, and to be used in search engine metadata and for display in social media embeds.', 'sitecore' ); ?>
</p>

<label class="screen-reader-text" for="excerpt">
	<?php _e( 'Summary Description', 'sitecore' ); ?>
</label>

<textarea rows="1" cols="40" name="excerpt" id="excerpt"><?php echo $post->post_excerpt; ?></textarea>
<?php

}

/**
 * Add page break button to visual editor.
 *
 * Used for creating a "Read More" link on your blog page and archive pages.
 *
 * @since  1.0.0
 * @param  array $buttons
 * @param  string $id
 * @return array Returns the TinyMCE buttons array.
 *
 * @todo   Review this if or when a check becomes available for the
 *         new WordPress block editor (Gutenberg) since page breaks
 *         will be included.
 */
function add_page_break_button( $buttons, $id ) {

	if ( $id !== 'content' ) {
		return $buttons;
	}

	array_splice( $buttons, 13, 0, 'wp_page' );
	return $buttons;
}

/**
 * Replace "Post" in the update messages for custom post types.
 *
 * Example: where the edit screen reads "Post updated" and "View post"
 * it would read "Project updated" and "View project" for post type Project.
 *
 * @since  1.0.0
 * @global object post
 * @global int post_ID
 * @param  array $messages
 * @return string Returns the text appropriate for each condition.
 */
function update_messages( $messages ) {

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
				__( '%1s updated. <a href="%2s">View %3s</a>', 'sitecore' ), $post_object->labels->singular_name,
				esc_url( get_permalink( $post_ID ) ),
				$post_object->labels->singular_name
			),
			2  => __( 'Custom field updated.', 'sitecore' ),
			3  => __( 'Custom field deleted.', 'sitecore' ),
			4  => sprintf(
				__( '1%s updated.', 'sitecore' ),
				$post_object->labels->singular_name
			),
			5  => isset( $_GET['revision']) ? sprintf(
				__( '%1s restored to revision from %2s', 'sitecore' ),
				$post_object->labels->singular_name,
				wp_post_revision_title( (int) $_GET['revision'], false )
				) : false,
			6  => sprintf(
				__( '%1s published. <a href="%2s">View %3s</a>', 'sitecore' ),
				$post_object->labels->singular_name,
				esc_url( get_permalink( $post_ID ) ),
				$post_object->labels->singular_name
			),
			7  => sprintf(
				__( '%1s saved.', 'sitecore' ),
				$post_object->labels->singular_name
			),
			8  => sprintf(
				__( '%1s submitted. <a target="_blank" href="%2s">Preview %3s</a>', 'sitecore' ),
				$post_object->labels->singular_name,
				esc_url( add_query_arg( 'preview', 'true',
				get_permalink( $post_ID ) ) ),
				$post_object->labels->singular_name
			),
			9  => sprintf(
				__( '%1s scheduled for: <strong>%2s</strong>. <a target="_blank" href="%3s">Preview %4s</a>', 'sitecore'  ),
				$post_object->labels->singular_name,
				date_i18n( __( 'M j, Y @ G:i', 'sitecore' ),
				strtotime( $post->post_date ) ),
				esc_url( get_permalink( $post_ID ) ),
				$post_object->labels->singular_name
			),
			10 => sprintf(
				__( '%1s draft updated. <a target="_blank" href="%2s">Preview %3s</a>', 'sitecore'  ),
				$post_object->labels->singular_name,
				esc_url( add_query_arg( 'preview', 'true',
				get_permalink( $post_ID ) ) ),
				$post_object->labels->singular_name
			),
		];
	}
	return $messages;
}
