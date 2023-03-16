<?php
/**
 * Posts to News
 *
 *  Changes the default posts to "news".
 *
 * @package    Site_Core
 * @subpackage Post Types
 * @category   Blog
 * @since      1.0.0
 */

namespace SiteCore\Posts_to_News;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
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

	// Rewrite post type labels.
	add_action( 'wp_loaded', $ns( 'rewrite_type_labels' ) );
	add_action( 'admin_menu', $ns( 'rewrite_taxonomy_labels' ) );
	add_action( 'admin_menu', $ns( 'menu_icon' ) );
	add_filter( 'post_updated_messages', $ns( 'page_messages' ) );
	add_action( 'admin_head', $ns( 'icon_css' ) );
	add_action( 'admin_footer', $ns( 'at_glance_text' ) );
}
if ( enable_posts_to_news() ) {
	setup();
}

/**
 * Enable plugin
 *
 * Whether to to convert to news.
 *
 * @since  1.0.0
 * @return boolean Returns true/false according to setting.
 */
function enable_posts_to_news() {

	$enable = get_option( 'posts_to_news', false );

	if ( true == $enable ) {
		return true;
	}
	return false;
}

/**
 * Post type labels
 *
 * @since  1.0.0
 * @return array
 */
function news_posts_labels() {

	$labels = [
		'name'                  => ucwords( plural() ),
		'menu_name'             => ucwords( menu_name() ),
		'singular_name'         => ucwords( singular() ),
		'all_items'             => ucwords( plural() ),
		'add_new'               => __( 'Add News', 'sitecore' ),
		'add_new_item'          => __( 'Add ' . ucwords( singular() ), 'sitecore' ),
		'edit_item'             => __( 'Edit ' . ucwords( singular() ), 'sitecore' ),
		'new_item'              => __( 'New ' . ucwords( singular() ), 'sitecore' ),
		'view_item'             => __( 'View ' . ucwords( singular() ), 'sitecore' ),
		'view_items'            => __( 'View ' . ucwords( plural() ), 'sitecore' ),
		'search_items'          => __( 'Search ' . ucwords( plural() ), 'sitecore' ),
		'not_found'             => __( 'No ' . ucwords( plural() ) . ' Found', 'sitecore' ),
		'not_found_in_trash'    => __( 'No ' . ucwords( plural() ) . ' Found in Trash', 'sitecore' ),
		'parent_item_colon'     => __( 'Parent ' . ucwords( singular() ), 'sitecore' ),
		'featured_image'        => __( 'Featured image for this ' . strtolower( singular() ), 'sitecore' ),
		'set_featured_image'    => __( 'Set featured image for this ' . strtolower( singular() ), 'sitecore' ),
		'remove_featured_image' => __( 'Remove featured image for this ' . strtolower( singular() ), 'sitecore' ),
		'use_featured_image'    => __( 'Use as featured image for this ' . strtolower( singular() ), 'sitecore' ),
		'archives'              => __( ucwords( singular() ) . ' archives', 'sitecore' ),
		'insert_into_item'      => __( 'Insert into ' . ucwords( singular() ), 'sitecore' ),
		'uploaded_to_this_item' => __( 'Uploaded to this ' . ucwords( singular() ), 'sitecore' ),
		'filter_items_list'     => __( 'Filter ' . ucwords( plural() ), 'sitecore' ),
		'items_list_navigation' => __( ucwords( plural() ) . ' list navigation', 'sitecore' ),
		'items_list'            => __( ucwords( plural() ) . ' List', 'sitecore' ),
		'attributes'            => __( ucwords( singular() ) . ' Attributes', 'sitecore' )
	];
	return apply_filters( 'scp_news_posts_labels', $labels );
}

/**
 * Rewrite post type labels
 *
 * @since  1.0.0
 * @return void
 */
