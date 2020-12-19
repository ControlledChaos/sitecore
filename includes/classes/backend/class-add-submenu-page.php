<?php
/**
 * Add submenu page class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Submenu_Page extends Add_Menu_Page {

	/**
	 * Parent slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name for the parent menu or
	 *                the file name of a standard admin page.
	 */
	protected $parent_slug = '';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 30;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();
	}

	/**
	 * Register submenu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_page() {

		$this->help = add_submenu_page(
			strtolower( $this->parent_slug ),
			$this->page_title(),
			$this->menu_title(),
			strtolower( $this->capability ),
			strtolower( $this->menu_slug ),
			[ $this, $this->function ],
			(integer)$this->position
		);

		// Add content to the contextual help section.
		if ( true == $this->add_help ) {
			add_action( 'load-' . $this->help, [ $this, 'help' ] );
		}
	}
}
