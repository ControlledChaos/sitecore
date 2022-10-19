<?php
/**
 * Register the site help post type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Site_Help extends Register_Type {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'singular'    => __( 'help page', 'sitecore' ),
			'plural'      => __( 'help pages', 'sitecore' ),
			'description' => '',
			'menu_icon'   => 'dashicons-welcome-learn-more'
		];

		$options = [
			'public'        => false,
			'show_in_menu'  => false,
			'menu_position' => 100
		];

		parent :: __construct(
			'site_help',
			$labels,
			$options,
			10,
			false
		);
	}

	/**
	 * Rewrite rules
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Returns the array of rewrite rules.
	 */
	public function rewrite() {

		$rewrite = [
			'slug'       => 'site-help',
			'with_front' => false,
			'feeds'      => false,
			'pages'      => false
		];

		return $rewrite;
	}
}
