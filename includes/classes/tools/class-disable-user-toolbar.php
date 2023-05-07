<?php
/**
 * Disable user toolbar
 *
 * Disable the user toolbar on the frontend of sites for
 * specific roles and capabilities based on inclusion
 * and exclusion settings.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Classes\Tools;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Disable_User_Toolbar {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'init' ] );

		if ( is_admin() ) {
			add_action( 'admin_init', [ $this, 'admin_init' ] );

			if ( $this->enable_site_admin() ) {
				add_action( 'admin_menu', [ $this, 'admin_menu' ] );
			}

			if ( is_multisite() ) {
				add_action( 'network_admin_menu', [ $this, 'network_admin_menu' ] );
				add_action( 'network_admin_edit_admin_bar_disabler', [ $this, 'network_settings_save' ] );
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
	public function init() {
		return $this->disable_by_settings();
	}

	/**
	 * Admin init
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function admin_init() {

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
	public function get_settings( $inherit = false ) {

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
			$site_settings = $this->get_site_settings();

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
	public function get_site_settings() {

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
	public function disable_by_settings() {

		$settings = $this->get_settings( true );

		if ( $settings['disable_all'] ) {
			return $this->disable();
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
			return $this->disable();
		}

		$inclusion_list_caps = $settings['whitelist_caps'];

		if ( ! empty( $inclusion_list_caps ) ) {
			foreach ( $inclusion_list_caps as $cap ) {
				if ( $user->has_cap( $cap ) ) {
					return false;
				}
			}
			return $this->disable();
		}

		$exclusion_list_roles = $settings['blacklist_roles'];

		if ( ! empty( $exclusion_list_roles ) ) {
			if ( ! is_array( $exclusion_list_roles ) ) {
				$exclusion_list_roles = [ $exclusion_list_roles ];
			}

			foreach ( $exclusion_list_roles as $role ) {
				if ( in_array( $role, $user->roles, true ) ) {
					return $this->disable();
				}
			}
		}

		$exclusion_list_caps = $settings['blacklist_caps'];

		if ( ! empty( $exclusion_list_caps ) ) {
			foreach ( $exclusion_list_caps as $cap ) {
				if ( $user->has_cap( $cap ) ) {
					return $this->disable();
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
	public function enable_site_admin() {

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
	public function disable() {

		if ( ! is_admin() ) {
			add_filter( 'show_admin_bar', '__return_false', 999 );
		} else {
			// WP 3.x support.
			remove_action( 'personal_options', '_admin_bar_preferences' );

			// Disable option on user edit screen.
			add_action( 'admin_print_styles-user-edit.php', [ $this, 'disable_personal_option' ] );

			// Disable option on profile screen.
			add_action( 'admin_print_styles-profile.php', [ $this, 'disable_personal_option' ] );
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
	public function disable_personal_option() {

		echo '<style type="text/css">
				.show-admin-bar {
					display: none;
				}
			</style>';
	}

	/**
	 * Add menu item
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		add_submenu_page(
			'users.php',
			__( 'Disable User Toolbar', 'sitecore' ),
			__( 'User Toolbar', 'sitecore' ),
			'manage_options',
			'admin_bar_disabler',
			[ $this, 'settings_page', ],
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
	public function network_admin_menu() {

		add_submenu_page(
			'users.php',
			__( 'Disable User Toolbar', 'sitecore' ),
			__( 'User Toolbar', 'sitecore' ),
			'manage_network_options',
			'admin_bar_disabler',
			[ $this, 'settings_page', ]
		);
	}

	/**
	 * Save network settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function network_settings_save() {

		check_admin_referer( 'admin_bar_disabler' );

		$settings = $this->get_site_settings();

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

		wp_redirect( 'users.php?page=admin_bar_disabler&settings-updated=1' );
		die();
	}

	/**
	 * Admin settings page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function settings_page() {

		$settings    = $this->get_settings();
		$network     = is_multisite() && is_network_admin();
		$action      = 'options.php';
		$all_message = __( 'Disable the frontend toolbar for all users.', 'sitecore' );

		if ( $network ) {
			$settings    = $this->get_site_settings();
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
		<div class="wrap">

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
								<select name="admin_bar_disabler_whitelist_roles[]" id="admin_bar_disabler_whitelist_roles" size="9" multiple style="overflow-y: auto">
									<optgroup label="<?php _e( 'Default', 'sitecore' ); ?>" style="font-style: normal; font-weight: normal">
										<option value=""><?php _e( 'Reset', 'sitecore' ); ?></option>
									</optgroup>
									<optgroup label="<?php _e( 'User Roles', 'sitecore' ); ?>" style="font-style: normal; font-weight: normal">
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
							<p>
								<?php
								foreach ( $roles as $role => $name ) {
									?>
									<input id="<?php echo $inclusion_list_roles . '-' . $role; ?>" name="<?php echo $inclusion_list_roles . '-' . $role; ?>" type="checkbox" value="<?php echo esc_attr( $role ); ?>"<?php checked( in_array( $role, $inclusion_list_roles, true ) ); ?> /> <?php echo esc_html( $name ); ?>
									<?php
								}
								?>
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
							<textarea rows="3" cols="50" name="admin_bar_disabler_whitelist_caps" id="admin_bar_disabler_whitelist_caps" style="resize: none"><?php echo wp_strip_all_tags( $inclusion_list_caps ); ?></textarea>
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
								<select name="admin_bar_disabler_blacklist_roles[]" id="admin_bar_disabler_blacklist_roles" size="9" multiple style="overflow-y: auto">
									<optgroup label="<?php _e( 'Default', 'sitecore' ); ?>" style="font-style: normal; font-weight: normal">
										<option value=""><?php _e( 'Reset', 'sitecore' ); ?></option>
									</optgroup>
									<optgroup label="<?php _e( 'User Roles', 'sitecore' ); ?>" style="font-style: normal; font-weight: normal">
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
							<textarea rows="3" cols="50" name="admin_bar_disabler_blacklist_caps" id="admin_bar_disabler_blacklist_caps" style="resize: none"><?php echo wp_strip_all_tags( $exclusion_list_caps ); ?></textarea>
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
}
