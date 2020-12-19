<?php
/**
 * User roles and capabilities
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

class User_Roles_Caps {

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
}
