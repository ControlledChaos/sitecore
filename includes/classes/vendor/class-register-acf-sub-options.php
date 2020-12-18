<?php
/**
 * Register ACF options subpage
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

class Register_ACF_Sub_Options extends Admin\Add_Menu_Page {

	/**
	 * Menu position
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer The position in the menu order this item should appear.
	 */
	protected $position = 60;

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
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}
		acf_add_options_sub_page( $this->options_page() );
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
			'page_title'  => $this->page_title(),
			'menu_title'  => $this->menu_title(),
			'parent_slug' => strtolower( $this->parent_slug ),
			'menu_slug'   => strtolower( $this->menu_slug ),
			'capability'  => strtolower( $this->capability ),
			'position'    => (integer)$this->position
		];
		return $options;
	}
}
