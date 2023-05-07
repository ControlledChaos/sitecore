<?php
/**
 * Disable user toolbar
 *
 * Disable the user toolbar on the frontend of sites for
 * specific roles and capabilities based on inclusion
 * and exclusion settings.
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Disable_Toolbar ;

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

	add_action( 'init', $ns( 'init' ) );

	if ( is_admin() ) {
		add_action( 'admin_init', $ns( 'admin_init' ) );

		if ( enable_site_admin() ) {
			add_action( 'admin_menu', $ns( 'admin_menu' ) );
		}

		if ( is_multisite() ) {
			add_action( 'network_admin_menu', $ns( 'network_admin_menu' ) );
			add_action( 'network_admin_edit_admin_bar_disabler', $ns( 'network_settings_save' ) );
		}
	}
}

/**
 * Init
 *
 * @since  1.0.0
 * @access public
 * @return boolean
 */
function init() {
	return disable_by_settings();
}

/**
 * Admin init
 *
 * @since  1.0.0
 * @access public
 */
function admin_init() {

	register_setting( 'admin-bar-disabler-settings-group', 'admin_bar_disabler_disable_all' );
	register_setting( 'admin-bar-disabler-settings-group', 'admin_bar_disabler_whitelist_roles' );
	register_setting( 'admin-bar-disabler-settings-group', 'admin_bar_disabler_whitelist_caps' );
	register_setting( 'admin-bar-disabler-settings-group', 'admin_bar_disabler_blacklist_roles' );
	register_setting( 'admin-bar-disabler-settings-group', 'admin_bar_disabler_blacklist_caps' );
}

/**
 * Get plugin settings
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function get_settings( $inherit = false ) {

	$settings = [
		'disable_all'     => (boolean) get_option( 'admin_bar_disabler_disable_all', 0 ),
		'whitelist_roles' => (array) get_option( 'admin_bar_disabler_whitelist_roles', [] ),
		'whitelist_caps'  => get_option( 'admin_bar_disabler_whitelist_caps', '' ),
		'blacklist_roles' => (array) get_option( 'admin_bar_disabler_blacklist_roles', [] ),
		'blacklist_caps'  => get_option( 'admin_bar_disabler_blacklist_caps', '' ),
	];

	$settings['whitelist_roles'] = array_map( 'trim', array_unique( array_filter( $settings['whitelist_roles'] ) ) );

	$settings['whitelist_caps'] = explode( ',', $settings['whitelist_caps'] );
	$settings['whitelist_caps'] = array_map( 'trim', array_unique( array_filter( $settings['whitelist_caps'] ) ) );

	$settings['blacklist_roles'] = array_map( 'trim', array_unique( array_filter( $settings['blacklist_roles'] ) ) );

	$settings['blacklist_caps'] = explode( ',', $settings['blacklist_caps'] );
	$settings['blacklist_caps'] = array_map( 'trim', array_unique( array_filter( $settings['blacklist_caps'] ) ) );

	// Inherit settings from network settings
	if ( $inherit && is_multisite() ) {
		$site_settings = get_site_settings();

		foreach ( $site_settings as $setting => $value ) {
			if ( ! isset( $settings[ $setting ] ) || empty( $settings[ $setting ] ) ) {
				$settings[ $setting ] = $value;
			}
		}
	}
	return $settings;
}

/**
 * Get plugin settings
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function get_site_settings() {

	$settings = [
		'disable_all'     => (boolean) get_site_option( 'admin_bar_disabler_disable_all', 0 ),
		'whitelist_roles' => (array) get_site_option( 'admin_bar_disabler_whitelist_roles', [] ),
		'whitelist_caps'  => get_site_option( 'admin_bar_disabler_whitelist_caps', '' ),
		'blacklist_roles' => (array) get_site_option( 'admin_bar_disabler_blacklist_roles', [] ),
		'blacklist_caps'  => get_site_option( 'admin_bar_disabler_blacklist_caps', '' ),
	];

	$settings['whitelist_roles'] = array_map( 'trim', array_unique( array_filter( $settings['whitelist_roles'] ) ) );

	$settings['whitelist_caps'] = explode( ',', $settings['whitelist_caps'] );
	$settings['whitelist_caps'] = array_map( 'trim', array_unique( array_filter( $settings['whitelist_caps'] ) ) );

	$settings['blacklist_roles'] = array_map( 'trim', array_unique( array_filter( $settings['blacklist_roles'] ) ) );

	$settings['blacklist_caps'] = explode( ',', $settings['blacklist_caps'] );
	$settings['blacklist_caps'] = array_map( 'trim', array_unique( array_filter( $settings['blacklist_caps'] ) ) );

	return $settings;
}

/**
 * Disable by settings
 *
 * @since  1.0.0
 * @access public
 * @return boolean
 */
