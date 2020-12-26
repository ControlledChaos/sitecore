<?php
/**
 * Media class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Media
 * @since      1.0.0
 */

namespace SiteCore\Classes\Media;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Media {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Register media type taxonomy.
		new Register_Media_Type;

		// Add categories and tags to media library items.
		add_action( 'init' , [ $this, 'media_taxonomies' ] );

		// Add image sizes.
		add_action( 'init', [ $this, 'image_sizes' ] );

		// Add image sizes to insert media UI.
		add_filter( 'image_size_names_choose', [ $this, 'insert_custom_image_sizes' ] );

		// Default add single image link.
        add_action( 'admin_init', [ $this, 'image_link' ], 10 );

        // Default add gallery images link.
        add_filter( 'media_view_settings', [ $this, 'gallery_link' ], 10 );

		// Add featured images to RSS feeds.
		add_filter( 'the_excerpt_rss', [ $this, 'rss_featured_images' ] );
		add_filter( 'the_content_feed', [ $this, 'rss_featured_images' ] );

		// Remove the default gallery shortcode to be replaced.
		remove_shortcode( 'gallery' );

		// Replace the default gallery shortcode for new attributes.
		add_shortcode( 'gallery', [ $this, 'gallery_shortcode' ] );
	}

	/**
	 * Add taxonomies to media library
	 *
	 * Includes categories and tags for attachment post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function media_taxonomies() {

		// Add standard categories.
		register_taxonomy_for_object_type( 'category', 'attachment' );

		// Add standard tags.
		register_taxonomy_for_object_type( 'post_tag', 'attachment' );
	}

	/**
	 * Add image sizes
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function image_sizes() {

		// For link embedding and sharing on social sites.
		add_image_size( __( 'meta-image', SCP_DOMAIN ), 1280, 720, true );

		// For use as featured image in admin columns.
		add_image_size( __( 'column-thumbnail', SCP_DOMAIN ), 48, 48, true );
	}

	/**
	 * Add image sizes to media UI
	 *
	 * Adds custom image sizes to "Insert Media" user interface
	 * and adds custom class to the `<img>` tag.
	 *
	 * @since  1.0.0
	 * @access public
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
	 * Note: As of this comment on June 21, 2018 the `image_default_link_type`
	 * option only works with the rich text editor, not with the new block editor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 *
	 * @todo Review this after WordPress 5.0 is released or if/when the new block
	 *       editor adds the option to link to the full size image.
	 */
	public function image_link() {

		$image_set = get_option( 'image_default_link_type' );

		if ( $image_set !== 'file' ) { // Could be 'none'
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
	 * @access public
	 * @return mixed[] Modifies the WordPress gallery shortcode.
	 *
	 * @todo Review this after WordPress 5.0 is released or if/when the new block
	 *       editor adds the option to link to the full size images.
	 */
	public function gallery_link( $settings ) {

		$settings['galleryDefaults']['link'] = 'file';

		return $settings;
	}

	/**
	 * Add featured images to RSS feeds
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object post The post object for the current post.
	 * @param  string $content Gets the current post content.
	 * @return string $content Returns the filered current post content.
	 */
	public function rss_featured_images( $content ) {

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

		// Return the filered post content.
		return $content;
	}

	/**
	 * New gallery shortcode
	 *
	 * Adds Fancybox attributes to the default attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $attr Stores the shortcode attributes.
	 * @param  object $content Gets the post content.
	 * @param  int $id Gets the ID of the shortcode.
	 * @return mixed[]
	 */
	public function gallery_shortcode( $attr, $content, $id ) {

		// Get the post opbject.
		$post = get_post();

		// Variable to be used for shortcode instances.
		static $instance = 0;

		// Start counter for shortcode instances.
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		/**
		 * Filters the default gallery shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default gallery template.
		 *
		 * @param string $output   The gallery output. Default empty.
		 * @param array  $attr     Attributes of the gallery shortcode.
		 * @param int    $instance Unique numeric ID of this gallery shortcode instance.
		 */

		$output = apply_filters( 'post_gallery', '', $attr, $instance );
		if ( $output != '' ) {
			return $output;
		}

		$html5 = current_theme_supports( 'html5', 'gallery' );
		$atts  = apply_filters(
			'scp_fancybox_atts',
				shortcode_atts( [

				// Default parameters.
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post ? $post->ID : 0,
				'itemtag'    => $html5 ? 'figure'     : 'dl',
				'icontag'    => $html5 ? 'div'        : 'dt',
				'captiontag' => $html5 ? 'figcaption' : 'dd',
				'columns'    => 3,
				'size'       => 'thumbnail',
				'include'    => '',
				'exclude'    => '',
				'link'       => '',

				// Fancybox parameters.
				'loop'       => 'false',
				'infobar'    => 'false',
				'toolbar'    => 'true',
				'buttons'    => [
					'slideshow',
					'fullscreen',
					'thumbs',
					'close'
				],
				'arrows'     => 'true',
				'thumbs'     => 'false',
				'captions'   => 'title',
				'protected'  => 'false'

			],
			$attr,
			'gallery'
			)
		);

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {

			$_attachments = get_posts( [
				'include'        => $atts['include'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			] );

			$attachments = [];

			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}

		} elseif ( ! empty( $atts['exclude'] ) ) {

			$attachments = get_children( [
				'post_parent'    => $id,
				'exclude'        => $atts['exclude'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			] );

		} else {

			$attachments = get_children( [
				'post_parent'    => $id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => $atts['order'],
				'orderby'        => $atts['orderby']
			] );

		}

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {

			$output = "\n";

			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}

			return $output;

		}

		$itemtag    = tag_escape( $atts['itemtag'] );
		$captiontag = tag_escape( $atts['captiontag'] );
		$icontag    = tag_escape( $atts['icontag'] );
		$valid_tags = wp_kses_allowed_html( 'post' );

		if ( ! isset( $valid_tags[ $itemtag ] ) ) {
			$itemtag = 'dl';
		}
		if ( ! isset( $valid_tags[ $captiontag ] ) ) {
			$captiontag = 'dd';
		}
		if ( ! isset( $valid_tags[ $icontag ] ) ) {
			$icontag = 'dt';
		}

		$columns       = intval( $atts['columns'] );
		$itemwidth     = $columns > 0 ? floor(100/$columns) : 100;
		$float         = is_rtl() ? 'right' : 'left';
		$selector      = "gallery-{$instance}";
		$gallery_style = '';

		// Fancybox parameters.
		$loop_images  = tag_escape( $atts['loop'] );
		$show_infobar = tag_escape( $atts['infobar'] );
		$show_toolbar = tag_escape( $atts['toolbar'] );
		$captions     = tag_escape( $atts['captions'] );
		$show_arrows  = tag_escape( $atts['arrows'] );
		$show_thumbs  = tag_escape( $atts['thumbs'] );
		$protected    = tag_escape( $atts['protected'] );

		if ( 'true' == $loop_images ) {
			$loop = 'true';
		} else {
			$loop = 'false';
		}

		if ( 'true' == $show_infobar ) {
			$infobar = 'true';
		} else {
			$infobar = 'false';
		}

		if ( 'false' == $show_toolbar ) {
			$toolbar = 'false';
		} else {
			$toolbar = 'true';
		}

		if ( 'false' == $show_arrows ) {
			$arrows = 'false';
		} else {
			$arrows = 'true';
		}

		if ( 'true' == $show_thumbs ) {
			$thumbs = '{ autoStart : true }';
		} else {
			$thumbs = '{ autoStart : false }';
		}

		if ( 'true' == $protected ) {
			$protect = 'true';
		} else {
			$protect = 'false';
		}

		/**
		 * Filters whether to print default gallery styles.
		 *
		 * @since 3.1.0
		 *
		 * @param bool $print Whether to print default gallery styles.
		 *                    Defaults to false if the theme supports HTML5 galleries.
		 *                    Otherwise, defaults to true.
		 */
		if ( apply_filters( 'use_default_gallery_style', ! $html5 ) ) {

			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
				/* see gallery_shortcode() in wp-includes/media.php */
			</style>\n\t\t";

		}

		$size_class      = sanitize_html_class( $atts['size'] );
		$gallery_div     = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$gallery_options =
		"<script>
		jQuery(document).ready( function() {
			jQuery('a.$selector-link').fancybox({
				loop    : {$loop},
				infobar : {$infobar},
				toolbar : {$toolbar},
				arrows  : {$arrows},
				thumbs  : {$thumbs},
				protect : {$protect}
			});
		});
		</script>
		";

		/**
		 * Filters the default gallery shortcode CSS styles.
		 *
		 * @since 1.0.0
		 * @param string $gallery_style Default CSS styles and opening HTML div container
		 *                              for the gallery shortcode output.
		 */
		$output = apply_filters( 'gallery_style', $gallery_style . $gallery_options . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {

			if ( trim( $attachment->post_excerpt ) ) {
				$attr = [ 'aria-describedby' => '$selector->$id' ];
			} else {
				$attr = '';
			}

			$trim  = trim( $attachment->post_excerpt );
			$text  = wptexturize( $attachment->post_excerpt );
			$title = get_the_title( $id );

			if ( $captions == 'captions' && $captiontag && $trim ) {
				$caption = sprintf( ' data-caption="%1s"', $text );
			} elseif ( $captions == 'captions' && $captiontag && ! $trim ) {
				$caption = '';
			} elseif ( $captions == 'title' && $captiontag && ! $title ) {
				$caption = '';
			} elseif ( $captions == 'title' && $captiontag && $trim ) {
				$caption = sprintf( ' data-caption="%1s"', $title );
			} elseif ( $captions == 'title' && $captiontag && $title ) {
				$caption = sprintf( ' data-caption="%1s"', $title );
			} elseif ( $captions == 'title' && $captiontag && ! $title ) {
				$caption = '';
			} elseif ( $captions == 'hide' ) {
				$caption = '';
			} else {
				$caption = '';
			}

			// $fancybox = 'data-type="image" data-fancybox="gallery" title=""';

			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {

				$image_output = sprintf(
					'<a class="%1s-link" href="%2s" data-type="image" data-fancybox="group-%3s"%4s>%5s</a>',
					$selector,
					wp_get_attachment_url( $id ),
					$selector,
					$caption,
					wp_get_attachment_image( $id, $atts['size'], false, $attr )
				);

			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
			} else {
				$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
			}

			$image_meta  = wp_get_attachment_metadata( $id );

			$orientation = '';

			if ( isset( $image_meta['height'], $image_meta['width'] ) ) {

				if ( $image_meta['height'] > $image_meta['width'] ) {
					$orientation = 'portrait';
				} else {
					$orientation = 'landscape';
				}

			}

			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon {$orientation}'>
					$image_output
				</{$icontag}>";

			if ( $captiontag && trim( $attachment->post_excerpt ) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
					" . wptexturize( $attachment->post_excerpt ) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
				$output .= '<br style="clear: both" />';
			}
		}

		if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
			$output .= "
				<br style='clear: both' />";
		}

		$output .= "
			</div>\n";

		return $output;
	}
}
