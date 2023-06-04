<?php
/**
 * Sample post type field group
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Fields
 * @since      1.0.0
 */

namespace SiteCore\Fields\ACF;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! get_option( 'enable_sample_files', false ) ) {
	return;
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( [
		'key'    => 'group_647c2d9e2faa6',
		'title'  => __( 'Sample Post Type', 'sitecore' ),
		'fields' => [
			[
				'key'               => 'field_647c36cfd6510',
				'label'             => __( 'Sample Custom Fields', 'sitecore' ),
				'name'              => '',
				'type'              => 'message',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => ''
				],
				'message'   => __( 'The custom fields below, above the rich text editor, are loaded when the sample files option in Developer Tools is true.', 'sitecore' ),
				'new_lines' => 'wpautop',
				'esc_html'  => 0,
				'acfe_field_group_condition' => 0
			],
			[
				'key'               => 'field_647c2dc8d2170',
				'label'             => __( 'Subtitle', 'sitecore' ),
				'name'              => 'sample_subtitle',
				'type'              => 'text',
				'instructions'      => __( '', 'sitecore' ),
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
					4 => 'sample_tax',
					5 => 'media_type',
					6 => 'test-tax'
				],
				'default_value' => '',
				'placeholder'   => __( '', 'sitecore' ),
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'acfe_field_group_condition' => 0
			],
			[
				'key'               => 'field_647c2eaf3a513',
				'label'             => __( 'Call to Action', 'sitecore' ),
				'name'              => 'sample_call_to_action',
				'type'              => 'radio',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => [
					'width' => '',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled'    => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => '',
				'choices' => [
					'none'   => __( 'None', 'sitecore' ),
					'button' => __( 'Button', 'sitecore' ),
					'image'  => __( 'Image', 'sitecore' )
				],
				'allow_null'        => 0,
				'other_choice'      => 0,
				'default_value'     => 'none',
				'layout'            => 'horizontal',
				'return_format'     => 'value',
				'acfe_field_group_condition' => 0,
				'save_other_choice' => 0
			],
			[
				'key'               => 'field_647c31c630ca5',
				'label'             => __( 'Action Text', 'sitecore' ),
				'name'              => 'sample_call_to_action_text',
				'type'              => 'text',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_647c2eaf3a513',
							'operator' => '!=',
							'value'    => 'none'
						]
					]
				],
				'wrapper' => [
					'width' => '50',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled' => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => [
					0 => 'category',
					1 => 'post_tag',
					2 => 'link_category',
					3 => 'acf-field-group-category',
					4 => 'sample_tax',
					5 => 'media_type',
					6 => 'test-tax'
				],
				'default_value' => '',
				'placeholder'   => __( '', 'sitecore' ),
				'prepend'       => '',
				'append'        => '',
				'maxlength'     => '',
				'acfe_field_group_condition' => 0,
			],
			[
				'key'               => 'field_647c30f2565bb',
				'label'             => __( 'Action Link', 'sitecore' ),
				'name'              => 'sample_call_to_action_link',
				'type'              => 'url',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_647c2eaf3a513',
							'operator' => '!=',
							'value'    => 'none'
						]
					]
				],
				'wrapper' => [
					'width' => '50',
					'class' => '',
					'id'    => '',
				],
				'acf-column-enabled'    => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => '',
				'default_value' => '',
				'placeholder'   => '',
				'acfe_field_group_condition' => 0,
			],
			[
				'key'               => 'field_647c2f353a514',
				'label'             => __( 'Button Background Color', 'sitecore' ),
				'name'              => 'sample_call_to_action_button_bg',
				'type'              => 'color_picker',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_647c2eaf3a513',
							'operator' => '==',
							'value'    => 'button'
						]
					]
				],
				'wrapper' => [
					'width' => '43',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled'    => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => '',
				'default_value' => '#dd0000',
				'display'       => 'default',
				'return_format' => 'value',
				'button_label'  => __( 'Select Color', 'sitecore' ),
				'color_picker'  => 1,
				'absolute'      => 0,
				'input'         => 1,
				'allow_null'    => 1,
				'alpha'         => 1,
				'theme_colors'  => 1,
				'colors'        => [],
				'acfe_field_group_condition' => 0,
			],
			[
				'key'               => 'field_647c301a3a515',
				'label'             => __( 'Button Text Color', 'sitecore' ),
				'name'              => 'sample_call_to_action_button_text',
				'type'              => 'color_picker',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_647c2eaf3a513',
							'operator' => '==',
							'value'    => 'button',
						]
					]
				],
				'wrapper' => [
					'width' => '50',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled' => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => [
					0 => 'category',
					1 => 'post_tag',
					2 => 'link_category',
					3 => 'acf-field-group-category',
					4 => 'sample_tax',
					5 => 'media_type',
					6 => 'test-tax'
				],
				'default_value' => '#ffffff',
				'display'       => 'default',
				'return_format' => 'value',
				'button_label'  => __( 'Select Color', 'sitecore' ),
				'color_picker'  => 1,
				'absolute'      => 0,
				'input'         => 1,
				'allow_null'    => 1,
				'alpha'         => 1,
				'theme_colors'  => 1,
				'colors'        => [],
				'acfe_field_group_condition' => 0
			],
			[
				'key'               => 'field_647c3047565b8',
				'label'             => __( 'Action Image', 'sitecore' ),
				'name'              => 'sample_call_to_action_image',
				'type'              => 'image',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_647c2eaf3a513',
							'operator' => '==',
							'value'    => 'image'
						]
					]
				],
				'wrapper' => [
					'width' => '50',
					'class' => '',
					'id'    => ''
				],
				'uploader'              => '',
				'acfe_thumbnail'        => 0,
				'acf-column-enabled'    => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => '',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'min_width'     => '',
				'min_height'    => '',
				'min_size'      => '',
				'max_width'     => '',
				'max_height'    => '',
				'max_size'      => '',
				'mime_types'    => '',
				'acfe_field_group_condition' => 0,
				'library'       => 'all',
			],
			[
				'key'               => 'field_647c30c2565ba',
				'label'             => __( 'Image Text Color', 'sitecore' ),
				'name'              => 'sample_call_to_action_image_text',
				'type'              => 'color_picker',
				'instructions'      => __( '', 'sitecore' ),
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field' => 'field_647c2eaf3a513',
							'operator' => '==',
							'value' => 'image'
						]
					]
				],
				'wrapper' => [
					'width' => '50',
					'class' => '',
					'id'    => ''
				],
				'acf-column-enabled' => 0,
				'acf-column-post_types' => '',
				'acf-column-taxonomies' => [
					0 => 'category',
					1 => 'post_tag',
					2 => 'link_category',
					3 => 'acf-field-group-category',
					4 => 'sample_tax',
					5 => 'media_type',
					6 => 'test-tax'
				],
				'default_value' => '#ffffff',
				'display'       => 'default',
				'return_format' => 'value',
				'button_label'  => __( 'Select Color', 'sitecore' ),
				'color_picker'  => 1,
				'absolute'      => 0,
				'input'         => 1,
				'allow_null'    => 1,
				'alpha'         => 1,
				'theme_colors'  => 1,
				'colors'        => [],
				'acfe_field_group_condition' => 0
			]
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'sample_type'
				]
			]
		],
		'menu_order'            => 0,
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => __( '', 'sitecore' ),
		'acfe_autosync'         => [
			0 => 'json'
		],
		'acfe_form'             => 0,
		'acfe_display_title'    => '',
		'acfe_meta'             => '',
		'acfe_note'             => ''
	] );
endif;
