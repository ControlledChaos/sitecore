<?php
/**
 * Add menu page class
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

class Add_Menu_Page {

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be displayed in the
	 *                title tags of the page when the
	 *                menu is selected.
	 */
	protected $page_title = '';

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The text to be used for the menu.
	 */
	protected $menu_title = '';

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
	protected $menu_slug = '';

	/**
	 * Callback function
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The function to be called to output the
	 *                content for the page. Default value: 'callback'.
	 */
	protected $function = 'callback';

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
	protected $icon_url = 'dashicons-admin-generic';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 30;

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
	protected $description = '';

	/**
	 * Help section
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Content is added to the contextual help
	 *                 section if true. Default is false.
	 */
	protected $add_help = false;

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

		$this->help = add_menu_page(
			$this->page_title(),
			$this->menu_title(),
			strtolower( $this->capability ),
			strtolower( $this->menu_slug ),
			[ $this, $this->function ],
			strtolower( $this->icon_url ),
			(integer)$this->position
		);

		// Add content to the contextual help section.
		if ( true == $this->add_help ) {
			add_action( 'load-' . $this->help, [ $this, 'help' ] );
		}
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the conditional menu label.
	 */
	protected function page_title() {
		return $this->page_title;
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the conditional menu label.
	 */
	protected function menu_title() {
		return ucwords( $this->menu_title );
	}

	/**
	 * Page heading
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page heading.
	 */
	protected function heading() {
		return $this->menu_title();
	}

	/**
	 * Page content
	 *
	 * This can be used in the default `callback()` method.
	 * Hooking into `scp_submanu_page_content` adds
	 * content/markup inside the standard page markup.
	 * Use a new `callback()` method to override these defaults.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the page content.
	 */
	protected function content() {
		return '';
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the conditional menu label.
	 */
	protected function description() {

		$description = sprintf(
			'<p class="description">%s</p>',
			__( $this->description, SCP_DOMAIN )
		);

		if ( ! empty( $this->description ) ) {
			return $description;
		}

		return null;
	}

	/**
	 * Callback function
	 *
	 * It is recommended that the page output callback functions
	 * of classes that extend this class simply include a file in
	 * the `views/pages` directory to output the markup of the page.
	 *
	 * The following demonstrates the basic page wrap and heading
	 * markup that is standard to ClassicPress, WordPress, and the
	 * antibrand system.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function callback() {

		// Native page wrap element/class.
		$html = '<div class="wrap">';

		// Print a heading using the menu title variable.
		$html .= sprintf(
			'<h1>%s</h1>',
			__( $this->heading(), SCP_DOMAIN )
		);

		// Print a paragraph with native description class using the description variable.
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( $this->description(), SCP_DOMAIN )
		);

		$html .= $this->content();

		// End page wrap.
		$html .= '</div>';

		// Return the page markup.
		echo $html;
	}

	/**
	 * Help tabs
	 *
	 * Adds tabs to the contextual help section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function help() {

		// Get the current screen object.
		$screen = get_current_screen();

		// More information tab.
		$screen->add_help_tab( [
			'id'       => 'more_info',
			'title'    => __( 'More Information', SCP_DOMAIN ),
			'content'  => null,
			'callback' => [ $this, 'more_info' ]
		] );

		// Add a help sidebar.
		$screen->set_help_sidebar(
			$this->sidebar()
		);
	}

	/**
	 * More Infromation tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function more_info() {
		include_once SCP_PATH . 'views/backend/help/sample-more-info.php';
	}

	/**
	 * Help sidebar
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the content of the sidebar.
	 */
	public function sidebar() {

		ob_start();

		include_once SCP_PATH . 'views/backend/help/sample-sidebar.php';

		$html = ob_get_clean();

		// Return the page markup.
		return $html;
	}
}
