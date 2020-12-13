<?php
/**
 * Add Manage Website page
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

class Manage_Website_Page extends Add_Submenu_Page {

	/**
	 * Parent slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name for the parent menu or
	 *                the file name of a standard admin page.
	 */
	protected $parent_slug = 'index.php';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be displayed in the
	 *                title tags of the page when the
	 *                menu is selected.
	 */
	protected $page_title = 'Help Managing This Website';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be used for the menu.
	 */
	protected $menu_title = 'Manage Website';

	/**
	 * Capability
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The capability required for the menu
	 *                to be displayed to the user.
	 */
	protected $capability = 'manage_options';

	/**
	 * Page slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name to refer to the menu by.
	 */
	protected $menu_slug = 'manage-website';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 1;

	/**
	 * Page description
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var    string The description of the page diplayed below the title.
	 */
	protected $description = 'This page provides you with help managing this website.';

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
	 * Callback function
	 *
	 * @todo Conditional page output files, one being for
	 * the antibrand admin screen class. But this will
	 * work in the meantime.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function callback() {
		include_once SCP_PATH . 'views/backend/pages/manage-website.php';
	}
}
