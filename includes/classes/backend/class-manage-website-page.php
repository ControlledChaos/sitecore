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

		$options = [
			'capability'    => 'manage_options',
			'menu_slug'     => 'manage-website',
			'parent_slug'   => 'index.php',
			'icon_url'      => 'dashicons-welcome-learn-more',
			'position'      => 1,
			'add_help'      => true
		];

		parent :: __construct(
			$labels,
			$options
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
	public function content_callback() {
		include_once SCP_PATH . 'views/backend/pages/manage-website.php';
	}
}
