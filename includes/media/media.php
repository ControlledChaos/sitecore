<?php
/**
 * Media class
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Media
 * @since      1.0.0
 */

namespace SiteCore\Media;

use SiteCore\Classes\Core as Core_Class;

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

	// Add custom media taxonomy.
	add_action( 'plugins_loaded', function() {
		new Core_Class\Register_Media_Type;
	}, 11 );

	// Add categories and tags to media library items.
	add_action( 'init' , $ns( 'media_taxonomies' ) );

	// Add image sizes.
	add_action( 'init', $ns( 'image_sizes' ) );

	// Add image sizes to insert media UI.
	add_filter( 'image_size_names_choose', $ns( 'insert_custom_image_sizes' ) );

	// Default add single image link.
	add_action( 'admin_init', $ns( 'image_link' ), 10 );

	// Default add gallery images link.
	add_filter( 'media_view_settings', $ns( 'gallery_link' ), 10 );

	// Add lightbox attributes to images & galleries.
	add_filter( 'wp_get_attachment_link', $ns( 'lightbox_gallery_attribute' ), 10, 2 );
	add_filter( 'the_content', $ns( 'lightbox_image_attribute' ), 10, 1 );

	// Add featured images to RSS feeds.
	add_filter( 'the_excerpt_rss', $ns( 'rss_featured_images' ) );
	add_filter( 'the_content_feed', $ns( 'rss_featured_images' ) );
}

/**
 * Add taxonomies to media library
 *
 * Includes categories and tags for attachment post type.
 *
 * @since  1.0.0
 * @return void
 */
function media_taxonomies() {

	// Add standard categories.
	register_taxonomy_for_object_type( 'category', 'attachment' );

	// Add standard tags.
	register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}

/**
 * Add image sizes
 *
 * @since  1.0.0
 * @return void
 */
function image_sizes() {

	/**
	 * Meta image
	 *
	 * For link embedding and sharing on social sites.
	 * 16:9 aspect ratio.
	 */
	add_image_size( 'meta-image', 1280, 720, true );

	/**
	 * Admin avatar & icon
	 *
	 * For use as with user avatars and admin icons.
	 * 1:1 aspect ratio.
	 */
	add_image_size( 'admin-avatar', 160, 160, true );

	/**
	 * Column thumbnail
	 *
	 * For use as featured image in admin columns.
	 * 1:1 aspect ratio.
	 */
	add_image_size( 'column-thumbnail', 48, 48, true );
}

/**
 * Add image sizes to media UI
 *
 * Adds custom image sizes to "Insert Media" user interface
 * and adds custom class to the `<img>` tag.
 *
 * @since  1.0.0
 * @param  array $sizes Gets the array of image size names.
 * @global array $_wp_additional_image_sizes Gets the array of custom image size names.
 * @return array $sizes Returns an array of image size names.
 */
function insert_custom_image_sizes( $sizes ) {

	// Access global variables.
	global $_wp_additional_image_sizes;

	// Return default sizes if no custom sizes.
	if ( empty( $_wp_additional_image_sizes ) ) {
		return $sizes;
	}

	// Capitalize custom image size names and remove hyphens.
	foreach ( $_wp_additional_image_sizes as $id => $data ) {

		if ( ! isset( $sizes[$id] ) ) {
			$sizes[$id] = ucwords( str_replace( '-', ' ', $id ) );
		}
	}

	// Return the modified array of sizes.
	return $sizes;
}

/**
 * Default link when adding an image
 *
 * @since  1.0.0
 * @return void
 */
function image_link() {

	$image_link = get_option( 'image_default_link_type' );

	if ( $image_link !== 'file' ) { // Could be 'none' or custom.
		update_option( 'image_default_link_type', 'file' );
	}
}

/**
 * Default gallery images link
 *
 * Note: As of this comment on June 21, 2018 this function only works with
 * galleries in the rich text editor, not with the new block editor galleries.
 *
 * @since  1.0.0
 * @return mixed[] Modifies the WordPress gallery shortcode.
 *
 * @todo Review this after WordPress 5.0 is released or if/when the new block
 *       editor adds the option to link to the full size images.
 */
function gallery_link( $settings ) {

	$settings['galleryDefaults']['link'] = 'file';

	return $settings;
}

/**
 * Gallery lightbox attributes
 *
 * @since  1.0.0
 * @param  string $content
 * @param  integer $id
 * @return string
 */
function lightbox_gallery_attribute( $content, $id ) {

	// Restore title attribute.
	$title   = get_the_title( $id );
	$caption = wp_get_attachment_caption( $id );
	return str_replace( '<a', '<a data-type="image" data-fancybox="gallery" data-caption="' . $caption . '" title="' . esc_attr( $title ) . '" ', $content );
}


/**
 * Single image lightbox attributes
 *
 * @since  1.0.0
 * @param  string $content
 * @return string
 */
function lightbox_image_attribute( $content ) {

		global $post;

		$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
		$replace = '<a$1href=$2$3.$4$5 data-type="image" data-fancybox="image" data-caption="">';
		$content = preg_replace( $pattern, $replace, $content );

		return $content;
}

/**
 * Add featured images to RSS feeds
 *
 * @since  1.0.0
 * @global object post The post object for the current post.
 * @param  string $content Gets the current post content.
 * @return string $content Returns the filtered current post content.
 */
function rss_featured_images( $content ) {

	// Get the post object.
	global $post;

	// Apply a filter for conditional image sizes.
	$size = apply_filters( 'scp_rss_featured_image_size', 'medium' );

	/**
	 * Use this layout only if the post has a featured image.
	 *
	 * The image and the content/excerpt are in separate <div> tags
	 * to get the content below the image.
	 */
	if ( has_post_thumbnail( $post->ID ) ) {
		$content = sprintf( '<div>%1s</div><div>%2s</div>', get_the_post_thumbnail( $post->ID, $size, [] ), $content );
	}

	// Return the filtered post content.
	return $content;
}
