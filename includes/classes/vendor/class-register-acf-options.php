<?php
/**
 * Register ACF options page
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Vendor
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Vendor;

// Alias namespaces.
use SiteCore\Classes\Admin as Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_ACF_Options extends Admin\Add_Menu_Page {

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The URL to the icon to be used for this menu.
	 *                Default is the gear icon.
	 */
	protected $icon_url = 'dashicons-admin-generic';

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 *                 Default `79` is immediately above Settings.
	 */
	protected $position = 79;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();
	}

	/**
	 * Register menu page
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function menu_page() {

		// Stop here if ACF Pro is not active.
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}
		acf_add_options_page( $this->options_page() );
	}

	/**
	 * Options page arguments
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of page arguments.
	 */
	public function options_page() {

		$options = [
			'page_title' => $this->page_title(),
			'menu_title' => $this->menu_title(),
			'menu_slug'  => strtolower( $this->menu_slug ),
			'capability' => strtolower( $this->capability ),
			'icon_url'   => strtolower( $this->icon_url ),
			'position'   => (integer)$this->position
		];
		return $options;
	}
}
