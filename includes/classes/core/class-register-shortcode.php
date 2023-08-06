<?php
/**
 * Base class to register a shortcode
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Shortcode {

	/**
	 * Shortcode tag
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The tag name of the shortcode.
	 */
	protected $code_tag = '';

	/**
	 * Shortcode attributes
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array An array of shortcode attributes.
	 */
	protected $code_atts = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $code_tag, $code_atts ) {
		$this->code_tag  = (string) $code_tag;
		$this->code_atts = wp_parse_args( $code_atts, [] );
	}

	/**
	 * Add shortcode
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_shortcode() {

		// Get shortcode tag.
		$tag = $this->code_tag;

		// Do not add if child class does not have a tag.
		if ( ! empty( $tag ) ) {
			add_shortcode( $tag, [ $this, 'output' ] );
		}
	}

	/**
	 * Shortcode output
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function output( $atts, $content = null ) {
		return $content;
	}
}
