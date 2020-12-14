<?php
/**
 * Sample class to register a post type
 *
 * Copy this file and rename it to reflect
 * its new class name. Add to the autoloader
 * and intantiate where appropriate.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @access     public
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Sample_Type extends Register_Type {

	/**
	 * Post type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The database name of the post type.
	 */
	protected $type_key = 'sample_type';

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the post type.
	 */
	protected $singular = 'sample post';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the post type.
	 */
	protected $plural = 'sample posts';

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The dashicon class for book.
	 */
	protected $menu_icon = 'dashicons-welcome-learn-more';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run the parent constructor method.
		parent :: __construct();
	}

	/**
	 * Rewrite rules
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of rewrite rules.
	 */
	public function rewrite() {

		$rewrite = [
			'slug'       => 'sample',
			'with_front' => true,
			'feeds'      => true,
			'pages'      => true
		];

		return $rewrite;
	}
}
