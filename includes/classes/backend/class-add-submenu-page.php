<?php
/**
 * Plugin initialization class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Submenu_Page {

	/**
	 * Parent slug
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The slug name for the parent menu or
	 *                the file name of a standard admin page.
	 */
	public $parent_slug = '';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The text to be displayed in the
	 *                title tags of the page when the
	 *                menu is selected.
	 */
	public $page_title = '';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The text to be used for the menu.
	 */
	public $menu_title = '';

	/**
	 * Capability
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The capability required for the menu
	 *                to be displayed to the user.
	 */
	public $capability = '';

	/**
	 * Page slug
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The slug name to refer to the menu by.
	 *                Should be unique for the menu page and
	 *                only include lowercase alphanumeric,
	 *                dashes, and underscores characters to be
	 *                compatible with sanitize_key().
	 */
	public $menu_slug = '';

	/**
	 * Callback function
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The function to be called to output the
	 *                content for the page. Default value: ''.
	 */
	public $function = '';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    integer The position in the menu order this item should appear.
	 *                 Default value: null
	 */
	public $position = null;

	/**
	 * Page description
	 *
	 * This is a non-native feature. The description is addeded by
	 * the template provided in this plugin.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var    string The description of the page diplayed below the title.
	 */
	public $description = '';

	/**
	 * Instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns the instance.
	 */
	public static function instance() {
		return new self;
	}

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Add an about page for the plugin.
        add_action( 'admin_menu', [ $this, 'submenu_page' ] );
	}

	/**
	 * Register submenu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function submenu_page() {

		add_submenu_page(
			$this->parent_slug,
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			$this->callback(),
			$this->position
		);
	}

	/**
	 * Callback function
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function callback() {
		// Page content.
	}
}