function disable_by_settings() {

	$settings = get_settings( true );

	if ( $settings['disable_all'] ) {
		return disable();
	}

	$inclusion_list_roles = $settings['whitelist_roles'];

	$user = new \WP_User( get_current_user_id() );

	if ( ! empty( $inclusion_list_roles ) ) {
		if ( ! is_array( $inclusion_list_roles ) ) {
			$inclusion_list_roles = [ $inclusion_list_roles ];
		}

		foreach ( $inclusion_list_roles as $role ) {
			if ( in_array( $role, $user->roles, true ) ) {
				return false;
			}
		}
		return disable();
	}

	$inclusion_list_caps = $settings['whitelist_caps'];

	if ( ! empty( $inclusion_list_caps ) ) {
		foreach ( $inclusion_list_caps as $cap ) {
			if ( $user->has_cap( $cap ) ) {
				return false;
			}
		}
		return disable();
	}

	$exclusion_list_roles = $settings['blacklist_roles'];

	if ( ! empty( $exclusion_list_roles ) ) {
		if ( ! is_array( $exclusion_list_roles ) ) {
			$exclusion_list_roles = [ $exclusion_list_roles ];
		}

		foreach ( $exclusion_list_roles as $role ) {
			if ( in_array( $role, $user->roles, true ) ) {
				return disable();
			}
		}
	}

	$exclusion_list_caps = $settings['blacklist_caps'];

	if ( ! empty( $exclusion_list_caps ) ) {
		foreach ( $exclusion_list_caps as $cap ) {
			if ( $user->has_cap( $cap ) ) {
				return disable();
			}
		}
	}
	return false;
}

/**
 * Disable site admin pages
 *
 * @since  1.0.0
 * @access public
 * @return boolean
 */
function enable_site_admin() {

	if ( ! is_multisite() ) {
		return true;
	}

	$network = get_network_option( get_current_network_id(), 'admin_bar_disabler_disable_all', false );

	if ( $network ) {
		return false;
	}
	return true;
}

/**
 * Disable admin bar
 *
 * @since  1.0.0
 * @access public
 * @return boolean
 */
function disable() {

	if ( ! is_admin() ) {
		add_filter( 'show_admin_bar', '__return_false', 999 );
	} else {
		// WP 3.x support.
		remove_action( 'personal_options', '_admin_bar_preferences' );

		// Disable option on user edit screen.
		add_action( 'admin_print_styles-user-edit.php', __NAMESPACE__ . '\disable_personal_option' );

		// Disable option on profile screen.
		add_action( 'admin_print_styles-profile.php', __NAMESPACE__ . '\disable_personal_option' );
	}
	return true;
}

