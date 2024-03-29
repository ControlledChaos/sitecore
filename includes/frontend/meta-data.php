<?php
/**
 * Frontend meta tags
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Frontend
 * @since      1.0.0
 */

namespace SiteCore\Meta_Tags;

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

	// Print meta tags to the head.
	if ( get_option( 'enable_meta_tags', true ) ) {
		add_action( 'wp_head', $ns( 'print_meta_tags' ) );
	}

	// Print structured data scripts to the head.
	if ( get_option( 'enable_structured_data', true ) ) {
		add_action( 'wp_head', $ns( 'print_structured_data' ) );
	}
}

/**
 * Title content
 *
 * @since  1.0.0
 * @global object post The post object for the current post.
 * @return string Returns the text of the title content attribute.
 */
function title() {

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
		$title = wp_strip_all_tags( get_the_archive_title(), true );

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
 * Subtitle
 *
 * Development function.
 *
 * @since  1.0.0
 * @return string Returns an empty, filtered string.
 */
function subtitle() {
	$subtitle = '';
	return apply_filters( 'scp_meta_data_subtitle', $subtitle );
}

/**
 * Description content
 *
 * @since  1.0.0
 * @return string Returns the text of the description content attribute.
 */
function description() {

	// Site description (tagline).
	$site_desc = wp_strip_all_tags( get_bloginfo( 'description' ) );

	// Get options.
	$blog_desc = get_option( 'meta_description_blog_index', '' );

	// Get the manual excerpt from the metabox.
	$manual_excerpt = '';
	if (
		is_singular( get_post_type( get_the_ID() ) ) &&
		post_type_supports( get_post_type( get_the_ID() ), 'excerpt' )
		)
	{
		// Get the manual excerpt from the metabox.
		$manual_excerpt = get_post( get_the_ID() )->post_excerpt;
	}

	// Auto excerpt from content as a fallback.
	$auto_excerpt = wp_strip_all_tags( wp_trim_words( get_the_content(), 40, '&hellp;' ) );

	$search = apply_filters(
		'meta_description_search_results',
		wp_strip_all_tags(
			sprintf(
				'%s %s',
				__( 'Showing search results for', 'sitecore' ),
				get_search_query()
			)
		)
	);

	if ( is_home() ) {

		if ( 'posts' == get_option( 'show_on_front' ) ) {

			if ( ! empty( $blog_desc && ! ctype_space( $blog_desc ) ) ) {
				$description = $blog_desc;

			} elseif ( ! empty( $site_desc && ! ctype_space( $site_desc ) ) ) {
				$description = $site_desc;
			}

		} elseif ( ! empty( get_option( 'page_for_posts' ) ) ) {

			if ( has_excerpt( get_option( 'page_for_posts' ) ) ) {
				$manual_excerpt = get_post( get_option( 'page_for_posts' ) )->post_excerpt;

				if ( ! ctype_space( $manual_excerpt ) ) {
					$description = wp_strip_all_tags( $manual_excerpt );
				}

			} elseif ( ! empty( $site_desc && ! ctype_space( $site_desc ) ) ) {
				$description = $site_desc;
			}
		}

	} elseif ( 'page' == get_option( 'show_on_front' ) && is_front_page() ) {

		if (
			'excerpt' === get_option( 'meta_description_front_page' ) &&
			has_excerpt( get_option( 'page_on_front' ) ) &&
			! ctype_space( $manual_excerpt )
		) {
			$description = wp_strip_all_tags( $manual_excerpt );

		} elseif ( ! empty( $site_desc ) && ! ctype_space( $site_desc ) ) {
			$description = $site_desc;
		}

	} elseif ( is_search() ) {
		$description = $search;

	} elseif ( has_excerpt() && ! ctype_space( $manual_excerpt ) ) {
		$description = wp_strip_all_tags( $manual_excerpt );

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
 * @return mixed Returns the ID integer of queried objects.
 *               Returns the ID in a string.
 */
function paged() {

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
 * @return string Returns the text of the url content attribute.
 */
function url() {

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
			$url .= paged();
		}

	} elseif ( is_category() ) {
		$url = esc_url( get_category_link( $id ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a tag archive and on the first page.
	} elseif ( is_tag() ) {
		$url = esc_url( get_tag_link( $id ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a taxonomy archive and on the first page.
	} elseif ( is_tax() ) {
		$url = esc_url( get_term_link( $id ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in an author archive and on the first page.
	} elseif ( is_author() ) {
		$url = esc_url( get_author_posts_url( $id ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a day archive and on the first page.
	} elseif ( is_day() ) {
		$url = esc_url( get_day_link( $year, $month, $day ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a month archive and on the first page.
	} elseif ( is_month() ) {
		$url = esc_url( get_month_link( $year, $month ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a year archive and on the first page.
	} elseif ( is_year() ) {
		$url = esc_url( get_year_link( $year ) );

		if ( is_paged() ) {
			$url .= paged();
		}

	// If in a year archive and on the first page.
	} elseif ( is_date() ) {
		$url = esc_url( get_year_link( $year ) );

		if ( is_paged() ) {
			$url .= paged();
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
 * @global object post The post object for the current post.
 * @return string Returns the text of the author content attribute.
 */
function author() {

	// Get the current post.
	global $post;

	// Stop if on error page.
	if ( is_404() || is_null( $post ) ) {
		return;
	}

	$post_id = $post->ID;
	$post    = get_post( $post_id );

	// Get the author ID.
	$author_id = '';
	if ( is_singular() && post_type_supports( get_post_type( $post_id ), 'author' ) ) {

		// Get author meta data.
		$author_id    = $post->post_author;
		$display_name = get_the_author_meta( 'display_name', $author_id );
		$first_name   = get_the_author_meta( 'first_name', $author_id );
		$last_name    = get_the_author_meta( 'last_name', $author_id );

		// Use first & last name if available.
		if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
			$author = sprintf(
				'%s %s',
				$first_name,
				$last_name
			);

		// Default to display name.
		} else {
			$author = $display_name;
		}

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
 * @return string Returns the text of the datePublished content attribute.
 */
function published() {

	// Stop if on error page.
	if ( is_404() ) {
		return;
	}

	$date = get_the_date( 'M d, Y' );
	return apply_filters( 'scp_meta_data_published', $date );

}

/**
 * Modified date
 *
 * @since  1.0.0
 * @return string Returns the text of the dateModified content attribute.
 */
function modified() {

	// Stop if on error page.
	if ( is_404() ) {
		return;
	}

	$date = get_post_modified_time( 'M d, Y' );
	return apply_filters( 'scp_meta_data_modified', $date );
}

/**
 * Post genre
 *
 * Development function.
 *
 * @since  1.0.0
 * @return string Returns an empty, filtered string.
 */
function post_genre() {
	$genre = '';
	return apply_filters( 'scp_meta_data_post_genre', $genre );
}

/**
 * Get keywords
 *
 * Converts each line of a textarea field
 * to an array value.
 *
 * @since  1.0.0
 * @return mixed Returns a simple array or
 *               an empty string.
 */
function get_keywords() {

	// Get the content of the keywords option.
	$option = get_option( 'meta_site_keywords', '' );

	// Return an empty string if the option is empty or only contains spaces.
	if ( 0 == strlen( $option ) || ctype_space( $option ) ) {
		return '';
	}

	/**
	 * Convert each new line of the option to an array value,
	 * removing any carriage return entities.
	 */
	$keywords = explode( "\n", str_replace( "\r", "", $option ) );

	// Return an array of keywords or phrases.
	return $keywords;
}

/**
 * Keywords
 *
 * Converts the array of keywords or phrases
 * to a comma-separated string.
 *
 * @since  1.0.0
 * @return string Returns a comma-separated string of
 *                keywords or phrases, or an empty string.
 */
function keywords() {

	// Get keywords.
	$keywords = get_keywords();

	// Convert keywords array to a comma-separated string.
	if ( is_array( $keywords ) ) {
		$keywords = implode( ', ', $keywords );
	}

	// Return the comma-separated string of keywords or empty.
	return apply_filters( 'scp_meta_data_keywords', $keywords );
}

/**
 * Get image ID from URL
 *
 * @since  1.0.0
 * @param  string $url
 * @return mixed Returns an attachment ID or null.
 */
function image_url_id( $url ) {

	// Split the $url into two parts with the wp-content directory as the separator.
	$parsed_url = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	// Get the host of the current site and the host of the $url, ignoring www.
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
	if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
		return;
	}

	// Search the database for any attachment GUID with a partial path match.
	// Example: /uploads/2021/10/example-image.jpg
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

	// Return attachment ID or null.
	return $attachment[0];
}

/**
 * Image
 *
 * @since  1.0.0
 * @return string Returns the text of the image content attribute.
 */
function image() {

	global $post;

	$default_image = apply_filters( 'scp_default_meta_image_blog_index', '' );
	$index_image   = get_option( 'meta_image_blog_index', '' );
	$archive_image = get_option( 'meta_image_archive', '' );

	if ( has_image_size( 'meta-image' ) ) {
		$size = 'meta-image';
	} else {
		$size = 'large';
	}

	$src = $default_image;
	if ( is_home() && 'posts' === get_option( 'show_on_front' ) ) {

		if ( ! empty( $index_image ) ) {
			$image = wp_get_attachment_image_src( $index_image, $size, false );
			$src   = $image[0];
		}

	} elseif ( is_archive() ) {

		if ( ! empty( $archive_image ) ) {
			$image = wp_get_attachment_image_src( $archive_image, $size, false );
			$src   = $image[0];
		}

	} elseif ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size, false );
		$src   = $image[0];

	}
	return apply_filters( 'scp_meta_data_image', $src );
}

/**
 * Site logo
 *
 * @since  1.0.0
 * @return mixed Returns the logo url or null.
 */
function site_logo( $html = null ) {

	// Get the custom logo URL.
	$mod = get_theme_mod( 'custom_logo' );
	$url = wp_get_attachment_image_src( $mod , 'full' );

	$logo = '';
	if ( has_custom_logo( get_current_blog_id() ) ) {
		$logo = esc_attr( esc_url( $url[0] ) );
	}
	return apply_filters( 'scp_meta_data_logo', $logo );
}

/**
 * Default copyright
 *
 * @since  1.0.0
 * @return string Returns the text of the default copyright.
 */
function copyright_default() {

	$copy    = '%copy%';
	$year    = '%year%';
	$name    = '%name%';
	$default = sprintf(
		'%s%s %s. %s',
		$copy,
		$year,
		$name,
		__( 'All rights reserved.', 'sitecore' )
	);
	return apply_filters( 'scp_meta_copyright_default', $default );
}

/**
 * Copyright
 *
 * @since  1.0.0
 * @return string Returns the text of the copyright content attribute.
 */
function copyright() {

	$option  = get_option( 'meta_site_copyright', '' );
	$default = copyright_default();

	if ( ! empty( $option ) && ! ctype_space( $option ) ) {
		$text = $option;
	} else {
		$text = $default;
	}

	$text = str_replace( '%copy%', '&copy;', $text );
	$text = str_replace( '%year%', get_the_time( 'Y' ), $text );
	$text = str_replace( '%name%', get_bloginfo( 'name' ), $text );
	$copy = $text;

	return apply_filters( 'scp_meta_data_copyright', $copy );
}

/**
 * Word count
 *
 * Number of words in a single post.
 *
 * @since  1.0.0
 * @return integer Returns the number of words.
 */
function word_count() {

	$count = null;
	if ( is_singular() ) {
		$count = str_word_count( trim( strip_tags( get_the_content( null, false, get_the_ID() ) ) ) ?? 0 );
	}
	return $count;
}

/**
 * Print meta tags
 *
 * @since  1.0.0
 * @return void
 */
function print_meta_tags() {

	$tags  = meta_tags();
	$tags .= schema_tags();
	$tags .= open_graph_tags();
	$tags .= twitter_tags();
	$tags .= dublin_tags();

	return apply_filters( 'scp_print_meta_tags', $tags );
}

/**
 * Print structured data
 *
 * @since  1.0.0
 * @return void
 */
function print_structured_data() {

	$data = null;

	if ( is_singular( 'post' ) && true == get_option( 'posts_to_news' ) ) {
		$data = news_article_data();
	} elseif ( is_singular( 'post' ) ) {
		$data = blog_post_data();
	} elseif ( is_singular() ) {
		$data = article_data();
	}
	return apply_filters( 'scp_print_structured_data', $data );
}

/**
 * Standard meta tags
 *
 * @since  1.0.0
 * @return void
 */
function meta_tags() {
	include SCP_PATH . 'views/frontend/meta-data/meta-tags/standard.php';
}

/**
 * Schema meta tags
 *
 * @since  1.0.0
 * @return void
 */
function schema_tags() {
	include SCP_PATH . 'views/frontend/meta-data/meta-tags/schema.php';
}

/**
 * Open Graph meta tags
 *
 * @since  1.0.0
 * @return void
 */
function open_graph_tags() {
	include SCP_PATH . 'views/frontend/meta-data/meta-tags/open-graph.php';
}

/**
 * Twitter meta tags
 *
 * @since  1.0.0
 * @return void
 */
function twitter_tags() {
	include SCP_PATH . 'views/frontend/meta-data/meta-tags/twitter.php';
}

/**
 * Dublin Core meta tags
 *
 * @since  1.0.0
 * @return void
 */
function dublin_tags() {
	include SCP_PATH . 'views/frontend/meta-data/meta-tags/dublin.php';
}

/**
 * Article structured data
 *
 * @since  1.0.0
 * @return void
 */
function article_data() {
	include SCP_PATH . 'views/frontend/meta-data/structured-data/article.php';
}

/**
 * News article structured data
 *
 * @since  1.0.0
 * @return void
 */
function news_article_data() {
	include SCP_PATH . 'views/frontend/meta-data/structured-data/news-article.php';
}

/**
 * Blog post structured data
 *
 * @since  1.0.0
 * @return void
 */
function blog_post_data() {
	include SCP_PATH . 'views/frontend/meta-data/structured-data/blog-post.php';
}
