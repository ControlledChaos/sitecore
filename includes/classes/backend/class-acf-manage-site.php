<?php
/**
 * Sample ACF options page
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

class ACF_Manage_Site extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Manage Website', 'sitecore' ),
			'menu_title'  => __( 'Manage Website', 'sitecore' ),
			'description' => __( '', 'sitecore' )
		];

		$options = [
			'acf_page'    => true,
			'capability'  => 'read',
			'menu_slug'   => 'acf-manage-website',
			'parent_slug' => 'index.php',
			'icon_url'    => 'dashicons-admin-generic',
			'position'    => 1
		];

		parent :: __construct(
			$labels,
			$options
		);
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

		if ( function_exists( 'acf_add_local_field_group' ) ) :

			acf_add_local_field_group( [
				'key'    => 'group_6408c44b12845',
				'title'  => __( 'Manage Website', 'sitecore' ),
				'fields' => [
					[
						'key'               => 'field_6408c48bfd582',
						'label'             => __( 'Developer Notice', 'sitecore' ),
						'name'              => '',
						'aria-label'        => '',
						'type'              => 'message',
						'instructions'      => __( '', 'sitecore' ),
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => [
							'width' => '',
							'class' => '',
							'id'    => '',
						],
						'message' => __( 'This Advanced Custom Fields message field is added via the <code>ACF_Manage_Site</code> class. It is provided as demonstration of adding a field group from the <code>acf_field_groups()</code> method.', 'sitecore' ),
						'new_lines' => 'wpautop',
						'esc_html'  => 0,
					],
				],
				'location' => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'acf-manage-website',
						],
					],
				],
				'menu_order'            => 0,
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __( '', 'sitecore' ),
				'show_in_rest'          => 0,
				'acfe_display_title'    => '',
				'acfe_autosync'         => [
					0 => 'json',
				],
				'acfe_form' => 0,
				'acfe_meta' => '',
				'acfe_note' => '',
			] );

		endif;
	}
}
