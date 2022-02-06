<?php
/**
 * Sample/starter class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Editor_Options {

	/**
	 * Settings
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object
	 */
	private static $settings;

	/**
	 * Supported post types
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private static $supported_post_types = [];

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run this class.
		add_action( 'init', [ $this, 'init_actions' ] );
	}

	/**
	 * Initial actions
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function init_actions() {

		global $wp_version;

		$block_editor = has_action( 'enqueue_block_assets' );
		$gutenberg    = function_exists( 'gutenberg_register_scripts_and_styles' );

		register_activation_hook( __FILE__, [ __CLASS__, 'activate' ] );

		$settings = self :: get_settings();

		if ( is_multisite() ) {
			add_action( 'wpmu_options', [ __CLASS__, 'network_settings' ] );
			add_action( 'update_wpmu_options', [ __CLASS__, 'save_network_settings' ] );
		}

		if ( ! $settings['hide-settings-ui'] ) {

			add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );

			if ( $settings['allow-users'] ) {

				// User settings.
				add_action( 'personal_options_update', [ __CLASS__, 'save_user_settings' ] );
				add_action( 'profile_personal_options', [ __CLASS__, 'user_settings' ] );
			}
		}

		// Always remove the "Try Gutenberg" dashboard widget.
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );

		if ( ! $block_editor && ! $gutenberg  ) {
			// return;
		}

		if ( $settings['allow-users'] ) {

			// Also used in Gutenberg.
			add_filter( 'use_block_editor_for_post', [ __CLASS__, 'choose_editor' ], 100, 2 );

			if ( $gutenberg ) {

				// Support older Gutenberg versions.
				add_filter( 'gutenberg_can_edit_post', [ __CLASS__, 'choose_editor' ], 100, 2 );

				if ( $settings['editor'] === 'tinymce' ) {
					self :: remove_block_hooks( 'some' );
				}
			}

			add_filter( 'get_edit_post_link', [ __CLASS__, 'get_edit_post_link' ] );
			add_filter( 'redirect_post_location', [ __CLASS__, 'redirect_location' ] );
			add_action( 'edit_form_top', [ __CLASS__, 'add_redirect_helper' ] );
			add_action( 'admin_head-edit.php', [ __CLASS__, 'add_edit_php_inline_style' ] );
			add_action( 'edit_form_top', [ __CLASS__, 'remember_tinymce_editor' ] );
			if ( version_compare( $wp_version, '5.8', '>=' ) ) {
				add_filter( 'block_editor_settings_all', [ __CLASS__, 'remember_block_editor' ], 10, 2 );
			} else {
				add_filter( 'block_editor_settings', [ __CLASS__, 'remember_block_editor' ], 10, 2 );
			}

			// Post state (edit.php).
			add_filter( 'display_post_states', [ __CLASS__, 'add_post_state' ], 10, 2 );

			// Row actions (edit.php).
			add_filter( 'page_row_actions', [ __CLASS__, 'add_edit_links' ], 15, 2 );
			add_filter( 'post_row_actions', [ __CLASS__, 'add_edit_links' ], 15, 2 );

			// Switch editors while editing a post.
			add_action( 'add_meta_boxes', [ __CLASS__, 'add_meta_box' ], 10, 2 );
			add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_scripts' ] );

		} else {

			if ( $settings['editor'] === 'tinymce' ) {

				/**
				 * Also used in Gutenberg.
				 * Consider disabling other Block Editor functionality.
				 */
				add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );

				if ( $gutenberg ) {

					// Support older Gutenberg versions.
					add_filter( 'gutenberg_can_edit_post_type', '__return_false', 100 );
					self :: remove_block_hooks();
				}

			} else {

				// `$settings['editor'] === 'block'`, nothing to do :).
				return;
			}
		}

		if ( $block_editor ) {

			// Move the Privacy Page notice back under the title.
			add_action( 'admin_init', [ __CLASS__, 'on_admin_init' ] );
		}

		if ( $gutenberg ) {

			// These are handled by this plugin. All are older, not used in 5.3+.
			remove_action( 'admin_init', 'gutenberg_add_edit_link_filters' );
			remove_action( 'admin_print_scripts-edit.php', 'gutenberg_replace_default_add_new_button' );
			remove_filter( 'redirect_post_location', 'gutenberg_redirect_to_classic_editor_when_saving_posts' );
			remove_filter( 'display_post_states', 'gutenberg_add_gutenberg_post_state' );
			remove_action( 'edit_form_top', 'gutenberg_remember_tinymce_editor_when_saving_posts' );
		}
	}

	/**
	 * Remove block hooks
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function remove_block_hooks( $remove = 'all' ) {

		remove_action( 'admin_menu', 'gutenberg_menu' );
		remove_action( 'admin_init', 'gutenberg_redirect_demo' );

		if ( $remove !== 'all' ) {
			return;
		}

		// Gutenberg 5.3+
		remove_action( 'wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
		remove_action( 'admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles' );
		remove_action( 'admin_notices', 'gutenberg_wordpress_version_notice' );
		remove_action( 'rest_api_init', 'gutenberg_register_rest_widget_updater_routes' );
		remove_action( 'admin_print_styles', 'gutenberg_block_editor_admin_print_styles' );
		remove_action( 'admin_print_scripts', 'gutenberg_block_editor_admin_print_scripts' );
		remove_action( 'admin_print_footer_scripts', 'gutenberg_block_editor_admin_print_footer_scripts' );
		remove_action( 'admin_footer', 'gutenberg_block_editor_admin_footer' );
		remove_action( 'admin_enqueue_scripts', 'gutenberg_widgets_init' );
		remove_action( 'admin_notices', 'gutenberg_build_files_notice' );
		remove_filter( 'load_script_translation_file', 'gutenberg_override_translation_file' );
		remove_filter( 'block_editor_settings', 'gutenberg_extend_block_editor_styles' );
		remove_filter( 'block_editor_settings_all', 'gutenberg_extend_block_editor_styles' );
		remove_filter( 'default_content', 'gutenberg_default_demo_content' );
		remove_filter( 'default_title', 'gutenberg_default_demo_title' );
		remove_filter( 'block_editor_settings', 'gutenberg_legacy_widget_settings' );
		remove_filter( 'block_editor_settings_all', 'gutenberg_legacy_widget_settings' );
		remove_filter( 'rest_request_after_callbacks', 'gutenberg_filter_oembed_result' );

		// Previously used, compat for older Gutenberg versions.
		remove_filter( 'wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers' );
		remove_filter( 'get_edit_post_link', 'gutenberg_revisions_link_to_editor' );
		remove_filter( 'wp_prepare_revision_for_js', 'gutenberg_revisions_restore' );
		remove_action( 'rest_api_init', 'gutenberg_register_rest_routes' );
		remove_action( 'rest_api_init', 'gutenberg_add_taxonomy_visibility_field' );
		remove_filter( 'registered_post_type', 'gutenberg_register_post_prepare_functions' );
		remove_action( 'do_meta_boxes', 'gutenberg_meta_box_save' );
		remove_action( 'submitpost_box', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'submitpage_box', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'edit_page_form', 'gutenberg_intercept_meta_box_render' );
		remove_action( 'edit_form_advanced', 'gutenberg_intercept_meta_box_render' );
		remove_filter( 'redirect_post_location', 'gutenberg_meta_box_save_redirect' );
		remove_filter( 'filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes' );
		remove_filter( 'body_class', 'gutenberg_add_responsive_body_class' );
		remove_filter( 'admin_url', 'gutenberg_modify_add_new_button_url' ); // old
		remove_action( 'admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks' );
		remove_filter( 'register_post_type_args', 'gutenberg_filter_post_type_labels' );

		// Keep
		// remove_filter( 'wp_kses_allowed_html', 'gutenberg_kses_allowedtags', 10, 2 ); // not needed in 5.0
		// remove_filter( 'bulk_actions-edit-wp_block', 'gutenberg_block_bulk_actions' );
		// remove_filter( 'wp_insert_post_data', 'gutenberg_remove_wpcom_markdown_support' );
		// remove_filter( 'the_content', 'do_blocks', 9 );
		// remove_action( 'init', 'gutenberg_register_post_types' );

		// Continue to manage wpautop for posts that were edited in the block editor.
		// remove_filter( 'wp_editor_settings', 'gutenberg_disable_editor_settings_wpautop' );
		// remove_filter( 'the_content', 'gutenberg_wpautop', 8 );

	}

	/**
	 * Get settings
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $refresh
	 * @return object
	 */
	private static function get_settings( $refresh = 'no' ) {

		/**
		 * Can be used to override the plugin's settings. Always hides the settings UI when used (as users cannot change the settings).
		 *
		 * Has to return an associative array with two keys.
		 * The defaults are:
		 *   'editor' => 'tinymce', // Accepted values: 'tinymce', 'block'.
		 *   'allow-users' => false,
		 *
		 * @param boolean To override the settings return an array with the above keys.
		 */
		$settings = apply_filters( 'default_editor_settings', false );

		if ( is_array( $settings ) ) {

			return [
				'editor'           => ( isset( $settings['editor'] ) && $settings['editor'] === 'block' ) ? 'block' : 'tinymce',
				'allow-users'      => ! empty( $settings['allow-users'] ),
				'hide-settings-ui' => true,
			];
		}

		if ( ! empty( self :: $settings ) && $refresh === 'no' ) {
			return self :: $settings;
		}

		if ( is_multisite() ) {

			$defaults = [
				'editor'      => get_network_option( null, 'editor-options-replace' ) === 'block' ? 'block' : 'tinymce',
				'allow-users' => false,
			];

			/**
			 * Filters the default network options.
			 *
			 * @param array $defaults The default options array. See `default_editor_settings` for supported keys and values.
			 */
			$defaults = apply_filters( 'default_editor_network_settings', $defaults );

			if ( get_network_option( null, 'tinymce-editor-allow-sites' ) !== 'allow' ) {

				// Per-site settings are disabled. Return default network options nad hide the settings UI.
				$defaults['hide-settings-ui'] = true;
				return $defaults;
			}

			// Override with the site options.
			$editor_option      = get_option( 'editor-options-replace' );
			$allow_users_option = get_option( 'editor-options-allow-users' );

			if ( $editor_option ) {
				$defaults['editor'] = $editor_option;
			}

			if ( $allow_users_option ) {
				$defaults['allow-users'] = ( $allow_users_option === 'allow' );
			}

			$editor      = ( isset( $defaults['editor'] ) && $defaults['editor'] === 'block' ) ? 'block' : 'tinymce';
			$allow_users = ! empty( $defaults['allow-users'] );

		} else {

			$allow_users = ( get_option( 'editor-options-allow-users' ) === 'allow' );
			$option      = get_option( 'editor-options-replace' );

			// Normalize old options.
			if ( $option === 'block' || $option === 'no-replace' ) {
				$editor = 'block';
			} else {
				// empty( $option ) || $option === 'tinymce' || $option === 'replace'.
				$editor = 'tinymce';
			}
		}

		// Override the defaults with the user options.
		if ( ( ! isset( $GLOBALS['pagenow'] ) || $GLOBALS['pagenow'] !== 'options-writing.php' ) && $allow_users ) {

			$user_options = get_user_option( 'default-editor-settings' );

			if ( $user_options === 'block' || $user_options === 'tinymce' ) {
				$editor = $user_options;
			}
		}

		self :: $settings = [
			'editor'           => $editor,
			'hide-settings-ui' => false,
			'allow-users'      => $allow_users,
		];

		return self :: $settings;
	}

	/**
	 * If the TinyMCE editor is used
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean Returns true if the TinyMCE editor is used.
	 */
	private static function is_tinymce( $post_id = 0 ) {

		if ( ! $post_id ) {
			$post_id = self :: get_edited_post_id();
		}

		if ( $post_id ) {
			$settings = self :: get_settings();

			if ( $settings['allow-users'] && ! isset( $_GET['default-editor__forget'] ) ) {
				$which = get_post_meta( $post_id, 'default-editor-remember', true );

				if ( $which ) {

					/**
					 * The editor choice will be "remembered" when the post is
					 * opened in either the tinymce or the block editor.
					 */
					if ( 'editor-options' === $which ) {
						return true;
					} elseif ( 'block-editor' === $which ) {
						return false;
					}
				}

				return ( ! self :: has_blocks( $post_id ) );
			}
		}

		if ( isset( $_GET['editor-options'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the edited post ID
	 *
	 * Retrieve early when loading the Edit Post screen.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return integer Returns the ID.
	 */
	private static function get_edited_post_id() {

		if (
			! empty( $_GET['post'] ) &&
			! empty( $_GET['action'] ) &&
			$_GET['action'] === 'edit' &&
			! empty( $GLOBALS['pagenow'] ) &&
			$GLOBALS['pagenow'] === 'post.php'
		) {
			// The post_ID.
			return (int) $_GET['post'];
		}

		return 0;
	}

	/**
	 * Register settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function register_settings() {

		// Add an option to Settings -> Writing.
		register_setting( 'writing', 'editor-options-replace', [
			'sanitize_callback' => [ __CLASS__, 'validate_option_editor' ],
		] );

		register_setting( 'writing', 'editor-options-allow-users', [
			'sanitize_callback' => [ __CLASS__, 'validate_option_allow_users' ],
		] );

		$allowed_options = [
			'writing' => [
				'editor-options-replace',
				'editor-options-allow-users'
			],
		];

		if ( function_exists( 'add_allowed_options' ) ) {
			add_allowed_options( $allowed_options );
		} else {
			add_option_whitelist( $allowed_options );
		}

		$heading_default = __( 'Default editor for all users', 'sitecore' );
		$heading_allow   = __( 'Allow users to switch editors', 'sitecore' );

		add_settings_field( 'editor-options-default', $heading_default, [ __CLASS__, 'editor_settings_default' ], 'writing' );
		add_settings_field( 'editor-options-choose', $heading_allow, [ __CLASS__, 'editor_settings_allow' ], 'writing' );
	}

	/**
	 * Save user settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function save_user_settings( $user_id ) {

		if (
			isset( $_POST['editor-options-user-settings'] ) &&
			isset( $_POST['editor-options-replace'] ) &&
			wp_verify_nonce( $_POST['editor-options-user-settings'], 'allow-user-settings' )
		) {
			$user_id = (int) $user_id;

			if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_user', $user_id ) ) {
				return;
			}

			$editor = self :: validate_option_editor( $_POST['editor-options-replace'] );
			update_user_option( $user_id, 'default-editor-settings', $editor );
		}
	}

	/**
	 * Validate editor option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the value of the option.
	 */
	public static function validate_option_editor( $value ) {

		if ( $value === 'block' ) {
			return 'block';
		}

		return 'tinymce';
	}

	/**
	 * Validate allow choisce option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the value of the option.
	 */
	public static function validate_option_allow_users( $value ) {

		if ( $value === 'allow' ) {
			return 'allow';
		}

		return 'disallow';
	}

	/**
	 * Form fields for the default editor option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function editor_settings_default() {
		include_once SCP_PATH . 'views/backend/forms/partials/settings-writing-editor-default.php';
	}

	/**
	 * Form fields for allow editor choice option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function editor_settings_allow() {
		include_once SCP_PATH . 'views/backend/forms/partials/settings-writing-editor-allow.php';
	}

	/**
	 * Form fields for user default editor option
	 *
	 * Shown on the Profile page when allowed by admin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function user_settings() {

		global $user_can_edit;

		$settings = self :: get_settings( 'update' );

		if (
			! defined( 'IS_PROFILE_PAGE' ) ||
			! IS_PROFILE_PAGE ||
			! $user_can_edit ||
			! $settings['allow-users']
		) {
			return;
		}

		include_once SCP_PATH . 'views/backend/forms/partials/settings-user-editor-default.php';
	}

	/**
	 * Form fields for network default editor option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function network_settings() {
		include_once SCP_PATH . 'views/backend/forms/partials/settings-network-editor-default.php';
	}

	/**
	 * Save network settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function save_network_settings() {

		if (
			isset( $_POST['tinymce-editor-network-settings'] ) &&
			current_user_can( 'manage_network_options' ) &&
			wp_verify_nonce( $_POST['tinymce-editor-network-settings'], 'allow-site-admin-settings' )
		) {
			if ( isset( $_POST['editor-options-replace'] ) && $_POST['editor-options-replace'] === 'block' ) {
				update_network_option( null, 'editor-options-replace', 'block' );
			} else {
				update_network_option( null, 'editor-options-replace', 'tinymce' );
			}
			if ( isset( $_POST['tinymce-editor-allow-sites'] ) && $_POST['tinymce-editor-allow-sites'] === 'allow' ) {
				update_network_option( null, 'tinymce-editor-allow-sites', 'allow' );
			} else {
				update_network_option( null, 'tinymce-editor-allow-sites', 'disallow' );
			}
		}
	}

	/**
	 * Redirect helper
	 *
	 * Add a hidden field in edit-form-advanced.php
	 * to help redirect back to the rich text editor on saving.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function add_redirect_helper() {

		?>
		<input type="hidden" name="editor-options" value="" />
		<?php
	}

	/**
	 * Remember TinyMCE editor
	 *
	 * Remembers when the rich text editor was used to edit a post.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function remember_tinymce_editor( $post ) {

		$post_type = get_post_type( $post );

		if ( $post_type && post_type_supports( $post_type, 'editor' ) ) {
			self :: remember( $post->ID, 'editor-options' );
		}
	}

	/**
	 * Remember block editor
	 *
	 * Remembers when the block editor was used to edit a post.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function remember_block_editor( $editor_settings, $context ) {

		if ( is_a( $context, 'WP_Post' ) ) {
			$post = $context;
		} elseif ( ! empty( $context->post ) ) {
			$post = $context->post;
		} else {
			return $editor_settings;
		}

		$post_type = get_post_type( $post );

		if ( $post_type && self::can_edit_post_type( $post_type ) ) {
			self::remember( $post->ID, 'block-editor' );
		}

		return $editor_settings;
	}

	/**
	 * Remember editor option
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	private static function remember( $post_id, $editor ) {

		if ( get_post_meta( $post_id, 'default-editor-remember', true ) !== $editor ) {
			update_post_meta( $post_id, 'default-editor-remember', $editor );
		}
	}

	/**
	 * Choose which editor to use for a post.
	 *
	 * Passes through `$which_editor` for block editor (it's sets to `true` but may be changed by another plugin).
	 *
	 * @uses `use_block_editor_for_post` filter.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  boolean $use_block_editor True for block editor, false for rich text editor.
	 * @param  WP_Post $post The post being edited.
	 * @return boolean True for block editor, false for rich text editor.
	 */
	public static function choose_editor( $use_block_editor, $post ) {

		$settings = self :: get_settings();
		$editors  = self :: get_enabled_editors_for_post( $post );

		// If no editor is supported, pass through `$use_block_editor`.
		if ( ! $editors['block_editor'] && ! $editors['editor_options'] ) {
			return $use_block_editor;
		}

		/**
		 * Open the default editor when no $post and for "Add New" links,
		 * or the alternate editor when the user is switching editors.
		 */
		if ( empty( $post->ID ) || $post->post_status === 'auto-draft' ) {

			if (
				// Add New.
				( $settings['editor'] === 'tinymce' && ! isset( $_GET['default-editor__forget'] ) ) ||

				// Switch to rich text editor when no draft post.
				( isset( $_GET['editor-options'] ) && isset( $_GET['default-editor__forget'] ) )
			) {
				$use_block_editor = false;
			}

		} elseif ( self :: is_tinymce( $post->ID ) ) {
			$use_block_editor = false;
		}

		// Enforce the editor if set by plugins.
		if ( $use_block_editor && ! $editors['block_editor'] ) {
			$use_block_editor = false;

		} elseif ( ! $use_block_editor && ! $editors['editor_options'] && $editors['block_editor'] ) {
			$use_block_editor = true;
		}

		return $use_block_editor;
	}

	/**
	 * Redirect location
	 *
	 * Keeps the `editor-options` query argument through redirects when saving posts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the query argument.
	 */
	public static function redirect_location( $location ) {

		if (
			isset( $_REQUEST['editor-options'] ) ||
			( isset( $_POST['_wp_http_referer'] ) && strpos( $_POST['_wp_http_referer'], '&editor-options' ) !== false )
		) {
			$location = add_query_arg( 'editor-options', '', $location );
		}

		return $location;
	}

	/**
	 * Get edit post link
	 *
	 * Keeps the `editor-options` query argument when looking at revisions.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the query argument.
	 */
	public static function get_edit_post_link( $url ) {

		$settings = self :: get_settings();

		if ( isset( $_REQUEST['editor-options'] ) || $settings['editor'] === 'tinymce' ) {
			$url = add_query_arg( 'editor-options', '', $url );
		}

		return $url;
	}

	/**
	 * Add meta box
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $post_type
	 * @param  object $post
	 * @return void
	 */
	public static function add_meta_box( $post_type, $post ) {

		$editors = self :: get_enabled_editors_for_post( $post );

		if ( ! $editors['block_editor'] || ! $editors['editor_options'] ) {

			// Editors cannot be switched.
			return;
		}

		$id       = 'editor-options-switch-editor';
		$title    = __( 'Editor', 'sitecore' );
		$callback = [ __CLASS__, 'do_meta_box' ];
		$args     = [
			'__back_compat_meta_box' => true,
	    ];

		add_meta_box( $id, $title, $callback, null, 'side', 'default', $args );
	}

	/**
	 * Do mata box
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $post
	 * @return void
	 */
	public static function do_meta_box( $post ) {

		$edit_url = get_edit_post_link( $post->ID, 'raw' );

		// Switching to block editor.
		$edit_url = remove_query_arg( 'editor-options', $edit_url );

		// Forget the previous value when going to a specific editor.
		$edit_url = add_query_arg( 'default-editor__forget', '', $edit_url );

		?>
		<p style="margin: 1em 0;">
			<a href="<?php echo esc_url( $edit_url ); ?>"><?php _e( 'Switch to block editor ', 'sitecore' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function enqueue_block_editor_scripts() {

		// get_enabled_editors_for_post() needs a WP_Post or post_ID.
		if ( empty( $GLOBALS['post'] ) ) {
			return;
		}

		$editors = self :: get_enabled_editors_for_post( $GLOBALS['post'] );

		if ( ! $editors['editor_options'] ) {
			// Editor cannot be switched.
			return;
		}

		// Script suffix.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '';
		} else {
			$suffix = '.min';
		}

		wp_enqueue_script(
			'editor-options',
			SCP_URL . 'assets/js/editor-options' . $suffix . '.js',
			[ 'wp-element', 'wp-components', 'lodash' ],
			'1.4',
			true
		);

		wp_localize_script(
			'editor-options',
			'editorOptionsL10n',
			[ 'linkText' => __( 'Switch to rich text editor ', 'sitecore' ) ]
		);
	}

	/**
	 * Can edit post type
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  object $post_type
	 * @return boolean Returns true if the user can edit post type.
	 */
	private static function can_edit_post_type( $post_type ) {

		$can_edit = false;

		if ( function_exists( 'gutenberg_can_edit_post_type' ) ) {
			$can_edit = gutenberg_can_edit_post_type( $post_type );
		} elseif ( function_exists( 'use_block_editor_for_post_type' ) ) {
			$can_edit = use_block_editor_for_post_type( $post_type );
		}

		return $can_edit;
	}

	/**
	 * Enabled for post type
	 *
	 * Checks which editors are enabled for the post type.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  string $post_type The post type.
	 * @return array Associative array of the editors and whether they are enabled for the post type.
	 */
	private static function get_enabled_editors_for_post_type( $post_type ) {

		if ( isset( self :: $supported_post_types[ $post_type ] ) ) {
			return self :: $supported_post_types[ $post_type ];
		}

		$editor_options = post_type_supports( $post_type, 'editor' );
		$block_editor = self :: can_edit_post_type( $post_type );

		$editors = [
			'editor_options' => $editor_options,
			'block_editor'   => $block_editor,
		];

		/**
		 * Filters the editors that are enabled for the post type.
		 *
		 * @param array $editors Associative array of the editors and whether they are enabled for the post type.
		 * @param string $post_type The post type.
		 */
		$editors = apply_filters( 'editor_options_enabled_editors_for_post_type', $editors, $post_type );
		self :: $supported_post_types[ $post_type ] = $editors;

		return $editors;
	}

	/**
	 * Enabled for post
	 *
	 * Checks which editors are enabled for the post.
	 *
	 * @since  1.0.0
	 * @access private
	 * @param  WP_Post $post The post object.
	 * @return array Associative array of the editors and whether they are enabled for the post.
	 */
	private static function get_enabled_editors_for_post( $post ) {

		$post_type = get_post_type( $post );

		if ( ! $post_type ) {
			return [
				'editor_options' => false,
				'block_editor'   => false,
			];
		}

		$editors = self :: get_enabled_editors_for_post_type( $post_type );

		/**
		 * Filters the editors that are enabled for the post.
		 *
		 * @param array $editors Associative array of the editors and whether they are enabled for the post.
		 * @param WP_Post $post  The post object.
		 */
		return apply_filters( 'editor_options_enabled_editors_for_post', $editors, $post );
	}

	/**
	 * Adds links to the post/page screens to edit any post or page in
	 * the rich text editor or block editor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $actions Post actions.
	 * @param  WP_Post $post  Edited post.
	 * @return array Updated post actions.
	 */
	public static function add_edit_links( $actions, $post ) {

		// This is in Gutenberg, don't duplicate it.
		if ( array_key_exists( 'tinymce', $actions ) ) {
			unset( $actions['tinymce'] );
		}

		if ( ! array_key_exists( 'edit', $actions ) ) {
			return $actions;
		}

		$edit_url = get_edit_post_link( $post->ID, 'raw' );

		if ( ! $edit_url ) {
			return $actions;
		}

		$editors = self :: get_enabled_editors_for_post( $post );

		// Do not show the links if only one editor is available.
		if ( ! $editors['editor_options'] || ! $editors['block_editor'] ) {
			return $actions;
		}

		// Forget the previous value when going to a specific editor.
		$edit_url = add_query_arg( 'default-editor__forget', '', $edit_url );

		// Build the edit actions. See also: WP_Posts_List_Table :: handle_row_actions().
		$title = _draft_or_post_title( $post->ID );

		// Link to the block editor.
		$url        = remove_query_arg( 'editor-options', $edit_url );
		$text       = _x( 'Edit Blocks', 'Editor Name', 'sitecore' );
		$label      = sprintf( __( 'Edit &#8220;%s&#8221; in the block editor', 'sitecore' ), $title );
		$edit_block = sprintf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $label ), $text );

		// Link to the rich text editor.
		$url          = add_query_arg( 'editor-options', '', $edit_url );
		$text         = _x( 'Edit Rich Text', 'Editor Name', 'sitecore' );
		$label        = sprintf( __( 'Edit &#8220;%s&#8221; in the rich text editor', 'sitecore' ), $title );
		$edit_rich    = sprintf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $url ), esc_attr( $label ), $text );
		$edit_actions = [
			'editor-options-tinymce' => $edit_rich,
			'editor-options-block'   => $edit_block
		];

		// Insert the new Edit actions instead of the Edit action.
		$edit_offset = array_search( 'edit', array_keys( $actions ), true );
		array_splice( $actions, $edit_offset, 1, $edit_actions );

		return $actions;
	}

	/**
	 * Add post state
	 *
	 * Show the editor that will be used in a "post state" in the Posts list table.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public static function add_post_state( $post_states, $post ) {

		if ( get_post_status( $post ) === 'trash' ) {
			return $post_states;
		}

		$editors = self :: get_enabled_editors_for_post( $post );

		if ( ! $editors['editor_options'] && ! $editors['block_editor'] ) {
			return $post_states;

		} elseif ( $editors['editor_options'] && ! $editors['block_editor'] ) {

			// Forced to rich text editor.
			$state = '<span class="editor-options-forced-state">' . _x( 'rich text editor', 'Editor Name', 'sitecore' ) . '</span>';

		} elseif ( ! $editors['editor_options'] && $editors['block_editor'] ) {

			// Forced to block editor.
			$state = '<span class="editor-options-forced-state">' . _x( 'block editor', 'Editor Name', 'sitecore' ) . '</span>';

		} else {

			$last_editor = get_post_meta( $post->ID, 'default-editor-remember', true );

			if ( $last_editor ) {
				$is_tinymce = ( $last_editor === 'editor-options' );
			} elseif ( ! empty( $post->post_content ) ) {
				$is_tinymce = ! self :: has_blocks( $post->post_content );
			} else {
				$settings = self :: get_settings();
				$is_tinymce = ( $settings['editor'] === 'tinymce' );
			}

			$state = $is_tinymce ? _x( 'Rich Text', 'Editor Name', 'sitecore' ) : _x( 'Blocks', 'Editor Name', 'sitecore' );
		}

		// Fix PHP 7+ warnings if another plugin returns unexpected type.
		$post_states = (array) $post_states;
		$post_states['editor-options'] = $state;

		return $post_states;
	}

	/**
	 * Post list inline styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function add_edit_php_inline_style() {

		?>
		<style>
		.editor-options-forced-state {
			font-style: italic;
			font-weight: 400;
			color: inherit;
			font-size: small;
		}
		</style>
		<?php
	}

	/**
	 * On admin administration
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function on_admin_init() {

		global $pagenow;

		if ( $pagenow !== 'post.php' ) {
			return;
		}

		$settings = self :: get_settings();
		$post_id  = self :: get_edited_post_id();

		if ( $post_id && ( $settings['editor'] === 'tinymce' || self :: is_tinymce( $post_id ) ) ) {

			// Move the Privacy Policy help notice back under the title field.
			remove_action( 'admin_notices', [ 'WP_Privacy_Policy_Content', 'notice' ] );
			add_action( 'edit_form_after_title', [ 'WP_Privacy_Policy_Content', 'notice' ] );
		}
	}

	/**
	 * Has blocks
	 *
	 * Support for ClassicPress, WP < 5.0, and antibrand.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return boolean Returns true if blocks are available.
	 */
	private static function has_blocks( $post = null ) {

		if ( ! is_string( $post ) ) {
			$wp_post = get_post( $post );

			if ( $wp_post instanceof WP_Post ) {
				$post = $wp_post->post_content;
			}
		}

		return false !== strpos( (string) $post, '<!-- wp:' );
	}

	/**
	 * Plugin activation
	 *
	 * Set defaults on activation.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function activate() {

		register_uninstall_hook( __FILE__, [ __CLASS__, 'uninstall' ] );

		if ( is_multisite() ) {
			add_network_option( null, 'editor-options-replace', 'tinymce' );
			add_network_option( null, 'tinymce-editor-allow-sites', 'disallow' );
		}

		add_option( 'editor-options-replace', 'tinymce' );
		add_option( 'editor-options-allow-users', 'disallow' );
	}

	/**
	 * Plugin uninstall
	 *
	 * Delete the options on uninstall.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public static function uninstall() {

		if ( is_multisite() ) {
			delete_network_option( null, 'editor-options-replace' );
			delete_network_option( null, 'tinymce-editor-allow-sites' );
		}

		delete_option( 'editor-options-replace' );
		delete_option( 'editor-options-allow-users' );
	}
}
