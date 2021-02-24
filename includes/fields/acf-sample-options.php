<?php
/**
 * Sample ACF options page field group
 *
 * The ACF export tool does not use the short
 * array syntax and does not add internationalization
 * functions. Also, it does not align arrays.
 * This is sample has been made consistent with
 * the rest of this plugin.
 *
 * @package    Pack_Station
 * @subpackage Fields
 * @category   ACF
 * @since      1.0.0
 */

namespace PackStation\Fields\ACF;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

acf_add_local_field_group( [
	'key'    => 'group_6036c0197044c',
	'title'  => __( 'Sample Options Page', SCP_DOMAIN ),
	'fields' => [
		[
			'key'               => 'field_6036c02cad870',
			'label'             => __( 'Sample Options Page Field', SCP_DOMAIN ),
			'name'              => 'sample_options_page_field',
			'type'              => 'text',
			'instructions'      => __( 'Sample instructions.', SCP_DOMAIN ),
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
			'placeholder'   => __( 'Just a text field.', SCP_DOMAIN ),
			'prepend'       => __( '', SCP_DOMAIN ),
			'append'        => __( '', SCP_DOMAIN ),
			'maxlength'     => '',
			'acfe_field_group_condition' => 0,
		],
	],
	'location' => [
		[
			[
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'sample-options-page',
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
	'description'           => __( 'Field group provided as a sample for the sample options page.', SCP_DOMAIN ),
	'acfe_autosync'         => [
		0 => 'json',
	],
	'acfe_form'          => 0,
	'acfe_display_title' => '',
	'acfe_meta'          => '',
	'acfe_note'          => '',
] );
