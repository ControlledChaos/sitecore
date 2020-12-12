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

class Add_Menu_Page {

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
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The URL to the icon to be used for this menu.
	 *                * Pass a base64-encoded SVG using a data URI,
	 *                  which will be colored to match the color scheme.
	 *                  This should begin with 'data:image/svg+xml;base64,'.
	 *                * Pass the name of a Dashicons helper class to use
	 *                  a font icon, e.g. 'dashicons-chart-pie'.
	 *                * Pass 'none' to leave div.wp-menu-image empty so
	 *                  an icon can be added via CSS.
	 */
	public $icon_url = '';

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
        add_action( 'admin_menu', [ $this, 'menu_page' ] );
	}

	/**
	 * Register menu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function menu_page() {

		add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			$this->callback(),
			$this->icon_url,
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
