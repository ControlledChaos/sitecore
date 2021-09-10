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

		// Archive title.
		add_filter( 'get_the_archive_title', [ $this, 'archive_title' ] );
	}

	/**
	 * Archive title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the filtered title.
	 */
	public function archive_title( $title ) {

		// Remove any HTML, words, digits, and spaces before the title.
		$title = preg_replace( '#^[\w\d\s]+:\s*#', '', strip_tags( $title ) );
		return $title;
	}
}
