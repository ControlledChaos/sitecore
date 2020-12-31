<?php
/**
 * Users class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\Classes\Users;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Users {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// User roles & capabilities.
		new User_Roles_Caps;

		if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
			new User_Toolbar;
		}

		new User_Avatars;

		// Move the personal data menu items.
		add_action( 'admin_menu', [ $this, 'menus_personal_data' ] );

		/**
		 * Remove user admin color picker
		 *
		 * If `SCP_ALLOW_ADMIN_COLOR_PICKER` is set to false.
		 * This can be defined in the system config file.
		 */
		if ( defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) && false == SCP_ALLOW_ADMIN_COLOR_PICKER ) {
			remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
		}

		// Add the WP_Editor.
		add_action( 'show_user_profile', [ $this, 'visual_editor' ] );
		add_action( 'edit_user_profile', [ $this, 'visual_editor' ] );

		// Don't sanitize the data for display in a textarea.
		add_action( 'admin_init', [ $this, 'save_filters' ] );

		// Load required JS
		add_action( 'admin_enqueue_scripts', [ $this, 'load_javascript' ], 10, 1 );

		// Add content filters to the output of the description.
		add_filter( 'get_the_author_description', 'wptexturize' );
		add_filter( 'get_the_author_description', 'convert_chars' );
		add_filter( 'get_the_author_description', 'wpautop' );
	}

	/**
	 * Move the personal data
	 *
	 * Moves the personal data links to the Users entry.
	 *
	 * @since  1.0.0
	 * @access public
     * @global array $menu The admin menu array.
     * @global array $submenu The admin submenu array.
	 * @return void
	 */
	public function menus_personal_data() {

		global $menu, $submenu;

		// Remove personal data links as submenu items of Tools.
		if ( isset( $submenu['tools.php'] ) ) {

			// Look for menu items under Tools.
			foreach ( $submenu['tools.php'] as $key => $item ) {

				// Unset Export if it is found.
				if ( $item[2] === 'export-personal-data.php' ) {
					unset($submenu['tools.php'][$key] );
				}

				// Unset Erase if it is found.
				if ( $item[2] === 'erase-personal-data.php' ) {
					unset( $submenu['tools.php'][$key] );
				}
			}
		}

		// New Export Data submenu entry.
		$submenu['users.php'][25] = [
			__( 'Export Data', SCP_DOMAIN ),
			'export_others_personal_data',
			'export-personal-data.php'
		];

		// New Erase Data submenu entry.
		$submenu['users.php'][30] = [
			__( 'Erase Data', SCP_DOMAIN ),
			'erase_others_personal_data',
			'erase-personal-data.php'
		];
	}

	/**
	 *	Create Visual Editor
	 *
	 *	Add TinyMCE editor to replace the "Biographical Info" field in a user profile
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $user An object with details about the current logged in user.
	 * @return string Return the editor and container markup.
	 */
	public function visual_editor( $user ) {

		// Contributor level user or higher required
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		?>
		<table class="form-table">
			<tr>
				<th><label for="description"><?php _e( 'Biographical Info', 'hindsight' ); ?></label></th>
				<td>
					<?php
					$description = get_user_meta( $user->ID, 'description', true );
					wp_editor( $description, 'description' );
					?>
					<p class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'hindsight' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Editor script
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the script tag markup.
	 */
	public function load_javascript( $hook ) {

		// Contributor level user or higher required.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Load JavaScript only on the profile and user edit pages.
		if ( $hook == 'profile.php' || $hook == 'user-edit.php' ) {
			wp_enqueue_script(
				'visual-editor-biography',
				get_theme_file_uri( '/assets/js/user-bio.min.js' ),
				[ 'jquery' ],
				false,
				true
			);
		}
	}

	/**
	 * Save filters
	 *
	 * Removes textarea filters from description field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function save_filters() {

		// Contributor level user or higher required.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		remove_all_filters( 'pre_user_description' );
	}
}
