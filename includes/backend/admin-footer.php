<?php
/**
 * Admin footer
 *
 * @package    Site_Core
 * @subpackage Admin
 * @category   Footer
 * @since      1.0.0
 */

namespace SiteCore\Admin_Footer;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
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

	if ( get_option( 'enable_custom_admin_footer', true ) ) {
		add_filter( 'admin_footer_text', $ns( 'admin_footer_primary' ), 99 );
		add_filter( 'update_footer', $ns( 'admin_footer_secondary' ), 99 );
	}
}

/**
 * Site title & description
 *
 * @since  1.0.0
 * @return string
 */
function site_name() {

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ! ctype_space( $site_description ) ) {
		$html = sprintf(
			'%s - %s',
			get_bloginfo( 'name' ),
			get_bloginfo( 'description' )
		);
	} else {
		$html = get_bloginfo( 'name' );
	}
	return $html;
}

/**
 * Plugin credit
 *
 * @since  1.0.0
 * @return string
 */
function plugin_credit() {

	$html = sprintf(
		'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a> %s',
		get_bloginfo( 'name' ),
		esc_html__( 'is managed by the', 'sitecore' ),
		esc_url( SCP_PLUGIN_URL ),
		esc_html( SCP_NAME ),
		esc_html__( 'plugin', 'sitecore' )
	);
	return $html;
}

/**
 * Developer website
 *
 * @since  1.0.0
 * @return string
 */
function dev_website() {

	$html = sprintf(
		'%s %s <a href="%s" target="_blank" rel="nofollow">%s</a>',
		get_bloginfo( 'name' ),
		esc_html__( 'website was designed & developed by', 'sitecore' ),
		esc_url( SCP_DEV_URL ),
		esc_html( SCP_DEV_NAME )
	);
	return $html;
}

/**
 * Developer email
 *
 * @since  1.0.0
 * @return string
 */
function dev_email() {

	$html = sprintf(
		'%s %s %s <a href="mailto:%s">%s</a>',
		esc_html__( 'Contact', 'sitecore' ),
		esc_html( SCP_DEV_NAME ),
		esc_html__( 'for website assistance:', 'sitecore' ),
		esc_html( SCP_DEV_EMAIL ),
		esc_html( SCP_DEV_EMAIL )
	);
	return $html;
}

/**
 * Admin footer primary
 *
 * Replaces the "Thank you for creating with ClassicPress/WordPress" text
 * in the #wpfooter div at the bottom of all admin screens. This replaces
 * text inside the default paragraph (<p>) tags.
 *
 * @since  1.0.0
 * @return void
 */
function admin_footer_primary() {
	echo plugin_credit();
}

/**
 * Admin footer secondary
 *
 * @since  1.0.0
 * @return void
 */
function admin_footer_secondary() {
	echo dev_email();
}
