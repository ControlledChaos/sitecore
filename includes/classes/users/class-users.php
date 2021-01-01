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
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Users extends Classes\Base {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run the parent constructor method.
		parent :: __construct();

		// Access current admin page.
		global $pagenow;

		// User roles & capabilities.
		new User_Roles_Caps;

		// User toolbar if the user is logged in.
		if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
			new User_Toolbar;
		}

		// Local user avatars.
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

		// Add rich text profile editor.
		add_action( 'show_user_profile', [ $this, 'profile_editor' ], 9 );
		add_action( 'edit_user_profile', [ $this, 'profile_editor' ], 9 );

		// Don't sanitize the data for display in a textarea.
		add_action( 'admin_init', [ $this, 'editor_filters' ] );

		// Add content filters to the output of the profile editor.
		add_filter( 'get_the_author_description', 'wptexturize' );
		add_filter( 'get_the_author_description', 'convert_chars' );
		add_filter( 'get_the_author_description', 'wpautop' );
	}

	/**
	 * Print styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns one or more style blocks.
	 */
	public function admin_print_styles() {

		// Access current admin page.
		global $pagenow;

		// Print styles only on the profile and user edit pages.
		if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) :

		?>
		<style>
		#profile-page > form,
		#profile-page > form > div > div {
			display: flex;
			flex-direction: column;
		}

		#wp-description-wrap {
			max-width: 1024px;
		}

		#profile-page > form h2:first-of-type,
		#profile-page > form table:first-of-type {
			order: 99;
		}

		.submit {
			order: 100;
		}

		#profile-page > form table:nth-of-type(2),
		#xprofile-page > form h2:nth-of-type(4),
		#xprofile-page > form table:nth-of-type(4) {
			display: none;
		}
		</style>
		<?php
		endif; // If profile.php or user-edit.php.
	}

	/**
	 * Enqueue scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts() {

		// Script suffix.
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '';
		} else {
			$suffix = '.min';
		}

		// Access current admin page.
		global $pagenow;

		// Enqueue only on the profile and user edit pages.
		if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) {
			wp_enqueue_script(
				'visual-editor-biography',
				SCP_URL . 'assets/js/user-bio' . $suffix . '.js',
				[ 'jquery' ],
				false,
				true
			);
		}
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
	 *	Profile rich text editor
	 *
	 *	Add TinyMCE editor to replace the "Biographical Info" field in a user profile.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $user An object with details about the current logged in user.
	 * @return string Return the editor and container markup.
	 */
	public function profile_editor( $user ) {

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="description"><?php _e( 'Biographical Info', SCP_DOMAIN ); ?></label></th>
					<td>
						<?php
						$description = get_user_meta( $user->ID, 'description', true );
						wp_editor(
							$description,
							'description',
							[
								'default_editor'   => 'tinymce',
								'quicktags'        => false,
								'media_buttons'    => true,
								'drag_drop_upload' => true,
								'tinymce'          => [
									'toolbar1' => 'bold,italic,underline,bullist,numlist,blockquote,hr,link,unlink,spellchecker,wp_fullscreen,wp_adv ',
									'toolbar2' => 'formatselect,forecolor,backcolor,pastetext,removeformat,charmap,undo,redo'
								],
								'gecko_spellcheck' => true
							]
						);
						?>
						<p class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', SCP_DOMAIN ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Editor filters
	 *
	 * Removes textarea filters from description field.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function editor_filters() {
		remove_all_filters( 'pre_user_description' );
	}
}
