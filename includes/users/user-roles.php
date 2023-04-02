<?php
/**
 * User roles and capabilities
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\User_Roles;

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

	// Add user roles.
	add_action( 'init', $ns( 'add_user_roles' ) );

	// Add simple_role capabilities, priority must be after the initial role definition.
	add_action( 'init', $ns( 'add_user_capabilities' ), 11 );

	if ( get_option( 'enable_multi_user_roles', false ) ) {
		add_action( 'admin_head', $ns( 'profile_role_dropdown' ) );
		add_action( 'show_user_profile', $ns( 'profile_role_output_checklist' ) );
		add_action( 'edit_user_profile', $ns( 'profile_role_output_checklist' ) );
		add_action( 'user_new_form', $ns( 'profile_role_output_checklist' ) );
		add_action( 'profile_update', $ns( 'profile_role_process_checklist' ) );

		// In multisite, user_register hook is too early so wp_network_activate_user add user role after
		if ( is_multisite() ) {
			add_filter( 'signup_site_meta', $ns( 'network_add_roles_in_signup_meta' ), 10, 7 );
			add_action( 'wpnetwork_activate_user', $ns( 'network_add_roles_after_activation' ), 10, 3 );
		} else {
			add_action( 'user_register', $ns( 'profile_role_process_checklist' ) );
		}
	}

	add_filter( 'manage_users_columns', $ns( 'list_role_column_replace' ), 11 );
	add_filter( 'manage_users_custom_column', $ns( 'list_role_column_content' ), 10, 3 );
}

/**
 * Get all roles
 *
 * @since  1.0.0
 * @return array Roles in name => label pairs.
 */
function get_roles() {

	global $wp_roles;

	return apply_filters( 'scp_get_roles', $wp_roles->role_names );
}

/**
 * Get editable roles
 *
 * Gets all editable roles by the current user.
 *
 * @since  1.0.0
 * @return array editable roles
 */
function get_editable_roles() {

	$editable_roles = \get_editable_roles();
	$final_roles    = [];

	foreach ( $editable_roles as $key => $role ) {
		$final_roles[$key] = $role['name'];
	}

	return apply_filters( 'scp_get_editable_roles', (array) $final_roles );
}

/**
 * Get user roles
 *
 * @since  1.0.0
 * @param  object|int $user The user object or ID.
 * @return array Roles in name => label pairs.
 */
function get_user_roles( $user = 0 ) {

	if ( ! $user ) {
		return [];
	}

	$user = get_user_by( 'id', (int) $user );

	if ( empty( $user->roles ) ) {
		return [];
	}

	$all_roles = get_roles();
	$roles     = [];

	foreach( $user->roles as $role ) {
		$roles[ $role ] = $all_roles[ $role ];
	}

	return apply_filters( 'scp_get_user_roles', $roles );
}

/**
 * Update roles
 *
 * Erases the user's existing roles and replace them with the new array.
 *
 * @since  1.0.0
 * @param  integer $user_id The user ID.
 * @param  array $roles The new array of roles for the user.
 * @return bool
 */
function update_roles( $user_id = 0, $roles = [] ) {

	do_action( 'scp_before_update_roles', $user_id, $roles );

	$roles = array_map( 'sanitize_key', (array) $roles );
	$roles = array_filter( (array) $roles, 'get_role' );
	$user  = get_user_by( 'id', (int) $user_id );

	// Remove all editable roles
	$editable = get_editable_roles();

	if ( is_array( $editable ) ) {
		$editable_roles = array_keys( $editable );
	} else {
		$editable_roles = [];
	}

	foreach( $editable_roles as $role ) {
		$user->remove_role( $role );
	}

	foreach( $roles as $role ) {
		$user->add_role( $role );
	}

	do_action( 'scp_after_update_roles', $user_id, $roles, $user->roles );

	return true;
}

/**
 * Can update roles
 *
 * Check whether or not a user can edit roles. User must have the edit_roles cap and
 * must be on a specific site (and not in the network admin area). Users also can't
 * edit their own roles unless they're a network admin.
 *
 * @since  1.0.0
 * @return bool True if current user can update roles, false if not.
 */
function can_update_roles() {

	do_action( 'scp_before_can_update_roles' );

	/**
	 * Conditionally print the checklist
	 *
	 * Following is the condition from which this method was adapted.
	 * It does not allow an administrator to access the checklist on
	 * their own profile. So check for the `IS_PROFILE_PAGE` has been
	 * removed.
	 *
	 * if (
	 *     is_network_admin() ||
	 *     ! current_user_can( 'promote_users' ) ||
	 *     ( defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE && ! current_user_can( 'manage_sites' ) )
	 * )
	 *
	 * @todo Text in network mode then edit this method and docblock accordingly.
	 */
	if ( is_network_admin() || ! current_user_can( 'promote_users' ) ) {
			return false;
	}

	return true;
}

/**
 * Add user roles
 *
 * @since  1.0.0
 * @return void
 */
function add_user_roles() {

	/**
	 * Developer role
	 *
	 * Has administrator capabilities plus develop capability
	 * added by the `add_user_capabilities` method.
	 */
	add_role(
		'developer',
		__( 'Developer', 'sitecore' ),
		get_role( 'administrator' )->capabilities
	);
}

/**
 * Add user capabilities
 *
 * @since  1.0.0
 * @return void
 */
