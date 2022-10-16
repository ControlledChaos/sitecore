<?php
/**
 * Add page class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Admin;
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Page {

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
	 * This is a non-native feature. The description is added by
	 * the template provided in this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The description of the page displayed below the title.
	 */
	protected $description = '';

	/**
	 * The content tab data associated with the screen, if any.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $content_tabs = [];

	/**
	 * Add tab data attributes to tabbed contents associated with screen, if any.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $content_tab_attributes = [];

	/**
	 * Tabs hashtags
	 *
	 * Allow URL hashtags per open tab.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $tabs_hashtags = false;

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
	 * ACF options page
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean True is the page is an ACF options page.
	 *                  Default is false.
	 */
	protected $acf_options = false;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Add an about page for the plugin.
		add_action( 'admin_menu', [ $this, 'add_page' ], 9 );

		// Add screen options.
		add_action( 'admin_head', [ $this, 'screen_options' ] );

		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Print admin styles to head.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ], 20 );
	}

	/**
	 * Register menu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_page() {

		// Return null for ACF options pages.
		if ( true == $this->acf_options ) {
			return null;
		}

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
	 * @return string Returns the page title.
	 */
	protected function page_title() {
		return __( $this->page_title, 'sitecore' );
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the menu label.
	 */
	protected function menu_title() {
		return ucwords( __( $this->menu_title, 'sitecore' ) );
	}

	/**
	 * Page heading
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page heading.
	 */
	protected function heading() {
		return $this->page_title();
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page description.
	 */
	protected function description() {

		$description = sprintf(
			'<p class="description">%s</p>',
			__( $this->description, 'sitecore' )
		);

		if ( ! empty( $this->description ) ) {
			return $description;
		}

		return null;
	}

	/**
	 * Gets the content tabs registered for the screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Content tabs with arguments.
	 */
	public function get_content_tabs() {

		// Set the tabbed content.
		$this->tabs();

		$content_tabs = $this->content_tabs;
		$priorities   = [];

		foreach ( $content_tabs as $content_tab ) {

			if ( isset( $priorities[ $content_tab['priority'] ] ) ) {
				$priorities[ $content_tab['priority'] ][] = $content_tab;
			} else {
				$priorities[ $content_tab['priority'] ] = [ $content_tab ];
			}
		}

		ksort( $priorities );
		$sorted = [];

		foreach ( $priorities as $list ) {
			foreach ( $list as $tab ) {
				$sorted[ $tab['id'] ] = $tab;
			}
		}
		return $sorted;
	}

	/**
	 * Get content tab
	 *
	 * Gets the arguments for a content tab.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $id Content Tab ID.
	 * @return array Content tab arguments.
	 */
	public function get_content_tab( $id ) {

		if ( ! isset( $this->content_tabs[ $id ] ) ) {
			return null;
		}

		return $this->content_tabs[ $id ];
	}

	/**
	 * Has content tab
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $id Content Tab ID.
	 * @return boolean Returns true if the tab ID is set.
	 */
	public function has_content_tab( $id ) {

		if ( ! isset( $this->content_tabs[ $id ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add content tab
	 *
	 * Adds a content tab to the tabbed content for the screen.
	 * Call this on the load-$pagenow hook for the relevant screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Returns array of arguments used to
	 *                     display the content tab.
	 * @return void
	 */
	public function add_content_tab( $args ) {

		$defaults = [
			'id'             => null,
			'id_before'      => null,
			'id_after'       => null,
			'url'            => null,
			'capability'     => 'read',
			'tab'            => null,
			'heading'        => null,
			'heading_before' => '<h2>',
			'heading_after'  => '</h2>',
			'class'          => 'content-tab',
			'icon'           => null,
			'content'        => null,
			'settings'       => '', // @todo Use or remove.
			'hide-if-no-js'  => false,
			'callback'       => null,
			'priority'       => 10,
		];

		$args       = wp_parse_args( $args, $defaults );
		$args['id'] = sanitize_html_class( $args['id'] );

		// Ensure there is an an ID and tab.
		if ( ! $args['id'] || ! $args['tab'] ) {
			return;
		}

		// Allows for overriding an existing tab with that ID.
		$this->content_tabs[ $args['id'] ] = $args;
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
		return null;
	}

	/**
	 * Render tabbed content
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_tabs() {
		return $this->render_content_tabs();
	}

	/**
	 * Render content tabs
	 *
	 * Renders the markup for the tabbed content container,
	 * the list of tab items, and the content container of
	 * each tab.
	 *
	 * List item closing tags are omitted to prevent whitespace nodes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the markup of the tabs and the tabs container.
	 */
	public function render_content_tabs() {

		$tabs = $this->get_content_tabs();

		if ( true == $this->tabs_hashtags ) {
			$hashtags = 'true';
		} else {
			$hashtags = 'false';
		}

		if ( is_array( $tabs ) && count( $tabs ) > 1 ) {

			$tabbed         = ' data-tabbed="tabbed"';
			$wrap_class     = 'registered-content-wrap admin-tabs';
			$content_class  = 'registered-content tab-content';

		} else {
			$tabbed         = '';
			$wrap_class     = 'registered-content-wrap';
			$content_class  = 'registered-content';
		}

		?>
		<div class="<?php echo $wrap_class; ?>" <?php echo $tabbed; ?> data-tabdeeplinking="<?php echo $hashtags; ?>" >

			<?php if ( count( $tabs ) > 1 ) : ?>

			<ul class="admin-tabs-list hide-if-no-js">
			<?php
			foreach ( $tabs as $tab ) :

				if ( current_user_can( $tab['capability'] ) ) :

					$content_id  = $tab['id_before'] . $tab['id'] . $tab['id_after'];
					$content_url = $tab['url'];

					if ( ! empty( $tab['url'] ) ) {
						$href = $tab['url'];
					} else {
						$href = "#$content_id";
					}

					if ( ! empty( $tab['icon'] ) ) {
						$icon = sprintf(
							'<span class="content-tab-icon %1s"></span> ',
							$tab['icon']
						);
					} else {
						$icon = null;
					}
					?>
						<li class="<?php echo $tab['class']; ?>">
							<a href="<?php echo esc_url( $href ); ?>" aria-controls="<?php echo esc_attr( $content_id ); ?>">
								<?php echo $icon . $tab['tab']; ?>
							</a>
					<?php
				endif;
			endforeach;
			?>

			</ul>

			<?php endif; ?>

			<?php
			foreach ( $this->get_content_tabs() as $tab ) :

				if ( true == $tab['hide-if-no-js'] ) {
					$content_class .= ' hide-if-no-js';
				}


				if ( current_user_can( $tab['capability'] ) ) :

					$content_id = $tab['id_before'] . $tab['id'] . $tab['id_after'];

					if ( ! empty( $tab['heading'] ) ) {
						$heading_before = $tab['heading_before'];
						$heading_after  = $tab['heading_after'];
					} else {
						$heading_before = '<h2>';
						$heading_after  = '</h2>';
					}
				?>
				<div id="<?php echo esc_attr( $content_id ); ?>" class="<?php echo $content_class; ?>">

					<?php echo $tab['heading_before'] . $tab['heading'] . $tab['heading_after']; ?>
					<?php

					// Development hook.
					do_action( "content_{$tab['id']}_tab_before" );

					// Print tab content, apply development filter.
					echo apply_filters( "content_{$tab['id']}_tab", $tab['content'] );

					// If it exists, fire tab callback.
					if ( ! empty( $tab['callback'] ) ) {
						call_user_func_array( $tab['callback'], [ $this, $tab ] );
					}

					// Development hook.
					do_action( "content_{$tab['id']}_tab_after" );

					?>
				</div>
				<?php
				endif;
			endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Remove content tab
	 *
	 * Removes a content tab from the contextual content for the screen.
	 *
	 * @since 1.0.0
	 * @param string $id The content tab ID.
	 */
	public function remove_content_tab( $id ) {
		unset( $this->content_tabs[ $id ] );
	}

	/**
	 * Remove content tabs
	 *
	 * Removes all content tabs from the contextual content for the screen.
	 *
	 * @since 1.0.0
	 */
	public function remove_content_tabs() {
		$this->content_tabs = [];
	}

	/**
	 * Gets the content tab attributes.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_content_tab_attributes() {
		return $this->content_tab_attributes;
	}

	/**
	 * Sets the content tab attributes.
	 *
	 * @since 1.0.0
	 * @param array
	 */
	public function set_content_tab_attributes( $args = [] ) {
		$this->content_tab_attributes = $args;
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
		do_action( 'render_screen_tabs_' . $this->menu_slug );
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

		add_action( 'render_screen_tabs_' . $this->menu_slug, [ $this, 'render_tabs' ] );

		// Native page wrap element/class.
		echo  '<div class="wrap">';

		// Print a heading using the menu title variable.
		echo  sprintf(
			'<h1>%s</h1>',
			__( $this->heading(), 'sitecore' )
		);

		// Print a paragraph with native description class using the description variable.
		echo  $this->description();

		$this->content();

		// End page wrap.
		echo  '</div>';
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
			'title'    => __( 'More Information', 'sitecore' ),
			'content'  => null,
			'callback' => [ $this, 'more_info' ]
		] );

		// Add a help sidebar.
		$screen->set_help_sidebar(
			$this->sidebar()
		);
	}

	/**
	 * More Information tab
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
	 * @access protected
	 * @return mixed Returns the content of the sidebar.
	 */
	protected function sidebar() {

		ob_start();

		include_once SCP_PATH . 'views/backend/help/sample-sidebar.php';

		$html = ob_get_clean();

		// Return the page markup.
		return $html;
	}

	/**
	 * Screen options
	 *
	 * Add to the screen option tab at the top of the page
	 * for showing and hiding page elements.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function screen_options() {

		// add_screen_option();
	}

	/**
	 * Enqueue page scripts
	 *
	 * This is for scripts that shall not be
	 * overridden by class extension. Specific
	 * screens should use enqueue_scripts() to
	 * enqueue scripts for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {}

	/**
	 * Print page styles
	 *
	 * This is for styles that shall not be
	 * overridden by class extension. Specific
	 * screens should use print_styles() to
	 * print styles for its screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string
	 */
	public function admin_print_styles() {}
}
