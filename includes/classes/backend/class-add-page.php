<?php
/**
 * Add admin page class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

use SiteCore\Compatibility as Compat;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Add_Page {

	/**
	 * Page labels
	 *
	 * Various text for the admin page, not
	 * including page content or forms.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array An array of page labels.
	 */
	public $page_labels = [];

	/**
	 * Page options
	 *
	 * @since  1.0.0
	 * @access public
	 * @var array An array of page options.
	 */
	public $page_options = [];

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
	 * Hook priority
	 *
	 * When to hook to the admin menu.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The numeral to set hook priority.
	 */
	protected $priority = 10;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct( $page_labels, $page_options, $priority ) {

		$labels = [
			'page_title'  => '',
			'menu_title'  => '',
			'description' => ''
		];

		$options = [
			'settings'       => false,
			'network'        => false,
			'acf'            => [
				'acf_page'   => false,
				'capability' => 'manage_options'
			],
			'capability'     => 'manage_options',
			'menu_slug'      => '',
			'parent_slug'    => '',
			'icon_url'       => 'dashicons-admin-generic',
			'position'       => 30,
			'tabs_hashtags'  => false,
			'add_help'       => false,
			'screen_options' => false
		];

		$this->page_labels  = wp_parse_args( $page_labels, $labels );
		$this->page_options = wp_parse_args( $page_options, $options );
		$this->priority     = (int) $priority;
	}

	/**
	 * Add admin page
	 *
	 * Add hooks and filters.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_page() {

		/**
		 * Add an ACF options page and load field groups
		 * if ACF options page function is available.
		 */
		if ( $this->page_options['acf']['acf_page'] && Compat\active_acf_pro() ) {
			add_action( 'acf/init', [ $this, 'acf_page_init' ], $this->priority );
			add_action( 'acf/init', [ $this, 'acf_field_groups' ] );

		/**
		 * If network is true, if is in multisite mode,
		 * if the plugin is network active, add as
		 * a network admin page.
		 */
		} elseif ( is_multisite() && $this->page_options['network'] ) {
			if ( is_main_site() ) {
				add_action( 'network_admin_menu', [ $this, 'page_init' ], $this->priority );

				// Save network settings.
				add_action( 'network_admin_edit_' . $this->page_options['menu_slug'], [ $this, 'save_network_settings' ] );
			}

		// Otherwise add a regular admin page.
		} else {
			add_action( 'admin_menu', [ $this, 'page_init' ], $this->priority );
		}

		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Print admin styles to head.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ], 20 );
	}

	/**
	 * Page init
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function page_init() {
		return $this->add_menu_page();
	}

	/**
	 * ACF page init
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_page_init() {
		return $this->add_acf_page();
	}

	/**
	 * Is subpage
	 *
	 * Checks if the admin page class is a subpage.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean Returns true if the page is a subpage.
	 */
	protected function is_subpage() {

		if ( ! empty( $this->page_options['parent_slug'] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Register menu page
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function add_menu_page() {

		$screen = $this->page_options['menu_slug'];

		if ( $this->is_subpage() ) {

			$screen = add_submenu_page(
				strtolower( $this->page_options['parent_slug'] ),
				$this->page_title(),
				$this->menu_title(),
				strtolower( $this->page_options['capability'] ),
				strtolower( $this->page_options['menu_slug'] ),
				[ $this, 'content_callback' ],
				(integer)$this->page_options['position']
			);

		} else {

			$screen = add_menu_page(
				$this->page_title(),
				$this->menu_title(),
				strtolower( $this->page_options['capability'] ),
				strtolower( $this->page_options['menu_slug'] ),
				[ $this, 'content_callback' ],
				strtolower( $this->page_options['icon_url'] ),
				(integer)$this->page_options['position']
			);
		}

		// Add content to the contextual help section.
		if ( true == $this->page_options['add_help'] ) {
			add_action( "load-{$screen}", [ $this, 'help' ] );
		}

		if ( true == $this->page_options['screen_options'] ) {
			add_action( "load-{$screen}", [ $this, 'screen_options' ] );
		} else {
			add_action( "load-{$screen}", function() {
				add_filter( 'screen_options_show_screen', '__return_false' );
			} );
		}

		add_action( "admin_print_scripts-{$screen}", [ $this, 'admin_print_scripts' ] );
	}

	/**
	 * Register ACF options page
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function add_acf_page() {

		// Stop here if ACF Pro is not active.
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		$screen = $this->page_options['menu_slug'];

		$options = [
			'network'         => $this->page_options['network'],
			'page_title'      => $this->page_title(),
			'menu_title'      => $this->menu_title(),
			'menu_slug'       => strtolower( $this->page_options['menu_slug'] ),
			'capability'      => strtolower( $this->page_options['capability'] ),
			'parent_slug'     => strtolower( $this->page_options['parent_slug'] ),
			'position'        => (integer)$this->page_options['position'],
			'icon_url'        => strtolower( $this->page_options['icon_url'] ),
			'redirect'        => true,
			'post_id'         => 'options',
			'autoload'        => false,
			'update_button'   => $this->acf_update_button(),
			'updated_message' => $this->acf_update_message()
		];

		if ( isset( $this->page_options['acf']['capability'] ) ) {
			$acf_capability = $this->page_options['acf']['capability'];
		} else {
			$acf_capability = $this->page_options['capability'];
		}

		if ( $this->is_subpage() ) {
			acf_add_options_sub_page( $options );
		} else {
			acf_add_options_page( $options );
		}

		if ( ! current_user_can( $acf_capability ) ) {
			add_action( "admin_head-{$screen}", function() {
				remove_meta_box( 'submitdiv', 'acf_options_page', 'side' );
			} );
		}

		if ( false == $this->page_options['screen_options'] ) {
			add_action( "load-{$screen}", function() {
				add_filter( 'screen_options_show_screen', '__return_false' );
			} );
		}

		add_action( "admin_footer-{$screen}", [ $this, 'admin_print_scripts' ], 20 );
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page title.
	 */
	protected function page_title() {
		return $this->page_labels['page_title'];
	}

	/**
	 * Menu title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the menu label.
	 */
	protected function menu_title() {
		return $this->page_labels['menu_title'];
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
			$this->page_labels['description']
		);

		if ( ! empty( $this->page_labels['description'] ) ) {
			return $description;
		}
		return null;
	}

	/**
	 * ACF update button
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the text of the button.
	 */
	protected function acf_update_button() {
		return apply_filters( 'scp_acf_update_button', __( 'Update Page', 'sitecore' ) );
	}

	/**
	 * ACF update message
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the text of the message.
	 */
	protected function acf_update_message() {
		return apply_filters( 'scp_acf_update_message', __( 'Page Updated', 'sitecore' ) );
	}

	/**
	 * Form user access
	 *
	 * Prints the page form elements if current
	 * user is allowed to save settings. used
	 * as the parameter in `current_user_can()`.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the capability or
	 *                a non-existent capability.
	 */
	protected function form_user_access() {

		if ( is_array( $this->page_options['settings'] ) ) {
			if ( array_key_exists( 'capability', $this->page_options['settings'] ) ) {
				return $this->page_options['settings']['capability'];
			}
		} else {
			return 'scp_no_user_form_access';
		}
	}

	/**
	 * Form action
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed
	 */
	protected function form_action() {

		if (
			! $this->page_options['settings']['print_form'] ||
			$this->page_options['acf']['acf_page']
		) {
			return null;
		}

		if ( is_multisite() ) {
			if ( is_network_admin() ) {
				return add_query_arg( 'action', $this->page_options['menu_slug'], 'edit.php' );
			}
		}
		return 'options.php';
	}

	/**
	 * Begin settings form
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the opening form markup or null.
	 */
	protected function form_open() {

		if ( ! current_user_can( $this->form_user_access() ) ) {
			return null;
		}

		if (
			! $this->page_options['settings']['print_form'] ||
			$this->page_options['acf']['acf_page']
		) {
			return null;
		}

		$html = sprintf(
			'<form method="post" action="%s" novalidate="novalidate">',
			$this->form_action()
		);
		return $html;
	}

	/**
	 * End settings form
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the closing form markup or null.
	 */
	protected function form_close() {

		if ( ! current_user_can( $this->form_user_access() ) ) {
			return null;
		}

		if (
			! $this->page_options['settings']['print_form'] ||
			$this->page_options['acf']['acf_page']
		) {
			return null;
		}

		if ( array_key_exists( 'submit_label', $this->page_labels ) && $this->page_labels['submit_label'] ) {
			$label = $this->page_labels['submit_label'];
		} else {
			$label = __( 'Save Settings', 'sitecore' );
		}

		$html = submit_button(
			$label,
			'primary',
			'submit',
			true,
			[]
		);
		$html .= '</form>';

		return $html;
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

			if ( current_user_can( $content_tab['capability'] ) ) {
				if ( isset( $priorities[ $content_tab['priority'] ] ) ) {
					$priorities[ $content_tab['priority'] ][] = $content_tab;
				} else {
					$priorities[ $content_tab['priority'] ] = [ $content_tab ];
				}
			}
		}

		ksort( $priorities );
		$sorted = [];

		foreach ( $priorities as $list ) {
			foreach ( $list as $tab ) {
				if ( is_null( $tab['parent_id'] ) ) {
					$sorted[ $tab['id'] ] = $tab;
				}
			}
		}
		return $sorted;
	}

	/**
	 * Gets the content sub-tabs registered for the screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $id The ID of the parent tab.
	 * @return array Content tabs with arguments.
	 */
	public function get_content_sub_tabs( $id ) {

		// Get content tabs for the page.
		$tabs = $this->content_tabs;

		// Set up an array of sub-tabs.
		$sub_tabs = [];

		// Run through tabs for the page.
		foreach ( $tabs as $tab ) {

			// Weed out tabs without a parent ID set.
			if ( ! is_null( $tab['parent_id'] ) ) {

				/**
				 * If the tab's parent ID value matches
				 * that of the function instance.
				 */
				if ( $id == $tab['parent_id'] ) {
					$sub_tabs[] = $this->get_content_tab( $tab['id'] );
				}
			}
		}

		// Return the sub-tabs for the parent tab.
		return $sub_tabs;
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
			'parent_id'      => null,
			'url'            => null,
			'capability'     => 'read',
			'tab'            => null,
			'heading'        => null,
			'heading_before' => '<h2>',
			'heading_after'  => '</h2>',
			'class'          => 'content-tab',
			'icon'           => null,
			'content'        => null,
			'sub_tabs_top'   => false,
			'sub_tabs_only'  => false,
			'settings'       => '',
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

		// Get the primary tabs for this page.
		$tabs = $this->get_content_tabs();

		// Whether to add hashtags to the URL when switching tabs.
		$hashtags = 'false';
		if ( true == $this->page_options['tabs_hashtags'] ) {
			$hashtags = 'true';
		}

		/**
		 * Wrapping elements attributes
		 *
		 * Different styles used if only one tab is registered.
		 * Data attribute only if more than one tab.
		 */
		if ( is_array( $tabs ) && count( $tabs ) > 1 ) {
			$tabbed        = 'data-tabbed="tabbed"';
			$wrap_class    = 'registered-content-wrap admin-tabs';
			$content_class = 'registered-content tab-content';
		} else {
			$tabbed        = '';
			$wrap_class    = 'registered-content-wrap';
			$content_class = 'registered-content';
		}

		?>
		<div class="<?php echo $wrap_class; ?>" <?php echo $tabbed; ?> data-tabdeeplinking="<?php echo $hashtags; ?>" >

			<?php

			// Print the tabs list if more than one tab is registered.
			if ( count( $tabs ) > 1 ) :

			?>

			<ul class="admin-tabs-list hide-if-no-js">

			<?php

			// Linked list item for each tab.
			foreach ( $tabs as $tab ) :

				// Don't print the tab if the user is not allowed to view.
				if ( current_user_can( $tab['capability'] ) ) :

					// If the tab is simply a link to another URL.
					if ( ! empty( $tab['url'] ) ) {
						$href = $tab['url'];

					// If the link is to a content block on the page.
					} else {
						$href = '#' . $tab['id'];
					}

					// Add an inline icon if one is set for the tab.
					$icon = null;
					if ( ! empty( $tab['icon'] ) ) {
						$icon = sprintf(
							'<span class="content-tab-icon dashicons %1s"></span> ',
							$tab['icon']
						);
					}

					// Print the linked list item with or without icon.
					?>
						<li class="<?php echo $tab['class']; ?>">
							<a href="<?php echo esc_url( $href ); ?>" aria-controls="<?php echo esc_attr( $tab['id'] ); ?>">
								<?php echo $icon . $tab['tab']; ?>
							</a>
					<?php
				endif; // If capability.
			endforeach; // For each tab.

			?>
			</ul>
			<?php

			endif; // If tabs count is more than one.

			// Content for each tab.
			foreach ( $tabs as $tab ) :

				// Get sub-tabs for the tab ID.
				$sub_tabs = $this->get_content_sub_tabs( $tab['id'] );

				// Add has sub-tabs class if so.
				if ( ! empty( $sub_tabs ) ) {
					$content_class .= ' has-sub-tabs';
				}

				// Sub-tabs only class.
				if ( ! empty( $sub_tabs ) && true == $tab['sub_tabs_only'] ) {
					$content_class .= ' sub-tabs-only';
				}

				// Add a tabs position class.
				if ( ! empty( $sub_tabs ) && true == $tab['sub_tabs_top'] && false == $tab['sub_tabs_only'] ) {
					$content_class .= ' sub-tabs-top';
				} elseif ( ! empty( $sub_tabs ) && false == $tab['sub_tabs_only'] ) {
					$content_class .= ' sub-tabs-bottom';
				}

				// Add hide class if true.
				if ( ! empty( $sub_tabs ) && true == $tab['hide-if-no-js'] ) {
					$content_class .= ' hide-if-no-js';
				}

				// Don't print the content if the user is not allowed to view.
				if ( current_user_can( $tab['capability'] ) ) :

				// Wrap content with another tabs switcher element.
				?>
				<div id="<?php echo esc_attr( $tab['id'] ); ?>" class="<?php echo $content_class; ?>">

					<?php

					// Print the tab heading.
					echo $tab['heading_before'] . $tab['heading'] . $tab['heading_after'];

					/**
					 * Default tab content
					 *
					 * Prints the content and callback if the tab has
					 * no sub-tabs or it has sub-tabs, sub-tabs are not
					 * set to display at top, and not set for sub-tabs only.
					 */
					if ( empty( $sub_tabs ) || ( $sub_tabs && ! $tab['sub_tabs_top'] && ! $tab['sub_tabs_only'] ) ) :

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

					endif;

					// If the tab has sub-tabs.
					if ( ! empty( $sub_tabs ) ) :

						/**
						 * Wrapping elements attributes
						 *
						 * Different styles used if only one tab is registered.
						 * Data attribute only if more than one tab.
						 */
						if ( is_array( $sub_tabs ) && count( $sub_tabs ) > 1 ) {
							$wrap_class    = 'registered-content-wrap admin-tabs';
							$content_class = 'registered-content tab-content';
						} else {
							$wrap_class    = 'registered-content-wrap';
							$content_class = 'registered-content';
						}

					?>
					<div class="<?php echo $wrap_class; ?>" <?php echo $tabbed; ?> data-tabdeeplinking="<?php echo $hashtags; ?>" >

						<?php

						// Print the tabs list if more than one tab is registered.
						if ( count( $sub_tabs ) > 1 ) :

						?>

						<ul class="admin-tabs-list hide-if-no-js">

						<?php

						// Linked tab for each sub tab of the parent tab.
						foreach ( $sub_tabs as $sub_tab ) :

						// If the tab is simply a link to another URL.
						if ( ! empty( $sub_tab['url'] ) ) {
							$href = $sub_tab['url'];

						// If the link is to a sub-content block in the parent content.
						} else {
							$href = '#' . $sub_tab['id'];
						}

						// Add an inline icon if one is set for the tab.
						$icon = null;
						if ( ! empty( $sub_tab['icon'] ) ) {
							$icon = sprintf(
								'<span class="content-tab-icon dashicons %1s"></span> ',
								$sub_tab['icon']
							);
						}

						// Don't print the tab if the user is not allowed to view.
						if ( current_user_can( $sub_tab['capability'] ) ) :

						?>
							<li class="<?php echo $sub_tab['class']; ?>">
								<a href="<?php echo esc_url( $href ); ?>" aria-controls="<?php echo esc_attr( $sub_tab['id'] ); ?>">
									<?php echo $icon . $sub_tab['tab']; ?>
								</a>
						<?php
						endif; // If capability.
					endforeach; // For each tab.

					echo '</ul>';
					endif; // If more than one sub-tab.

					// Content for each sub tab of the parent tab.
					foreach ( $sub_tabs as $sub_tab ) :

					// Don't print the content if the user is not allowed to view.
					if ( current_user_can( $sub_tab['capability'] ) ) :

					?>
						<div id="<?php echo $sub_tab['id']; ?>" class="<?php echo $content_class; ?>">
							<?php echo $sub_tab['heading_before'] . $sub_tab['heading'] . $sub_tab['heading_after']; ?>
							<?php

							// Development hook.
							do_action( "content_{$sub_tab['id']}_sub_tab_before" );

							// Print sub-tab content, apply development filter.
							echo apply_filters( "content_{$sub_tab['id']}_sub_tab", $sub_tab['content'] );

							// If it exists, fire sub-tab callback.
							if ( ! empty( $sub_tab['callback'] ) ) {
								call_user_func_array( $sub_tab['callback'], [ $this, $sub_tab ] );
							}

							// Development hook.
							do_action( "content_{$sub_tab['id']}_sub_tab_after" );

							?>
						</div>
					<?php
					endif; // If capability.
					endforeach; // For each sub-tab.

					echo '</div>';

					endif; // If tab has sub-tabs.

					/**
					 * Alternate tab content
					 *
					 * Prints the content and callback if the tab has
					 * sub-tabs, sub-tabs are set to display at top,
					 * and not set for sub-tabs only.
					 */
					if ( $sub_tabs && $tab['sub_tabs_top'] && ! $tab['sub_tabs_only'] ) :

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

					endif;

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
	 * This can be used in the default `content_callback()` method.
	 * Hooking into `scp_submenu_page_content` adds
	 * content/markup inside the standard page markup.
	 * Use a new `content_callback()` method to override these defaults.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return mixed Returns the page content.
	 */
	protected function content() {

		$content = do_action( 'render_screen_tabs_' . $this->page_options['menu_slug'] );

		return apply_filters( 'scp_admin_page_content_' . $this->page_options['menu_slug'], $content );
	}

	/**
	 * Content callback function
	 *
	 * To override the default content with the add tab system
	 * it is recommended that the page output callback functions
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
	public function content_callback() {

		add_action( 'render_screen_tabs_' . $this->page_options['menu_slug'], [ $this, 'render_tabs' ] );

		// Native page wrap element/class.
		echo  '<div class="wrap">';

		// Print a heading using the menu title variable.
		echo  sprintf(
			'<h1>%s</h1>',
			__( $this->heading(), 'sitecore' )
		);

		// Print a paragraph with native description class using the description variable.
		echo  $this->description();

		wp_nonce_field( $this->page_options['menu_slug'] );

		if ( ! $this->is_subpage() ||
			( $this->is_subpage() && 'options-general.php' != $this->page_options['parent_slug'] )
		) {
			settings_errors();
		}

		echo $this->form_open();

		$this->content();

		echo $this->form_close();

		// End page wrap.
		echo '</div>';
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
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function screen_options() {
		// add_screen_option();
	}

	/**
	 * Save network settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function save_network_settings() {

		$settings = array_key_exists( 'capability', $this->page_options['settings'] );
		if ( ! is_multisite() && ! $this->page_options['settings'] ) {
			return;
		}

		if ( $this->is_subpage() ) {
			$redirect = $this->page_options['parent_slug'];
		} else {
			$redirect = 'admin.php';
		}

		check_admin_referer( $this->page_options['menu_slug'] );

		do_action( 'save_network_settings_' . $this->page_options['menu_slug'] );

		wp_safe_redirect(
			add_query_arg( [
				'page'    => $this->page_options['menu_slug'],
				'updated' => true
			],
			network_admin_url( $redirect ) )
		 );
		 die();
	}

	/**
	 * Enqueue page scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {}

	/**
	 * Print page styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_styles() {}

	/**
	 * Print page scripts
	 *
	 * Hooks into the page slug (suffix).
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_scripts() {
		?>
		<!-- Findme --><script><?php echo $this->page_options['menu_slug']; ?></script>
		<?php
	}

	/**
	 * ACF field groups
	 *
	 * Register field groups for this options page.
	 *
	 * The Plugin_ACF class at
	 * `includes/classes/vendor/class-plugin-acf.php`
	 * includes once all PHP files prefixed with `acf-`.
	 * This method is not necessary in child classes if
	 * field groups are added to that directory with
	 * that prefix.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_field_groups() {

		/**
		 * Include from another file or use the
		 * `acf_add_local_field_group` function
		 * here, as exported.
		 */
	}
}
