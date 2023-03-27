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
	'key'    => 'group_641fa9326c59e',
	'title'  => __( 'Admin Pages', 'sitecore' ),
	'fields' => [
		[
			'key'               => 'field_641fb215c7dac',
			'label'             => __( 'User Capability', 'sitecore' ),
			'name'              => 'admin_post_capability',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
		[
			'key'               => 'field_641fb27233f80',
			'label'             => __( 'Menu Label', 'sitecore' ),
			'name'              => 'admin_post_menu_title',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
		[
			'key'               => 'field_641fa9b6422b4',
			'label'             => __( 'Icon URL', 'sitecore' ),
			'name'              => 'admin_post_icon_url',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
		[
			'key'               => 'field_641fabeff9496',
			'label'             => __( 'Menu Position', 'sitecore' ),
			'name'              => 'admin_post_position',
			'type'              => 'number',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'min'  => '',
			'max'  => '',
			'step' => 1,
		],
		[
			'key'               => 'field_641fa95b422b3',
			'label'             => __( 'Page Slug', 'sitecore' ),
			'name'              => 'admin_post_page_slug',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
		[
			'key'               => 'field_641fabc7f9495',
			'label'             => __( 'Parent Slug', 'sitecore' ),
			'name'              => 'admin_post_parent_slug',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
		[
			'key'               => 'field_641faf68b8b3f',
			'label'             => __( 'Page Description', 'sitecore' ),
			'name'              => 'admin_post_description',
			'type'              => 'text',
			'instructions'      => __( '', 'sitecore' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'placeholder'   => __( '', 'sitecore' ),
			'prepend'       => '',
			'append'        => '',
			'maxlength'     => '',
		],
	],
	'location' => [
		[
			[
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'admin',
			],
		],
	],
	'menu_order'            => 0,
	'position'              => 'acf_after_title',
	'style'                 => 'seamless',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'        => [
		0 => 'block_editor',
		1 => 'discussion',
		2 => 'comments',
		3 => 'slug',
		4 => 'author',
		5 => 'format',
		6 => 'categories',
		7 => 'tags',
		8 => 'send-trackbacks',
	],
	'active'             => true,
	'description'        => __( '', 'sitecore' ),
	'show_in_rest'       => 0,
	'acfe_display_title' => '',
	'acfe_autosync'      => '',
	'acfe_form' => 0,
	'acfe_note' => '',
] );
