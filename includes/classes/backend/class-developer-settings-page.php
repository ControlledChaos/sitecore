<?php
/**
 * Developer Tools page class
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

class Developer_Settings_Page extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Developer Tools', 'sitecore' ),
			'menu_title'  => __( 'Developers', 'sitecore' ),
			'description' => __( 'Options for custom development tools.', 'sitecore' ),
		];

		$options = [
			'settings'      => true,
			'capability'    => 'develop',
			'menu_slug'     => 'developer-tools',
			'parent_slug'   => 'tools.php',
			'icon_url'      => 'dashicons-admin-generic',
			'position'      => 1,
			'tabs_hashtags' => true,
			'add_help'      => false
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
			'id'         => 'dev-tools',
			'tab'        => __( 'Tools', 'sitecore' ),
			'heading'    => __( 'Developer Tools', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'dev_tools' ]
		] );

		$this->add_content_tab( [
			'id'         => 'user-tools',
			'tab'        => __( 'Users', 'sitecore' ),
			'heading'    => __( 'User Options', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'user_tools' ]
		] );

		$this->add_content_tab( [
			'id'         => 'system-info',
			'tab'        => __( 'System', 'sitecore' ),
			'heading'    => __( 'System Information', 'sitecore' ),
			'content'    => '',
			'callback'   => [ $this, 'system_info' ]
		] );
	}

	/**
	 * Developer Tools tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function dev_tools() {
		include SCP_PATH . 'views/backend/forms/partials/settings-dev-tools.php';
	}

	/**
	 * User Options tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function user_tools() {
		include SCP_PATH . 'views/backend/forms/partials/settings-dev-user-tools.php';
	}

	/**
	 * System Information tab callback
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed Returns the tab content.
	 */
	public function system_info() {
		include SCP_PATH . 'views/backend/forms/partials/settings-dev-system-info.php';
	}
}
