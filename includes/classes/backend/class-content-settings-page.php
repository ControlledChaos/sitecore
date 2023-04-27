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
			'page_title'  => $this->page_title(),
			'menu_title'  => __( 'Content', 'sitecore' ),
			'description' => $this->description()
		];

		$options = [
			'capability'    => 'read',
			'settings'      => [
				'print_form' => true,
				'capability' => 'manage_options'
			],
			'menu_slug'     => 'custom-content',
			'icon_url'      => 'dashicons-edit',
			'position'      => 25,
			'tabs_hashtags' => true
		];

		parent :: __construct(
			$labels,
			$options,
			9
		);
	}

	/**
	 * Page title
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page title.
	 */
	protected function page_title() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			$title = __( 'Website Content', 'sitecore' );
		} else {
			$title = __( 'Custom Content', 'sitecore' );
		}
		return $title;
	}

	/**
	 * Page description
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page description.
	 */
	protected function description() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			$description = __( 'This is an overview of this website\'s content.', 'sitecore' );
		} else {
			$description = __( 'Manage how the content of this website is edited and displayed.', 'sitecore' );
		}
		return sprintf(
			'<p class="description">%s</p>',
			$description
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
			'capability' => 'read',
			'id'         => 'content-settings-intro',
			'tab'        => __( 'Intro', 'sitecore' ),
			'heading'    => __( 'About This Website\'s Content', 'sitecore' ),
			'icon'       => 'dashicons-info',
			'content'    => '',
			'callback'   => [ $this, 'intro_tab' ]
		] );

		$this->add_content_tab( [
			'capability' => 'manage_options',
			'id'         => 'content-settings',
			'tab'        => __( 'Options', 'sitecore' ),
			'heading'    => __( 'Website Content Options', 'sitecore' ),
			'icon'       => 'dashicons-admin-generic',
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
