<?php
/**
 * Add Manage Website page
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Manage_Website_Page extends Add_Page {

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
	 * Help section
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Content is added to the contextual help
	 *                 section if true. Default is false.
	 */
	protected $add_help = true;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Help Managing This Website', 'sitecore' ),
			'menu_title'  => __( 'Manage Website', 'sitecore' ),
			'description' => __( 'This page provides you with help managing this website.' )
		];

		parent :: __construct(
			true,
			$labels
		);
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