/**
 * Disable personal option row for toolbar preferences via inline CSS
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function disable_personal_option() {

	echo '<style type="text/css">
			.show-admin-bar {
				display: none;
			}
		</style>';
}

/**
 * Parent menu item
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function parent_page() {
	return apply_filters( 'scp_disable_toolbar_parent_page', 'users.php' );
}

/**
 * Add menu item
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function admin_menu() {

	add_submenu_page(
		parent_page(),
		__( 'Disable User Toolbar', 'sitecore' ),
		__( 'Disable Toolbar', 'sitecore' ),
		'manage_options',
		'admin_bar_disabler',
		__NAMESPACE__ . '\settings_page',
		60
	);
}

/**
 * Add network menu item
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function network_admin_menu() {

	add_submenu_page(
		parent_page(),
		__( 'Disable User Toolbar', 'sitecore' ),
		__( 'Disable Toolbar', 'sitecore' ),
		'manage_network_options',
		'admin_bar_disabler',
		__NAMESPACE__ . '\settings_page'
	);
}

/**
 * Save network settings
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function network_settings_save() {

	check_admin_referer( 'admin_bar_disabler' );

	$settings = get_site_settings();

	foreach ( $settings as $field => $value ) {

		if (
			isset( $_POST[ 'admin_bar_disabler_' . $field ] ) &&
			! empty( $_POST[ 'admin_bar_disabler_' . $field ] )
		) {
			update_site_option( 'admin_bar_disabler_' . $field, $_POST[ 'admin_bar_disabler_' . $field ] );
		} else {
			delete_site_option( 'admin_bar_disabler_' . $field );
		}
	}

	wp_safe_redirect(
		add_query_arg(
			[
				'page' => 'admin_bar_disabler',
				'settings-updated' => true
			],
			parent_page()
		)
	 );
	die();
}

/**
 * Admin settings page
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function settings_page() {

	$settings    = get_settings();
	$network     = is_multisite() && is_network_admin();
	$action      = 'options.php';
	$all_message = __( 'Disable the frontend toolbar for all users.', 'sitecore' );

	if ( $network ) {
		$settings    = get_site_settings();
		$action      = 'edit.php?action=admin_bar_disabler';
		$all_message = __( 'Disable the frontend toolbar for all users of all sites.', 'sitecore' );
	}

	global $wp_roles;

	if ( ! isset( $wp_roles ) ) {
		$wp_roles = new \WP_Roles();
	}

	$roles = $wp_roles->get_names();

	if ( $network && isset( $_GET['settings-updated'] ) ) {
		?>
		<div id="message" class="notice notice-success is-dismissible"><p><strong><?php _e( 'Settings saved.' ); ?></strong></p></div>
		<?php
	}

	?>
	<style>
	.disable-toolbar select[multiple] {
		overflow-y: auto;
	}
	.disable-toolbar optgroup {
		font-style: normal;
		font-weight: normal;
	}
	textarea {
		resize: none;
	}
	</style>
	<div class="wrap disable-toolbar">

		<h2><?php _e( 'Disable User Toolbar', 'sitecore' ); ?></h2>

		<p class="description"><?php _e( 'This plugin disables the user toolbar from the frontend only. It does not affect administration screens.', 'sitecore' ); ?></p>

		<form method="post" action="<?php echo esc_attr( $action ); ?>">
			<?php
			if ( $network ) {
				wp_nonce_field( 'admin_bar_disabler' );
			} else {
				settings_fields( 'admin-bar-disabler-settings-group' );
				do_settings_sections( 'admin-bar-disabler-settings-group' );
			}
			?>
			<?php if ( $network ) : ?>
			<hr />
			<p><?php _e( 'These settings apply to all sites of the network and will override any single site settings.', 'sitecore' ); ?></p>
			<?php endif; ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="admin_bar_disabler_disable_all">
							<?php _e( 'Disable for All', 'sitecore' ); ?>
						</label>
					</th>
					<td>
						<input type="checkbox" name="admin_bar_disabler_disable_all" id="admin_bar_disabler_disable_all" value="1"<?php checked( $settings['disable_all'] ); ?> /> <?php echo $all_message; ?>

						<?php if ( $network ) : ?>
						<p class="description"><?php _e( 'This also disables the toolbar settings page in single site admin.', 'sitecore' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_bar_disabler_whitelist_roles">
							<?php _e( 'Roles Inclusion', 'sitecore' ); ?>
						</label>
					</th>
					<td>
						<p><?php _e( 'ONLY show the user toolbar for users with these role(s).', 'sitecore' ); ?></p>
						<p>
							<select name="admin_bar_disabler_whitelist_roles[]" id="admin_bar_disabler_whitelist_roles" size="9" multiple>
								<optgroup label="<?php _e( 'Default', 'sitecore' ); ?>">
									<option value=""><?php _e( 'Reset', 'sitecore' ); ?></option>
								</optgroup>
								<optgroup label="<?php _e( 'User Roles', 'sitecore' ); ?>">
								<?php
								$inclusion_list_roles = $settings['whitelist_roles'];

								foreach ( $roles as $role => $name ) {
									?>
									<option value="<?php echo esc_attr( $role ); ?>"<?php selected( in_array( $role, $inclusion_list_roles, true ) ); ?>><?php echo esc_html( $name ); ?></option>
									<?php
								}
								?>
								</optgroup>
							</select>
						</p>
						<p class="description"><?php _e( 'CTRL/CMD + Click for multiple selections.', 'sitecore' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_bar_disabler_whitelist_caps">
							<?php _e( 'Capabilities Inclusion', 'sitecore' ); ?>
						</label>
					</th>
					<td>
						<?php
						$inclusion_list_caps = implode( ',', $settings['whitelist_caps'] );
						?>
						<p><?php _e( 'ONLY show the user toolbar for users with these capabilities', 'sitecore' ); ?></p>
						<textarea rows="3" cols="50" name="admin_bar_disabler_whitelist_caps" id="admin_bar_disabler_whitelist_caps"><?php echo wp_strip_all_tags( $inclusion_list_caps ); ?></textarea>
						<p class="description"><?php _e( 'Add capabilities as a comma-separated list.', 'sitecore' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_bar_disabler_blacklist_roles">
							<?php _e( 'Roles Exclusion', 'sitecore' ); ?>
						</label>
					</th>
					<td>
						<p><?php _e( 'DO NOT show the user toolbar for users with these role(s).', 'sitecore' ); ?></p>
						<p>
							<select name="admin_bar_disabler_blacklist_roles[]" id="admin_bar_disabler_blacklist_roles" size="9" multiple>
								<optgroup label="<?php _e( 'Default', 'sitecore' ); ?>">
									<option value=""><?php _e( 'Reset', 'sitecore' ); ?></option>
								</optgroup>
								<optgroup label="<?php _e( 'User Roles', 'sitecore' ); ?>">
								<?php
								$exclusion_list_roles = $settings['blacklist_roles'];

								foreach ( $roles as $role => $name ) {
									?>
									<option value="<?php echo esc_attr( $role ); ?>"<?php selected( in_array( $role, $exclusion_list_roles, true ) ); ?>><?php echo esc_html( $name ); ?></option>
									<?php
								}
								?>
								</optgroup>
							</select>
						</p>
						<p class="description"><?php _e( 'CTRL/CMD + Click for multiple selections.', 'sitecore' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="admin_bar_disabler_blacklist_caps">
							<?php _e( 'Capabilities Exclusion', 'sitecore' ); ?>
						</label>
					</th>
					<td>
						<?php
						$exclusion_list_caps = implode( ',', $settings['blacklist_caps'] );
						?>
						<p><?php _e( 'DO NOT show the user toolbar for users with these capabilities', 'sitecore' ); ?></p>
						<textarea rows="3" cols="50" name="admin_bar_disabler_blacklist_caps" id="admin_bar_disabler_blacklist_caps"><?php echo wp_strip_all_tags( $exclusion_list_caps ); ?></textarea>
						<p class="description"><?php _e( 'Add capabilities as a comma-separated list.', 'sitecore' ); ?></p>
					</td>
				</tr>
			</table>
			<p class="description"><?php _e( 'Do not use exclusion list in combination with the inclusion list.<br />In all cases inclusion list overrides exclusion list', 'sitecore' ); ?></p>
			<p>
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'sitecore' ); ?>" />&nbsp;&nbsp;
			</p>
		</form>
	</div>
	<?php
}