function rewrite_type_labels() {

	// Post type.
	$post_type = 'post';
	$type_obj  = get_post_type_object( $post_type );
	$labels    = news_posts_labels();

	// New post type labels.
	$type_obj->labels->name                  = $labels['name'];
	$type_obj->labels->menu_name             = $labels['menu_name'];
	$type_obj->labels->singular_name         = $labels['singular_name'];
	$type_obj->labels->all_items             = $labels['all_items'];
	$type_obj->labels->add_new               = $labels['add_new'];
	$type_obj->labels->add_new_item          = $labels['add_new_item'];
	$type_obj->labels->edit_item             = $labels['edit_item'];
	$type_obj->labels->new_item              = $labels['new_item'];
	$type_obj->labels->view_item             = $labels['view_item'];
	$type_obj->labels->view_items            = $labels['view_items'];
	$type_obj->labels->search_items          = $labels['search_items'];
	$type_obj->labels->not_found             = $labels['not_found'];
	$type_obj->labels->not_found_in_trash    = $labels['not_found_in_trash'];
	$type_obj->labels->parent_item_colon     = $labels['parent_item_colon'];
	$type_obj->labels->featured_image        = $labels['featured_image'];
	$type_obj->labels->set_featured_image    = $labels['set_featured_image'];
	$type_obj->labels->remove_featured_image = $labels['remove_featured_image'];
	$type_obj->labels->use_featured_image    = $labels['use_featured_image'];
	$type_obj->labels->archives              = $labels['archives'];
	$type_obj->labels->insert_into_item      = $labels['insert_into_item'];
	$type_obj->labels->uploaded_to_this_item = $labels['uploaded_to_this_item'];
	$type_obj->labels->filter_items_list     = $labels['filter_items_list'];
	$type_obj->labels->items_list_navigation = $labels['items_list_navigation'];
	$type_obj->labels->items_list            = $labels['items_list'];
	$type_obj->labels->attributes            = $labels['attributes'];
}

/**
 * Rewrite taxonomy labels
 *
 * Changes "Categories" to "News Categories" and
 * "Tags" to "News Tags" in the admin menu.
 *
 * @since  1.0.0
 * @global object $menu Gets the admin menu.
 * @global object $submenu Gets the admin submenus.
 * @return void
 */
function rewrite_taxonomy_labels() {

	// Access global variables.
	global $menu, $submenu;

	if ( current_user_can( 'manage_categories' ) ) {
		$submenu['edit.php'][15][0] = __( 'News Categories', 'sitecore' );
		$submenu['edit.php'][16][0] = __( 'News Tags', 'sitecore' );
	}
}

/**
 * Menu name
 *
 * @since  1.0.0
 * @return string Returns the menu name.
 */
function menu_name() {
	$menu_name = __( 'News', 'sitecore' );
	return apply_filters( 'scp_news_posts_menu_name', $menu_name );
}

/**
 * Singular name
 *
 * @since  1.0.0
 * @return string Returns the singular post name.
 */
function singular() {
	$singular = __( 'news post', 'sitecore' );
	return apply_filters( 'scp_news_posts_singular_name', $singular );
}

/**
 * Plural name
 *
 * @since  1.0.0
 * @return string Returns the plural post name.
 */
function plural() {
	$plural = __( 'news posts', 'sitecore' );
	return apply_filters( 'scp_news_posts_plural_name', $plural );
}

/**
 * News icon
 *
 * @since  1.0.0
 * @return string Returns the Dashicons class or
 *                custom icon URL.
 */
function get_news_icon() {
	$icon = 'dashicons-megaphone';
	return apply_filters( 'scp_news_posts_icon', $icon );
}

/**
 * News icon font
 *
 * The value of the `content` selector in the
 * icon font stylesheet.
 *
 * @since  1.0.0
 * @return string Returns the content value.
 */
function get_news_icon_font() {

	if ( 'dashicons-megaphone' === get_news_icon() ) {
		$content = '\f488';
	} else {
		$content = '\f109';
	}
	return apply_filters( 'scp_news_posts_icon_font', $content );
}

/**
 * Change the post pin icon
 *
 * @since  1.0.0
 * @global object $menu Gets the admin menu.
 * @return string Returns the various labels.
 */
function menu_icon() {

	// Access global variables.
	global $menu;

	foreach ( $menu as $key => $val ) {

		if ( menu_name() == $val[0] ) {
			$menu[$key][6] = get_news_icon();
		}
	}
}

/**
 * Change post messages
 *
 * @since  1.0.0
 * @param array $messages Gets the array of messages.
 * @global object $post Gets the post object.
 * @return array Returns the array of messages.
 */
