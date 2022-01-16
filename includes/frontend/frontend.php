<?php
/**
 * Site frontend
 *
 * @package    Site_Core
 * @subpackage Front
 * @category   General
 * @since      1.0.0
 */

namespace SiteCore\Front;

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

	// Remove unpopular meta tags.
	add_action( 'init', $ns( 'head_cleanup' ) );

	// Remove system versions from stylesheets and scripts.
	add_filter( 'style_loader_src', $ns( 'remove_versions' ), 999 );
	add_filter( 'script_loader_src', $ns( 'remove_versions' ), 999 );

	// Disable emoji script.
	add_action( 'init', $ns( 'disable_emojis' ) );

	// Deregister Dashicons for users not logged in.
	add_action( 'wp_enqueue_scripts', $ns( 'deregister_dashicons' ) );

	// Remove user toolbar items.
	add_action( 'admin_bar_menu', $ns( 'remove_toolbar_items' ), 999 );
}

/**
 * Clean up meta tags from the <head>
 *
 * @since  1.0.0
 * @return void
 */
function head_cleanup() {

	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
}

/**
 * Remove system versions
 *
 * Removes the system versions from stylesheet and script inks
 * in the head. The versions are a potential security risk,
 * indicating which version of the system to attack, and force
 * browsers to download new scripts when the system updates.
 *
 * @since  1.0.0
 * @param  string $src Path to the file.
 * @return null
 */
function remove_versions( $src ) {

	if ( strpos( $src, '?ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}

/**
 * Disable emoji script
 *
 * Emojis will still work in modern browsers. This removes the script
 * that makes emojis work in old browser.
 *
 * @since  1.0.0
 * @return void
 */
function disable_emojis() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
}

/**
 * Deregister Dashicons for users not logged in.
 *
 * @since  1.0.0
 * @return void
 */
function deregister_dashicons() {

	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}

/**
 * Remove user toolbar items
 *
 * @since  1.0.0
 * @param  object $wp_admin_bar The WP_Admin_Bar class.
 * @return void
 */
function remove_toolbar_items( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'search' );
}
