<?php
/**
 * Custom post type on the front page
 *
 * Select a custom post type to be displayed in place of latest posts on the front page.
 *
 * @package    Site_Core
 * @subpackage Post Types
 * @category   Blog
 * @since      1.0.0
 */

namespace SiteCore\Front_Page_Post_Type;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Filter post type query.
	add_filter( 'pre_get_posts', $ns( 'front_page_pre_get_posts' ) );

	// Customizer settings & text.
	add_action( 'customize_register', $ns( 'front_page_query_customize' ) );
}

/**
 * Front page query args
 *
 * @since  1.0.0
 * @return array Returns an array of query arguments.
 */
function front_page_query_args() {

	$args = [
		'has_archive'       => true,
		'show_in_nav_menus' => true,
		'public'            => true,
		'_builtin'          => false
	];

	return apply_filters( 'scp_front_page_query_args', $args );
}

/**
 * Front page query
 *
 * @since  1.0.0
 * @return array Returns an array of post type objects.
 */
function front_page_query() {

	$query_args = front_page_query_args();
	$post_types = get_post_types( $query_args, 'objects' );

	return $post_types;
}

/**
 * Modify front page query
 *
 * @since  1.0.0
 * @param  array $query
 * @return void
 */
function front_page_pre_get_posts( $query ) {

	// Stop if in the admin.
	if ( is_admin() ) {
		return;
	}

	if ( $query->is_home() && $query->is_front_page() && $query->is_main_query() ) {

		$post_type  = (string) get_option( 'scp_front_page_post_type', '' );
		$post_types = front_page_query_args();

		if ( in_array( $post_type, $post_types ) ) {
			$query->set( 'post_type', $post_type );
		}

	}

}

/**
 * Customizer settings
 *
 * Adds a control for the front page query.
 * Modifies front page section elements.
 *
 * @since  1.0.0
 * @param  object $wp_customize
 * @return void
 */
function front_page_query_customize( $wp_customize ) {

	$post_types = front_page_query();

	$choices = [];
	$choices['post'] = __( 'Posts', 'sitecore' );

	foreach ( $post_types as $post_type ) {
		$choices[$post_type->name] = $post_type->labels->name;
	}

	$wp_customize->add_setting( 'scp_front_page_post_type', [
		'type'       => 'option',
		'capability' => 'manage_options',
		'default'    => 'post'
	] );

	$wp_customize->add_control( 'scp_front_page_post_type', [
		'label'       => __( 'Front Page Post Type', 'sitecore' ),
		'description' => __( 'Choose which post type to display in the front page feed.', 'sitecore' ),
		'type'        => 'radio',
		'choices'     => $choices,
		'section'     => 'static_front_page',
		'priority'    => 20,
		'active_callback' => __NAMESPACE__ . '\\front_page_query_callback'
	] );

	$wp_customize->get_section( 'static_front_page' )->description = __( 'Select what is displayed on the front page of this website. It can be posts of a selected type in reverse chronological order or static content.', 'sitecore' );

	$wp_customize->get_control( 'show_on_front' )->choices = [
		'posts' => __( 'Post type feed', 'sitecore' ),
		'page'  => __( 'Static content', 'sitecore' ),
	];
}

/**
 * Front page query callback
 *
 * Only show the options when the front page is
 * in the preview and set to posts query.
 *
 * Uncomment `active_callback` in `front_page_customize`
 * to activate.
 *
 * @since  1.0.0
 * @return void
 */
function front_page_query_callback() {
	return ( is_home() );
}