function page_messages( $messages ) {

	// Access global variables.
	global $post;

	// Conditional message for revisions.
	if ( isset( $_GET['revision'] ) ) {
		$revision = sprintf(
			__( '%1s %2s' ),
			__( ucwords( singular() ) . ' restored to revision from', 'sitecore' ),
			wp_post_revision_title( (int) $_GET['revision'], false )
		);
	} else {
		$revision = false;
	}

	// Updated message.
	$updated = sprintf(
		__( '%1s <a href="%2s">%3s</a>' ),
		__( ucwords( singular() ) . ' updated.', 'sitecore' ),
		esc_url( get_permalink( get_the_ID() ) ),
		__( 'View News Post', 'sitecore' )
	);

	// Published message.
	$published = sprintf(
		__( '%1s <a href="%2s">%3s</a>' ),
		__( ucwords( singular() ) . ' published.', 'sitecore' ),
		esc_url( get_permalink( get_the_ID() ) ),
		__( 'View News Post', 'sitecore' )
	);

	// Submitted message.
	$submitted = sprintf(
		__( '%1s <a target="_blank" href="%2s">%3s</a>' ),
		__( ucwords( singular() ) . ' submitted.', 'sitecore' ),
		esc_url( add_query_arg( 'preview', 'true', get_permalink( get_the_ID() ) ) ),
		__( 'Preview News Post', 'sitecore' )
	);

	// Scheduled message.
	$scheduled = sprintf(
		__( '%1s <strong>%2s</strong>. <a target="_blank" href="%3s">%4s</a>' ),
		__( ucwords( singular() ) . ' scheduled for:', 'sitecore' ),
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( get_the_date( (string) get_option( 'date_format' ), get_the_ID() ) ) ),
		esc_url( get_permalink( get_the_ID() ) ),
		__( 'Preview News Post', 'sitecore' )
	);

	// Draft updated message.
	$draft = sprintf(
		__( '%1s <a target="_blank" href="%2s">%3s</a>' ),
		__( ucwords( singular() ) . ' draft updated.', 'sitecore' ),
		esc_url( add_query_arg( 'preview', 'true', get_permalink( get_the_ID() ) ) ),
		__( 'Preview News Post', 'sitecore' )
	);

	// The array of messages for the Posts post type.
	$messages['post'] = [

		// First is unused. Messages start at index 1.
		0  => null,
		1  => $updated,
		2  => __( 'Custom field updated.', 'sitecore' ),
		3  => __( 'Custom field deleted.', 'sitecore' ),
		4  => __( ucwords( singular() ) . ' updated.', 'sitecore' ),
		5  => $revision,
		6  => $published,
		7  => __( ucwords( singular() ) . ' saved.', 'sitecore' ),
		8  => $submitted,
		9  => $scheduled,
		10 => $draft
	];

	// Return the array of messages.
	return $messages;
}

/**
 * News posts dashboard icon
 *
 * Changes the posts icon in the At a Glance dashboard widget.
 *
 * @since  1.0.0
 * @return string Returns the style block in the admin head.
 */
function icon_css() {

	// Get the screen ID to target the Dashboard.
	$screen = get_current_screen();

	// Get the content value of the icon.
	$icon = get_news_icon_font();

	// Bail if not on the Dashboard screen.
	if ( 'dashboard' != $screen->id ) {
		return;
	}

	// Minified style block.
	$style = sprintf(
		'<style>#dashboard_right_now .post-count a[href="edit.php?post_type=post"]::before,#dashboard_right_now .post-count span::before{content:"%s"!important;}</style>',
		$icon
	);

	// Print the style block.
	echo apply_filters( 'scp_news_posts_icon_css', $style );
}

/**
 * News posts dashboard text
 *
 * Changes the posts text in the At a Glance dashboard widget.
 *
 * @since  1.0.0
 * @return string Returns the script block in the admin head.
 */
function at_glance_text() {

	// Get the screen ID to target the Dashboard.
	$screen = get_current_screen();

	// Bail if not on the Dashboard screen.
	if ( 'dashboard' != $screen->id ) {
		return;
	} ?>
	<script>jQuery(document).ready(function(a){a('.post-count a[href="edit.php?post_type=post"]').text(function(){return a(this).text().replace('1 Post','1 <?php echo ucwords( singular() ); ?>')}),a('.post-count a[href="edit.php?post_type=post"]').text(function(){return a(this).text().replace('Posts','<?php echo ucwords( plural() ); ?>')})});</script>
	<?php
}
