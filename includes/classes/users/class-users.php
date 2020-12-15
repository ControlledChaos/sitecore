<?php
/**
 * Users class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   General
 * @access     public
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

		// Move the personal data menu items.
        add_action( 'admin_menu', [ $this, 'menus_personal_data' ] );
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
