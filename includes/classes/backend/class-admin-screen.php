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

class Admin_Screen {

	/**
	 * Capability
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The capability of the role of the current user.
	 */
	public $capability = '';

	/**
	 * Page slug
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string The URL slug of the page.
	 */
	public $slug = '';

	/**
	 * Page parent slug
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $parent_slug = '';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    integer The position of the menu entry.
	 */
	public $position = null;

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $icon_url = '';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $page_title = '';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $menu_title = '';

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access public
	 * @var    string
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
			// string $page_title,
			// string $menu_title,
			// string $capability,
			// string $menu_slug,
			// callable $function = '',
			// string $icon_url = '',
			// int $position = null
		);
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
			// string $parent_slug,
			// string $page_title,
			// string $menu_title,
			// string $capability,
			// string $menu_slug,
			// callable $function = '',
			// int $position = null
		);
	}
}
