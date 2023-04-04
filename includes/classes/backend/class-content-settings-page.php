<?php
/**
 * Content settings class
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

class Content_Settings_Page extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Website Content', 'sitecore' ),
			'menu_title'  => __( 'Content', 'sitecore' ),
			'description' => __( 'Manage how the content of this website is edited and displayed.', 'sitecore' )
		];

		$options = [
			'capability'    => 'read',
			'settings'      => true,
			'menu_slug'     => 'content-settings',
			'icon_url'      => 'dashicons-edit',
			'position'      => 3,
			'tabs_hashtags' => true
		];

		parent :: __construct(
			$labels,
			$options,
			9
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
			'id'       => 'content-settings-intro',
			'tab'      => __( 'Intro', 'sitecore' ),
			'heading'  => __( 'About This Website\'s Content', 'sitecore' ),
			'content'  => '',
			'callback' => [ $this, 'intro_tab' ]
		] );

		$this->add_content_tab( [
			'capability' => 'manage_options',
			'id'         => 'content-settings-sample',
			'tab'        => __( 'Options', 'sitecore' ),
			'heading'    => __( 'Website Content Options', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'settings_tab' ]
		] );
	}

	/**
	 * Intro tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function intro_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-content-intro.php';
	}

	/**
	 * Settings callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function settings_tab() {
		include SCP_PATH . 'views/backend/forms/partials/settings-content.php';
	}
}
