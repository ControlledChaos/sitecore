<?php
/**
 * Network admin settings page
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

class Network_Admin_Settings_Page extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Administration Settings', 'sitecore' ),
			'menu_title'  => __( 'Network Admin', 'sitecore' ),
			'description' => __( 'Customize the content and user interfaces of network administration pages.', 'sitecore' )
		];

		$options = [
			'settings'      => [
				'print_form' => true,
				'capability' => 'manage_network_options'
			],
			'network'     => true,
			'capability'  => 'manage_options',
			'menu_slug'   => 'options-network-admin',
			'parent_slug' => 'settings.php',
			'position'    => 10,
			'add_help'    => false
		];

		parent :: __construct(
			$labels,
			$options,
			$priority
		);
	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tabs() {

		$this->add_content_tab( [
			'id'         => 'dashboard',
			'capability' => 'manage_options',
			'tab'        => __( 'Dashboard' ),
			'heading'    => __( 'User Dashboard' ),
			'icon'       => 'dashicons-dashboard',
			'content'    => '',
			'callback'   => [ $this, 'dashboard_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'menu',
			'capability' => 'manage_options',
			'tab'        => __( 'Menu' ),
			'heading'    => __( 'Admin Menu' ),
			'icon'       => 'dashicons-menu-alt',
			'content'    => '',
			'callback'   => [ $this, 'menu_tab' ]
		] );
	}

	/**
	 * Dashboard tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function dashboard_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-network-admin-dashboard.php';
	}

	/**
	 * Menu tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function menu_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-network-admin-menu.php';
	}
}
