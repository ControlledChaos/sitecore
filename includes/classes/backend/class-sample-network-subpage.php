<?php
/**
 * Sample submenu page class
 *
 * Copy this file and rename it to reflect
 * its new class name. Add to the autoloader
 * and instantiate where appropriate.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Classes\Admin;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Sample_Network_Subpage extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Sample Subpage', 'sitecore' ),
			'menu_title'  => __( 'Sample Subpage', 'sitecore' ),
			'description' => __( 'Demonstration of adding a network subpage.', 'sitecore' )
		];

		$options = [
			'network'       => true,
			'capability'    => 'read',
			'menu_slug'     => 'sample-subpage',
			'parent_slug'   => 'sites.php',
			'icon_url'      => 'dashicons-welcome-learn-more',
			'position'      => 9,
			'add_help'      => true
		];

		parent :: __construct(
			$labels,
			$options,
			$priority
		);
	}

	/**
	 * Tabbed content
	 *
	 * Add content to the tabbed section of the page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function tabs() {

		$this->add_content_tab( [
			'id'         => 'sample-one',
			'tab'        => __( 'One', 'sitecore' ),
			'heading'    => __( 'Sample Content One', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'sample-two',
			'tab'        => __( 'Two', 'sitecore' ),
			'heading'    => __( 'Sample Content Two', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'sample_tab' ]
		] );
	}

	/**
	 * Sample tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function sample_tab() {
		include SCP_PATH . 'views/backend/pages/sample-page-content.php';
	}
}
