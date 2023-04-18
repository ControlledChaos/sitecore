<?php
/**
 * ACF custom dashboard tabs
 *
 * Used to replace the tabbed content on
 * the custom user dashboard.
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

class Dashboard_Tabs_ACF extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Dashboard Tabs', 'sitecore' ),
			'menu_title'  => __( 'Dashboard Tabs', 'sitecore' ),
			'description' => __( 'Used to replace the tabbed content on the custom user dashboard.', 'sitecore' )
		];

		$options = [
			'acf'            => [
				'acf_page'   => true,
				'capability' => 'manage_options'
			],
			'capability'    => 'manage_options',
			'menu_slug'     => 'acf-dashboard-tabs',
			'parent_slug'   => 'index.php',
			'icon_url'      => 'dashicons-index-card',
			'position'      => 20,
			'add_help'      => false
		];

		parent :: __construct(
			$labels,
			$options,
			$priority
		);

		// Print admin styles to head.
		add_action( "admin_print_styles-$this->options['acf-dashboard-tabs']", [ $this, 'admin_print_styles' ], 20 );
	}

	/**
	 * Field groups
	 *
	 * Register field groups for this options page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_field_groups() {
		include_once SCP_PATH . '/includes/fields/acf-dashboard-tabs.php';
	}

	/**
	 * Print page styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_print_styles() {

		?>
		<style>
		.acf-options-page-subtitle {
			padding: 0 12px !important;
		}

		.acf-options-page-subtitle .acf-label {
			display: none;
		}

		.acf-options-page-subtitle .acf-input {
			margin-top: -12px;
		}

		.acf-options-page-subtitle .acf-input p {
			font-size: 13px;
			color: #646970;
		}

		.acf-options-page-subtitle .acf-input p:first-of-type {
			margin-top: 0;
		}
		</style>
		<?php
	}
}
