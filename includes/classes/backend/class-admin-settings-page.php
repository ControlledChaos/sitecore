<?php
/**
 * Admin settings page
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

class Admin_Settings_Page extends Add_Page {

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
			'menu_title'  => __( 'Admin', 'sitecore' ),
			'description' => __( 'Customize the content and user interfaces of administration pages.' )
		];

		$options = [
			'settings'      => true,
			'menu_slug'     => 'options-admin',
			'parent_slug'   => 'options-general.php',
			'position'      => 35,
			'tabs_hashtags' => true
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
			'id'         => 'menu',
			'capability' => 'read',
			'tab'        => __( 'Menu' ),
			'heading'    => __( 'Admin Menu' ),
			'content'    => '',
			'callback'   => [ $this, 'menu_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'dashboard',
			'capability' => 'read',
			'tab'        => __( 'Dashboard' ),
			'heading'    => __( 'User Dashboard' ),
			'content'    => '',
			'callback'   => [ $this, 'dashboard_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'toolbar',
			'capability' => 'read',
			'tab'        => __( 'Toolbar' ),
			'heading'    => __( 'User Toolbar' ),
			'content'    => '',
			'callback'   => [ $this, 'toolbar_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'header',
			'capability' => 'read',
			'tab'        => __( 'Header' ),
			'heading'    => __( 'Admin Header' ),
			'content'    => '',
			'callback'   => [ $this, 'header_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'footer',
			'capability' => 'read',
			'tab'        => __( 'Footer' ),
			'heading'    => __( 'Admin Footer' ),
			'content'    => '',
			'callback'   => [ $this, 'footer_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'users',
			'capability' => 'read',
			'tab'        => __( 'Users' ),
			'heading'    => __( 'User Options' ),
			'content'    => '',
			'callback'   => [ $this, 'users_tab' ]
		] );
	}

	/**
	 * Menu tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function menu_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-menu.php';
	}

	/**
	 * Dashboard tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function dashboard_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-dashboard.php';
	}

	/**
	 * Toolbar tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function toolbar_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-toolbar.php';
	}

	/**
	 * Header tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function header_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-header.php';
	}

	/**
	 * Footer tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function footer_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-footer.php';
	}

	/**
	 * Users tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function users_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-admin-users.php';
	}
}
