<?php
/**
 * Sample menu page class
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

class Sample_Page extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Sample Page', 'sitecore' ),
			'menu_title'  => __( 'Sample Page', 'sitecore' ),
			'description' => __( 'Demonstration of adding a page.', 'sitecore' )
		];

		$options = [
			'capability'    => 'read',
			'menu_slug'     => 'sample-page',
			'icon_url'      => 'dashicons-welcome-learn-more',
			'position'      => 3,
			'add_help'      => true
		];

		parent :: __construct(
			$labels,
			$options,
			10
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
			'id'            => 'sample-one',
			'tab'           => __( 'One', 'sitecore' ),
			'heading'       => __( 'Sample Content One', 'sitecore' ),
			'icon'          => 'dashicons-admin-home',
			'content'       => '',
			'sub_tabs_only' => true,
			'callback'      => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'sample-two',
			'tab'        => __( 'Two', 'sitecore' ),
			'heading'    => __( 'Sample Content Two', 'sitecore' ),
			'icon'       => 'dashicons-admin-generic',
			'content'    => '',
			'callback'   => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'        => 'sample-sub-one',
			'parent_id' => 'sample-one',
			'tab'       => __( 'One', 'sitecore' ),
			'heading'   => __( 'Sample Sub-Content One', 'sitecore' ),
			'icon'      => 'dashicons-drumstick',
			'content'   => '',
			'callback'  => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'        => 'sample-sub-two',
			'parent_id' => 'sample-one',
			'tab'       => __( 'Two', 'sitecore' ),
			'heading'   => __( 'Sample Sub-Content Two', 'sitecore' ),
			'icon'      => 'dashicons-carrot',
			'content'   => sprintf(
				'<p style="color: #d00"><strong>%s</strong></p>',
				__( 'An extra paragraph added by the content key.', 'sitecore' )
			),
			'callback'  => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'        => 'sample-sub-three',
			'parent_id' => 'sample-two',
			'tab'       => __( 'Three', 'sitecore' ),
			'heading'   => __( 'Sample Sub-Content Three', 'sitecore' ),
			'icon'      => 'dashicons-beer',
			'content'   => '',
			'callback'  => [ $this, 'sample_tab' ]
		] );

		$this->add_content_tab( [
			'id'        => 'sample-sub-four',
			'parent_id' => 'sample-two',
			'tab'       => __( 'Four', 'sitecore' ),
			'heading'   => __( 'Sample Sub-Content Four', 'sitecore' ),
			'icon'      => 'dashicons-album',
			'content'   => '',
			'callback'  => [ $this, 'sample_tab' ]
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
