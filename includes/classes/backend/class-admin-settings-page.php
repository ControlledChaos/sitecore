<?php
/**
 * Admin settings page
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

class Admin_Settings_Page extends Add_Submenu_Page {

	/**
	 * Parent slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name for the parent menu or
	 *                the file name of a standard admin page.
	 */
	protected $parent_slug = 'options-general.php';

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be displayed in the
	 *                title tags of the page when the
	 *                menu is selected.
	 */
	protected $page_title = 'Administration Settings';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be used for the menu.
	 */
	protected $menu_title = 'Admin';

	/**
	 * Page slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name to refer to the menu by.
	 */
	protected $menu_slug = 'admin-options';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 35;

	/**
	 * Page description
	 *
	 * This is a non-native feature. The description is addeded by
	 * the template provided in this plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var    string The description of the page diplayed below the title.
	 */
	protected $description = 'Customize the content and user interfaces of administration pages.';

	/**
	 * Tabs hashtags
	 *
	 * Allow URL hashtags per open tab.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $tabs_hashtags = true;

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
	 * Page heading
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page heading.
	 */
	protected function heading() {
		return __( 'Administration Settings', SCP_DOMAIN );
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
	 * Page content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the page content.
	 */
	public function callback() {
		include SCP_PATH . 'views/backend/pages/settings-page-admin.php';
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
