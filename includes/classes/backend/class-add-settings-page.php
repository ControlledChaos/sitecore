<?php
/**
 * Add settings page class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Admin;
use SiteCore\Classes\Settings as Settings;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Settings_Page extends Add_Page {

	/**
	 * Settings class
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Settings
	 */
	private $settings;

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be displayed in the
	 *                title tags of the page when the
	 *                menu is selected.
	 */
	protected $page_title = 'Sample Settings Page';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be used for the menu.
	 */
	protected $menu_title = 'Sample Settings';

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
	 *                Should be unique for the menu page and
	 *                only include lowercase alphanumeric,
	 *                dashes, and underscores characters to be
	 *                compatible with sanitize_key().
	 */
	protected $menu_slug = 'sample-settings';

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The URL to the icon to be used for this menu.
	 *                * Pass a base64-encoded SVG using a data URI,
	 *                  which will be colored to match the color scheme.
	 *                  This should begin with 'data:image/svg+xml;base64,'.
	 *                * Pass the name of a Dashicons helper class to use
	 *                  a font icon, e.g. 'dashicons-chart-pie'.
	 *                * Pass 'none' to leave div.wp-menu-image empty so
	 *                  an icon can be added via CSS.
	 */
	protected $icon_url = 'dashicons-admin-settings';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 79;

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
	protected $description = 'Demonstration of adding a settings page.';

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
			'id'           => 'sample-one',
			'tab'          => __( 'One', SCP_CONFIG['domain'] ),
			'heading'      => __( 'Settings One', SCP_CONFIG['domain'] ),
			'settings'     => 'sample-settings',
			'content'      => '',
			'callback'     => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'           => 'sample-two',
			'tab'          => __( 'Two', SCP_CONFIG['domain'] ),
			'heading'      => __( 'Settings Two', SCP_CONFIG['domain'] ),
			'settings'     => '',
			'content'      => '',
			'callback'     => [ $this, 'sample_tab' ]
		] );
	}

	/**
	 * Sample tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function sample_tab() {
		include SCP_PATH . 'views/backend/pages/sample-page-content.php';
	}
}
