<?php
/**
 * Frontend template filters
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Template_Filters {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Post type archive titles & descriptions.
		add_filter( 'get_the_archive_title', [ $this, 'archive_titles' ] );
		add_filter( 'get_the_archive_description', [ $this, 'archive_descriptions' ] );
	}

	/**
	 * Post type archive titles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the filtered title.
	 */
	public function archive_titles( $title ) {

		// Remove any HTML, words, digits, and spaces before the title.
		$title = preg_replace( '#^[\w\d\s]+:\s*#', '', strip_tags( $title ) );

		// Get the page for posts.
		$front = (string) get_option( 'show_on_front' );
		$posts = (int) get_option( 'page_for_posts' );

		// Blog pages title.
		if (
			'post' === get_post_type() &&
			is_home() && is_main_query() &&
			'page' === $front &&
			! empty( $posts )
		) {
			$title = get_the_title( $posts );
		}
		return $title;
	}

	/**
	 * Post type archive descriptions
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $description The default post type description.
	 * @return string Returns the new post type description.
	 */
	public function archive_descriptions( $description ) {

		// Blog pages description.
		if (
			'post' === get_post_type() &&
			is_home() && is_main_query()
		) {
			return sprintf(
				'<p>%s</p>',
				__( 'This is a filtered sample description for the default post type.', 'sitecore' )
			);

		// Sample post type description.
		} elseif (
			'sample_type' === get_post_type() &&
			is_home() && is_main_query()
		) {
			return sprintf(
				'<p>%s</p>',
				__( 'This is a filtered description for the sample post type.', 'sitecore' )
			);
		}
		return $description;
	}
}
