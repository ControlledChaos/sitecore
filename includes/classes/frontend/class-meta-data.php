<?php
/**
 * Frontend meta data
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front\Meta;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Meta_Data {

	/**
	 * The class object
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected static $class_object;

	/**
	 * Instance of the class
	 *
	 * This method can be used to call an instance
	 * of the class from outside the class.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns an instance of the class.
	 */
	public static function instance() {

		if ( is_null( self :: $class_object ) ) {
			self :: $class_object = new self();
		}

		// Return the instance.
		return self :: $class_object;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Title content
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object post The post object for the current post.
	 * @return string Returns the text of the title content attribute.
	 */
	public function title() {

		// Get the current posts for author archives.
		global $post;

		// Get the author ID.
		if ( is_singular() ) {
			$author_id = $post->post_author;
		} else {
			$author_id = null;
		}

		// Custom author title.
		$author = sprintf(
			'%1s %2s',
			__( 'Posts by', 'sitecore' ),
			get_the_author_meta( 'display_name', $author_id )
		);

		// Custom search title.
		$search = sprintf(
			'%1s %2s',
			__( 'Searching', 'sitecore' ),
			get_bloginfo( 'name' )
		);

		// Use the website name on the front page and 404 error page.
		if ( is_front_page() || is_404() ) {
			$title = get_bloginfo( 'name' );

		// Use the Posts Page title for the blog index.
		} elseif ( is_home() ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );

		// Use custom text for author pages.
		} elseif ( is_author() ) {
			$title = $author;

		// Use the archive title for the archive pages.
		} elseif ( is_archive() ) {
			$title = the_archive_title();

		// Use custom text for search pages.
		} elseif ( is_search() ) {
			$title = $search;

		// For all else, singular, use the post title.
		} elseif ( is_singular( get_post_type() ) ) {
			$title = get_the_title();

		} else {
			$title = '';
		}

		return apply_filters( 'scp_meta_data_title', $title );
	}

	/**
	 * Description content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the description content attribute.
	 */
	public function description() {

		$site_description = wp_strip_all_tags( get_bloginfo( 'description' ) );

		$search = wp_strip_all_tags(
			sprintf(
				'%s %s',
				__( 'Showing results for', 'sitecore' ),
				get_search_query()
			)
		);

		// Look for a manual excerpt.
		$manual_excerpt = wp_strip_all_tags( get_the_excerpt() );

		// Auto excerpt from content as a fallback.
		$auto_excerpt = wp_strip_all_tags( wp_trim_words( get_the_content(), 40, '&hellp;' ) );

		if ( ! empty( $site_description ) && ( is_front_page() || is_home() ) ) {
			$description = $site_description;

		} elseif ( is_search() ) {
			$description = $search;

		} elseif ( has_excerpt() ) {
			$description = $manual_excerpt;

		} elseif ( ! empty( $auto_excerpt ) ) {
			$description = $auto_excerpt;

		} elseif ( is_404() ) {
			$description = __( '404 Error: Not Found', 'sitecore' );

		} else {
			$description = '';
		}

		return apply_filters( 'scp_meta_data_description', $description );
	}

	/**
	 * Get the page number of paged URLs and combine with
	 * the "page" segment of the URL.
	 *
	 * Using the `template_redirect` hook to avoid errors
	 * thrown for trying to get objects before the query.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the ID integer of queried objects.
	 *               Returns the ID in a string.
	 */
	public static function paged() {

		// Get the page of paginated permalinks with a trailing slash.
		$page = trailingslashit( get_query_var( 'paged' ) );

		// Combine the two above.
		$paged = 'page/' . $page;

		// Return the page path.
		return $paged;
	}

	/**
	 * URL content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the url content attribute.
	 */
	public function url() {

		// Date variables.
		$year  = get_the_time( 'Y' );
		$month = get_the_time( 'm' );
		$day   = get_the_time( 'd' );

		// Set a variable for archive IDs.
		$id = get_queried_object_id();

		// Use the site URL for the front page and error page.
		if ( is_front_page() || is_404() ) {
			$url = esc_url( get_site_url() );

		// If in the blog index and on the first page.
		} elseif ( is_home() ) {
			$url = esc_url( get_permalink( get_option( 'page_for_posts' ) ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		} elseif ( is_category() ) {
			$url = esc_url( get_category_link( $id ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a tag archive and on the first page.
		} elseif ( is_tag() ) {
			$url = esc_url( get_tag_link( $id ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a taxonomy archive and on the first page.
		} elseif ( is_tax() ) {
			$url = esc_url( get_term_link( $id ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in an author archive and on the first page.
		} elseif ( is_author() ) {
			$url = esc_url( get_author_posts_url( $id ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a day archive and on the first page.
		} elseif ( is_day() ) {
			$url = esc_url( get_day_link( $year, $month, $day ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a month archive and on the first page.
		} elseif ( is_month() ) {
			$url = esc_url( get_month_link( $year, $month ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a year archive and on the first page.
		} elseif ( is_year() ) {
			$url = esc_url( get_year_link( $year ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// If in a year archive and on the first page.
		} elseif ( is_date() ) {
			$url = esc_url( get_year_link( $year ) );

			if ( is_paged() ) {
				$url .= $this->paged();
			}

		// For search pages, get the permalink for current terms of the query.
		} elseif ( is_search() ) {
			$url = esc_url( get_search_link() );

		// For everything else (singular) get the permalink.
		} else {
			$url = esc_url( get_the_permalink() );
		}

		// Return the appropriate URL in the content of the tag.
		return apply_filters( 'scp_meta_data_url', $url );
	}

	/**
	 * Author meta tag.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object post The post object for the current post.
	 * @return string Returns the text of the author content attribute.
	 */
	public function author() {

		// Stop if on error page.
		if ( is_404() ) {
			return;
		}

		// Get the current post.
		global $post;

		if ( is_null( $post ) ) {
			return;
		}

		$post_id = $post->ID;
		$post    = get_post( $post_id );

		// Get the author ID.
		$author_id = '';
		if ( is_singular() && post_type_supports( get_post_type( $post_id ), 'author' ) ) {
			$author_id = $post->post_author;
			$author    = get_the_author_meta( 'display_name', $author_id );

		// Otherwise use the website name.
		} else {
			$author = get_bloginfo( 'name' );
		}
		return apply_filters( 'tmg_meta_data_author', $author );
	}

	/**
	 * Published date
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the datePublished content attribute.
	 */
	public function published() {

		// Stop if on error page.
		if ( is_404() ) {
			return;
		}

		$date = get_the_date( 'Y-m-d' );
		return apply_filters( 'scp_meta_data_published', $date );

	}

	/**
	 * Modified date
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the dateModified content attribute.
	 */
	public function modified() {

		// Stop if on error page.
		if ( is_404() ) {
			return;
		}

		$date = get_post_modified_time( 'Y-m-d' );
		return apply_filters( 'scp_meta_data_modified', $date );
	}

	/**
	 * Image
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the image content attribute.
	 */
	public function image() {

		global $post;

		// Use the featured image on singular pages if there is one.
		if ( is_singular() && has_post_thumbnail() ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'meta-image', [ 1280, 720 ], true, '' );
			$src   = $image[0];

		} else {
			$src = '';
		}
		return apply_filters( 'scp_meta_data_image', $src );
	}

	/**
	 * Copyright
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the text of the copyright content attribute.
	 */
	public function copyright() {

		$copy = sprintf(
			'&copy; %s %s %s %s',
			__( 'Copyright', 'sitecore' ),
			get_the_time( 'Y' ),
			get_bloginfo( 'name' ),
			__( '. All rights reserved.', 'sitecore' )
		);
		return apply_filters( 'scp_meta_data_copyright', $copy );
	}
}

/**
 * Instance of the class
 *
 * @since  1.0.0
 * @access public
 * @return object Meta_Data Returns an instance of the class.
 */
function data() {
	return Meta_Data :: instance();
}
