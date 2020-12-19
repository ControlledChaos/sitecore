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

		// Add user roles.
		add_action( 'init', [ $this, 'add_user_roles' ] );

		// Add simple_role capabilities, priority must be after the initial role definition.
		add_action( 'init', [ $this, 'add_user_capabilities' ], 11 );

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
	}

	/**
	 * Add user roles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_user_roles() {

		/**
		 * Developer role
		 *
		 * Has administrator capabilities plus develop capability
		 * added by the `add_user_capabilities` method.
		 */
		add_role(
			'developer',
			__( 'Developer', SCP_DOMAIN ),
			get_role( 'administrator' )->capabilities
		);
	}

	/**
	 * Add user capabilities
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_user_capabilities() {

		// Gets the developer role object.
		$developer = get_role( 'developer' );

		// Add a new develop capability.
		$developer->add_cap( 'develop', true );
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
}
