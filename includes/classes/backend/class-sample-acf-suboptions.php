<?php
/**
 * Sample ACF options subpage
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

use SiteCore\Compatibility as Compat;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Sample_ACF_Suboptions extends Add_Page {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$labels = [
			'page_title'  => __( 'Sample ACF Options Subpage', 'sitecore' ),
			'menu_title'  => __( 'ACF Options', 'sitecore' ),
			'description' => __( 'Demonstration of adding an ACF options subpage.', 'sitecore' )
		];

		$options = [
			'acf'           => [
				'acf_page' => true
			],
			'capability'  => 'read',
			'menu_slug'   => 'sample-acf-options-subpage',
			'parent_slug' => 'options-general.php',
			'icon_url'    => 'dashicons-admin-generic',
			'position'    => 35
		];

		parent :: __construct(
			$labels,
			$options,
			10
		);
	}

	/**
	 * Field group key
	 *
	 * The key value in the `acf_add_local_field_group()` array.
	 * Also used for field group action links.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the field group key.
	 */
	protected function local_field_group_key() {
		return 'group_sample_sub_options_page';
	}

	/**
	 * Field group actions
	 *
	 * Markup of the group actions field.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the field markup.
	 */
	protected function field_group_actions() {

		$html = sprintf(
			'<p>%s</p>',
			__( 'Try one of the following actions with this sample field group.', 'sitecore' )
		);

		$html .= sprintf(
			'<p><a href="%s" target="_blank" rel="nofollow noindex">%s</a> | <a href="%s" rel="nofollow noindex">%s</a> | <a href="%s" rel="nofollow noindex">%s</a></p>',
			esc_url( admin_url( 'tools.php?page=acf-tools&tool=acfe-fg-local&action=php&keys=' . $this->local_field_group_key() ) ),
			__( 'Copy PHP', 'sitecore' ),
			esc_url( admin_url( 'tools.php?page=acf-tools&tool=acfe-fg-local&action=json&keys=' . $this->local_field_group_key() ) ),
			__( 'Export JSON', 'sitecore' ),
			esc_url( admin_url( 'tools.php?page=acf-tools&tool=acfe-fg-local&action=sync&keys=' . $this->local_field_group_key() ) ),
			__( 'Sync to Database', 'sitecore' )
		);

		return $html;
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

		$fields = [];
		$sample = [
			[
				'key'               => 'field_6036c10a75eed',
				'label'             => __( 'Sample Options Subpage Field', 'sitecore' ),
				'name'              => 'sample_options_subpage_field',
				'type'              => 'text',
				'instructions'      => __( 'Sample instructions.', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled'    => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => [
					0 => 'category',
					1 => 'post_tag',
					2 => 'link_category',
					3 => 'acf-field-group-category',
					4 => 'media_type',
					5 => 'test_tax_one'
				],
				'default_value' => '',
				'placeholder'   => __( 'Just a text field.', 'sitecore' ),
				'prepend'       => __( '', 'sitecore' ),
				'append'        => __( '', 'sitecore' ),
				'maxlength'     => '',
				'acfe_field_group_condition' => 0
			]
		];
		$fields = array_merge( $fields, $sample );

		$actions = [
			[
				'key'               => 'field_64bc275df6658',
				'label'             => __( 'Field Group Options', 'sitecore' ),
				'name'              => 'sample_options_subpage_actions',
				'type'              => 'message',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => '',
				],
				'message'   => $this->field_group_actions(),
				'new_lines' => '',
				'esc_html'  => 0,
				'acfe_field_group_condition' => 0,
			]
		];

		if ( Compat\active_acfe() || Compat\has_acfe() ) {
			$fields = array_merge( $fields, $actions );
		}

		acf_add_local_field_group( [
			'key'      => $this->local_field_group_key(),
			'title'    => __( 'Sample Options Subpage', 'sitecore' ),
			'fields'   => $fields,
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $this->page_options['menu_slug']
					]
				]
			],
			'menu_order'            => 0,
			'position'              => 'acf_after_title',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'field',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => __( 'Field group provided as a sample for the sample options page.', 'sitecore' ),
			'acfe_autosync'         => '',
			'acfe_form'             => 0,
			'acfe_display_title'    => '',
			'acfe_meta'             => '',
			'acfe_note'             => ''
		] );
	}
}
