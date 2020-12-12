<?php
/**
 * Admin class
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

class Admin {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @global string $pagenow Gets the filename of the current page.
	 * @return self
	 */
	public function __construct() {

		// Get the filename of the current page.
		global $pagenow;

		// Run related classes.
		new Add_Menu_Page;
		new Add_Submenu_Page;

		// Run the dashboard only on the backend index screen.
		if ( 'index.php' == $pagenow ) {
			new Dashboard;
		}
	}
}
