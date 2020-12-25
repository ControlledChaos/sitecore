<?php
/**
 * Sample menu page class
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
	protected $menu_slug = SCP_BASENAME . '-sample-settings';

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
	 * Help section
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Content is added to the contextual help
	 *                 section if true.
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

		parent :: __construct();

		$this->settings = new Settings\Settings( SCP_PATH . 'includes/settings/example-settings.php', 'my_example_settings' );

		// Add an optional settings validation filter (recommended)
		add_filter( $this->settings->get_option_group() . '_settings_validate', array( &$this, 'validate_settings' ) );
	}

	/**
	 * Add settings page.
	 */
	public function temp_add_page() {
		$this->settings->add_settings_page( array(
			// 'parent_slug' => 'options-general.php',
			'page_title'  => $this->page_title(),
			'menu_title'  => $this->menu_title(),
			'heading'     => $this->heading(),
			'capability'  => 'develop',
		) );
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
			'id'         => 'sample',
			'capability' => '',
			'tab'        => __( 'One' ),
			'heading'    => __( 'Settings One' ),
			'content'    => '',
			'callback'   => [ $this, 'sample_tab' ]
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
	 * Sample tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function sample_tab() {

		ob_start();

		include_once SCP_PATH . 'views/backend/pages/sample-page-content.php';

		$html = ob_get_clean();

		// Return the page markup.
		return $html;
	}

	/**
	 * Validate settings.
	 *
	 * @param $input
	 *
	 * @return mixed
	 */
	public function validate_settings( $input ) {
		// Do your settings validation here
		// Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
		return $input;
	}
}
