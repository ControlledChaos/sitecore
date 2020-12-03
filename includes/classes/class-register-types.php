<?php
/**
 * Base class to register a post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Includes
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Type {

	/**
	 * Post type
	 *
	 * Maximum 20 characters. May only contain lowercase alphanumeric
	 * characters, dashes, and underscores.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The database name of the post type.
	 */
	public $type_key = '';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Register post type.
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
     * Register post type
     *
     * Note for WordPress 5.0 or greater:
     * If you want your post type to adopt the block edit_form_image_editor
     * rather than using the classic editor then set `show_in_rest` to `true`.
     *
     * @since  1.0.0
	 * @access public
	 * @return void
     */
    public function register() {

		register_post_type(
			$this->$type_key,
			$options
		);
	}
}
