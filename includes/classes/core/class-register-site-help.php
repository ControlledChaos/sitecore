<?php
/**
 * Register the site help post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Site_Help extends Register_Type {

	/**
	 * Post type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The database name of the post type.
	 */
	protected $type_key = 'site_help';

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the post type.
	 */
	protected $singular = 'help page';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the post type.
	 */
	protected $plural = 'help pages';

	/**
	 * Public type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type is public.
	 */
	protected $public = false;

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set position.
	 */
	protected $menu_position = 100;

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The dashicon class for book.
	 */
	protected $menu_icon = 'dashicons-welcome-learn-more';

	/**
	 * Show in admin menu
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Whether the post type displays
	 *                 links in the admin menu.
	 */
	protected $show_in_menu = false;

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
			'slug'       => 'site-help',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => false
		];

		return $rewrite;
	}
}
