<?php
/**
 * Post title filter
 *
 * Extend this class to filter the post title
 * by post types.
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

class Title_Filter {

	/**
	 * Post types
	 *
	 * Array of the post types to be filtered,
	 * as they are registered.
	 *
	 * @example [ 'post', 'sample_type' ]
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the post types to be filtered.
	 */
	public $post_types = [];

	/**
	 * Post titles
	 *
	 * Array of the post type titles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array Array of the post type titles.
	 */
	public $post_title = [];

	/**
	 * Title filter priority
	 *
	 * When to filter the title.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set filter priority.
	 */
	protected $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $post_types, $post_title, $priority ) {

		$types = [];
		$title = [
			'singular'  => $this->singular_title(),
			'index'     => $this->index_title(),
			'post_type' => $this->post_type_title(),
			'archive'   => $this->archive_title(),
			'category'  => $this->category_title(),
			'tag'       => $this->tag_title(),
			'date'      => $this->date_title(),
			'author'    => $this->author_title()
		];

		$this->post_types = wp_parse_args( $post_types, $types );
		$this->post_title = wp_parse_args( $post_title, $title );
		$this->priority   = (int) $priority;

		add_action( 'init', [ $this, 'custom_title' ], $this->priority );
	}

	/**
	 * Check for post types
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Returns true if the post_types property
	 *                 has at least one post type.
	 */
	protected function is_post_type() {
		if ( isset( $this->post_types ) && is_array( $this->post_types ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Custom title
	 *
	 * Adds the title filter.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function custom_title() {
		if ( $this->is_post_type() ) {
			add_filter( 'the_title', [ $this, 'the_title' ], 10, 1 );
		}
	}

	/**
	 * Singular post title
	 *
	 * Title if the post is singular.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function singular_title() {

		$post_title = $this->post_title;

		if ( is_array( $post_title ) && array_key_exists( 'singular', $post_title ) ) {

			$singular = $post_title['singular'];

			if ( ! empty( $singular ) && ! ctype_space( $singular ) ) {
				return $singular;
			}
		}
		return null;
	}

	/**
	 * Post index title
	 *
	 * Title if the post is in the main blog pages.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function index_title() {
		return $this->singular_title();
	}

	/**
	 * Post type archive title
	 *
	 * Title if the post is in its post type archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function post_type_title() {
		return $this->index_title();
	}

	/**
	 * Post archive title
	 *
	 * Title if the post is in an archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function archive_title() {

		$post_title = $this->post_title;

		if ( is_array( $post_title ) && array_key_exists( 'archive', $post_title ) ) {

			$archive = $post_title['archive'];

			if ( ! empty( $archive ) && ! ctype_space( $archive ) ) {
				return $archive;
			}
		}
		return null;
	}

	/**
	 * Category archive title
	 *
	 * Title if the post is in a category archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function category_title() {
		return $this->archive_title();
	}

	/**
	 * Tag archive title
	 *
	 * Title if the post is in a tag archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function tag_title() {
		return $this->archive_title();
	}

	/**
	 * Date archive title
	 *
	 * Title if the post is in a date archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function date_title() {
		return $this->archive_title();
	}

	/**
	 * Author archive title
	 *
	 * Title if the post is in an author archive.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the title text or null.
	 */
	protected function author_title() {
		return $this->archive_title();
	}

	/**
	 * Title text
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $title The value of the title field.
	 * @return string Returns the text of the post title.
	 */
	public function the_title( $title ) {

		// Get the array of post types to be filtered.
		$types = $this->post_types;

		// Modify the title for each post type in the post_types property.
		foreach ( $types as $type ) {

			// Default title for post types not modified.
			$title = $title;

			// If the post type matches one in the loop.
			if ( $type == get_post_type( get_the_ID() ) ) {

				$object = get_post_type_object( $type );

				if ( $object->labels->singular_name ) {
					$name = $object->labels->singular_name;
				} else {
					$name = $object->labels->name;
				}

				/**
				 * Using `in_the_loop()` will modify all instances of
				 * the title in the loop. Titles outside of the loop
				 * are not modified.
				 */
				if ( is_post_type_archive( $type ) && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->post_type_title() ) ) {
						$title = $this->post_type_title();
					}

				} elseif ( is_category() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->category_title() ) ) {
						$title = $this->category_title();
					}

				} elseif ( is_tag() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->tag_title() ) ) {
						$title = $this->tag_title();
					}

				} elseif ( is_date() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->date_title() ) ) {
						$title = $this->date_title();
					}

				} elseif ( is_author() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->author_title() ) ) {
						$title = $this->author_title();
					}

				} elseif ( is_archive() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->archive_title() ) ) {
						$title = $this->archive_title();
					}

				} elseif ( is_home() && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->index_title() ) ) {
						$title = $this->index_title();
					}

				} elseif ( is_singular( $type ) && is_main_query() && in_the_loop() ) {

					if ( ! is_null( $this->singular_title() ) ) {
						$title = $this->singular_title();
					}
				}
			}
		}

		// Return the modified or unmodified title.
		return $title;
	}
}
