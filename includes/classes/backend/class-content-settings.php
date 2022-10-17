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
use SiteCore\Classes\Settings as Settings;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Content_Settings extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Content Settings', 'sitecore' ),
			'menu_title'  => __( 'Content', 'sitecore' ),
			'description' => __( 'Manage how the content of this website is edited and displayed.' )
		];

		$options = [
			'settings'      => true,
			'menu_slug'     => 'content-settings',
			'icon_url'      => 'dashicons-edit',
			'position'      => 26
		];

		parent :: __construct(
			$labels,
			$options
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
			'id'         => 'content-settings-intro',
			'tab'        => __( 'Intro', 'sitecore' ),
			'heading'    => __( 'Introduction', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'intro_tab' ]
		] );

		$this->add_content_tab( [
			'id'         => 'content-settings-sample',
			'tab'        => __( 'Another', 'sitecore' ),
			'heading'    => __( 'Sample Tab', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'sample_tab' ]
		] );
	}

	/**
	 * Page heading
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the page heading.
	 */
	protected function heading() {
		return __( 'Content Settings', 'sitecore' );
	}

	/**
	 * Intro tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function intro_tab() {
		include SCP_PATH . 'views/backend/pages/partials/settings-content-intro.php';
	}

	/**
	 * Sample tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function sample_tab() {
		include SCP_PATH . 'views/backend/pages/partials/settings-content-sample.php';
	}
}
