<?php
/**
 * Add submenu page class
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

class Add_Submenu_Page {

	/**
	 * Parent slug
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The slug name for the parent menu or
	 *                the file name of a standard admin page.
	 */
	protected $parent_slug = '';

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
	protected $capability = '';

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
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 *                 Default value: null
	 */
	protected $position = null;

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
        add_action( 'admin_menu', [ $this, 'submenu_page' ] );
	}

	/**
	 * Register submenu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function submenu_page() {

		$this->help = add_submenu_page(
			strtolower( $this->parent_slug ),
			$this->page_title(),
			$this->menu_title(),
			strtolower( $this->capability ),
			strtolower( $this->menu_slug ),
			[ $this, $this->function ],
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
	 * @access public
	 * @return string Returns the conditional menu label.
	 */
	public function page_title() {
		return $this->page_title;
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the conditional menu label.
	 */
	public function menu_title() {
		return ucwords( $this->menu_title );
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the conditional menu label.
	 */
	public function description() {

		$description = sprintf(
			'<p class="description">%s</p>',
			__( $this->description, SCP_DOMAIN )
		);
		return $description;
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
			__( $this->menu_title(), SCP_DOMAIN )
		);

		// Print a paragraph with native description class using the description variable.
		$html .= sprintf(
			'<p class="description">%s</p>',
			__( $this->description(), SCP_DOMAIN )
		);

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

		// Add to this page only.
		$screen = get_current_screen();
		if ( $screen->id != $this->help ) {
			return;
		}

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
	 * The HTML markup for the sidebar can be written directly
	 * in the `sidebar()` function. However, a sample sidebar
	 * file is provided, with output buffering functions, for
	 * demonstration.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the content of the sidebar.
	 */
	public function sidebar() {

		ob_start();

		require SCP_PATH . 'views/backend/help/sample-sidebar.php';

		$html = ob_get_clean();

		// Return the page markup.
		return $html;
	}
}
