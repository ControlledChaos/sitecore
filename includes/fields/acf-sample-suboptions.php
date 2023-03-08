<?php
/**
 * Sample ACF options subpage field group
 *
 * The ACF export tool does not use the short
 * array syntax and does not add internationalization
 * functions. Also, it does not align arrays.
 * This is sample has been made consistent with
 * the rest of this plugin.
 *
 * @package    Site_Core
 * @subpackage Fields
 * @category   ACF
 * @since      1.0.0
 */

namespace SiteCore\Fields\ACF;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

acf_add_local_field_group( [
	'key'    => 'group_sample_sub_options_page',
	'title'  => __( 'Sample Options Subpage', 'sitecore' ),
	'fields' => [
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
				'id'    => '',
			],
			'acf-column-enabled'    => 0,
			'acf-column-post_types' => '',
			'acf-column-taxonomies' => [
				0 => 'category',
				1 => 'post_tag',
				2 => 'link_category',
				3 => 'acf-field-group-category',
				4 => 'media_type',
				5 => 'test_tax_one',
			],
			'default_value' => '',
			'placeholder'   => __( 'Just a text field.', 'sitecore' ),
			'prepend'       => __( '', 'sitecore' ),
			'append'        => __( '', 'sitecore' ),
			'maxlength'     => '',
			'acfe_field_group_condition' => 0,
		],
	],
	'location' => [
		[
			[
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'sample-acf-options-subpage',
			],
		],
	],
	'menu_order'            => 0,
	'position'              => 'acf_after_title',
	'style'                 => 'seamless',
	'label_placement'       => 'top',
	'instruction_placement' => 'field',
	'hide_on_screen'        => '',
	'active'                => true,
	'description'           => __( 'Field group provided as a sample for the sample options subpage.', 'sitecore' ),
	'acfe_autosync'         => [
		0 => 'json',
	],
	'acfe_form'          => 0,
	'acfe_display_title' => '',
	'acfe_meta'          => '',
	'acfe_note'          => '',
] );