function add_user_capabilities() {

	// Gets the developer role object.
	$developer = get_role( 'developer' );

	// Add a new develop capability.
	$developer->add_cap( 'develop', true );
}

/**
 * Profile role dropdown
 *
 * Removes the default role dropdown from the profile form
 * because the functionality is replaced by a checkbox list.
 *
 * @since  1.0.0
 * @global string $pagenow Gets the filename of the current page.
 * @return mixed Returns CSS & hQuery script.
 */
function profile_role_dropdown() {

	// Get the filename of the current page.
	global $pagenow;

	if ( 'user-edit.php' !== $pagenow && 'user-new.php' !== $pagenow ) {
		return;
	}
	?>
	<style>
	.user-role-wrap {
		display: none;
	}
	</style>
	<script>
	jQuery( document ).ready( function($) {
		$( '.user-role-wrap' ).remove();
	} );
	</script>
	<?php
}

/**
 * Profile role checklist
 *
 * Output the checklist view. If the user is not allowed to edit roles,
 * nothing will appear.
 *
 * @since  1.0.0
 * @param  object $user The current user object.
 * @return void
 */
function profile_role_output_checklist( $user ) {

	if ( ! can_update_roles() ) {
		return;
	}

	wp_nonce_field( 'update-scp-multiple-roles', 'scp_multiple_roles_nonce' );

	$roles = get_editable_roles();

	if ( isset( $user->roles ) ) {
		$user_roles = $user->roles;
	} else {
		$user_roles = null;
	}

	include( apply_filters( 'scp_checklist_template', SCP_PATH . 'views/backend/forms/user-roles-checklist.php' ) );

}

/**
 * Process roles checklist
 *
 * Update the given user's roles as long as we've passed the nonce
 * and permissions checks.
 *
 * @since  1.0.0
 * @param  int $user_id The user ID whose roles might get updated.
 * @return void
 */
function profile_role_process_checklist( $user_id ) {

	/**
	 * The checklist is not always rendered when this method is
	 * triggered on `profile_update` (i.e. when updating a
	 * profile programmatically). First check that the
	 * `scp_multiple_roles_nonce` is available, else bail.
	 * If we continue to process and update_roles(),
	 * all user roles will be lost. We check for
	 * `scp_multiple_roles_nonce` rather than `scp_multiple_roles`
	 * as this input/variable will be empty if all role inputs
	 * are left unchecked.
	 */
	if ( ! isset( $_POST['scp_multiple_roles_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['scp_multiple_roles_nonce'], 'update-scp-multiple-roles' ) ) {
		return;
	}

	if ( ! can_update_roles() ) {
		return;
	}

	if ( isset( $_POST['scp_multiple_roles'] ) && is_array( $_POST['scp_multiple_roles'] ) ) {
		$new_roles = $_POST['scp_multiple_roles'];
	} else {
		$new_roles = [];
	}

	update_roles( $user_id, $new_roles );
}

/**
 * Network signup roles
 *
 * Add roles in signup meta with WP 4.8 filter.
 *
 * @since  1.0.0
 * @param  $meta
 * @param  $domain
 * @param  $path
 * @param  $title
 * @param  $user
 * @param  $user_email
 * @param  $key
 * @return array
 */
function network_add_roles_in_signup_meta( $meta, $domain, $path, $title, $user, $user_email, $key ) {

	if ( isset( $_POST['scp_multiple_roles_nonce'] ) && ! wp_verify_nonce( $_POST['scp_multiple_roles_nonce'], 'update-scp-multiple-roles' ) ) {
		return;
	}

	if ( ! can_update_roles() ) {
		return;
	}

	if ( isset( $_POST['scp_multiple_roles'] ) && is_array( $_POST['scp_multiple_roles'] ) ) {
		$new_roles = $_POST['scp_multiple_roles'];
	} else {
		$new_roles = [];
	}

	if ( empty( $new_roles ) ) {
		return;
	}

	$meta['scp_roles'] = $new_roles;

	return $meta;
}

/**
 * Network signup roles activation
 *
 * Adds multiple roles after user activation.
 *
 * @since  1.0.0
 * @param  $user_id
 * @param  $password
 * @param  $meta
 * @return void
 */
function network_add_roles_after_activation( $user_id, $password, $meta ) {

	if ( ! empty( $meta['scp_roles'] ) ) {
		update_roles( $user_id, $meta['scp_roles'] );
	}
}

/**
 * Replace admin column
 *
 * Removes the default role column and replace it with a custom version.
 *
 * @since  1.0.0
 * @param  array $columns Existing columns in name => label pairs.
 * @return array An updated list of columns.
 */
function list_role_column_replace( $columns ) {

	unset( $columns['role'] );
	$columns['scp_multiple_roles_column'] = __( 'Roles', 'sitecore' );

	return $columns;
}

/**
 * Admin column markup
 *
 * Output of the content of the Roles column.
 *
 * @since  1.0.0
 * @param  string $output The existing HTML to display. Should be empty.
 * @param  string $column The name of the current column.
 * @param  int $user_id The user ID whose roles are about to be displayed.
 * @return string The new HTML output.
 */
function list_role_column_content( $output, $column, $user_id ) {

	if ( 'scp_multiple_roles_column' !== $column ) {
		return $output;
	}

	$roles = get_user_roles( $user_id );

	ob_start();
	include( apply_filters( 'scp_column_template', SCP_PATH . 'views/backend/forms/user-roles-admin-column.php' ) );
	return ob_get_clean();
}
