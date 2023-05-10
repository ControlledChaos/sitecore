<?php
/**
 * Core classes & functions
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Core;

use SiteCore\Classes\Core as Core_Class,
	SiteCore\Remove_Blog  as Remove_Blog;

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

	add_action( 'plugins_loaded', $ns( 'classes' ) );

	if ( get_option( 'remove_blog' ) ) {
		Remove_Blog\setup();
	}

	// Allow link manager.
	if ( get_option( 'enable_link_manager', false ) ) {
		add_filter( 'pre_option_link_manager_enabled', '__return_true' );
	}

	// Disable Site Health notifications.
	if ( get_option( 'disable_site_health', false ) ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}
	if ( defined( 'SCP_DISABLE_SITE_HEALTH' ) && SCP_DISABLE_SITE_HEALTH ) {
		add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );
	}

	// Disable block widgets.
	if ( get_option( 'disable_block_widgets', true ) ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}
	if ( defined( 'SCP_DISABLE_BLOCK_WIDGETS' ) && SCP_DISABLE_BLOCK_WIDGETS ) {
		add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}

	// Login screen modifications.
	add_filter( 'login_headertext', $ns( 'login_title' ) );
	add_filter( 'login_headerurl', $ns( 'login_url' ) );
	add_action( 'login_head', $ns( 'login_styles' ) );

	// Remove the Draconian capital P filters.
	remove_filter( 'the_title', 'capital_P_dangit', 11 );
	remove_filter( 'the_content', 'capital_P_dangit', 11 );
	remove_filter( 'comment_text', 'capital_P_dangit', 31 );

	// System email from text.
	add_filter( 'wp_mail_from_name', $ns( 'mail_from_name' ) );

	// Disable WordPress administration email verification prompt.
	add_filter( 'admin_email_check_interval', '__return_false' );
}

/**
 * Core classes
 *
 * @since  1.0.0
 * @return void
 */
function classes() {

	$admin_type = new Core_Class\Register_Admin;
	$admin_type->add_type();

	/**
	 * Editor options for WordPress
	 *
	 * Not run for ClassicPress and the default antibrand system.
	 * The `Core\is_classicpress()` function checks for ClassicPress.
	 *
	 * Not run if the Classic Editor plugin is active.
	 */
	if ( ! is_classicpress() ) {
		if ( ! is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			new Core_Class\Editor_Options;
		}
	}

	if ( get_option( 'enable_sample_files', false ) ) {
		$sample_type = new Core_Class\Register_Sample_Type;
		$sample_type->add_type();

		$sample_tax = new Core_Class\Register_Sample_Tax;
		$sample_tax->add_tax();
	}
}

/**
 * Email from text
 *
 * @since  1.0.0
 * @return mixed Returns the option text or null.
 */
function mail_from_name() {

	$option = get_option( 'email_from_name' );

	if ( ! empty( $option ) ) {
		return $option;
	}
	return get_bloginfo( 'name' );
}

/**
 * Check for ClassicPress
 *
 * @since  1.0.0
 * @return boolean Returns true is ClassicPress is used.
 */
function is_classicpress() {

	if ( function_exists( 'classicpress_version' ) ) {
		return true;
	}
	return false;
}

/**
 * Platform name
 *
 * @since  1.0.0
 * @return string Returns the name of the platform
 */
function platform_name() {

	$name = 'WordPress';
	if ( is_classicpress() ) {
		$name = 'ClassicPress';
	}

	return apply_filters( 'scp_platform_name', $name );
}

/**
 * Dashboard type
 *
 * @since  1.0.0
 * @return string Returns the text of the dashboard type.
 */
function dashboard_type() {

	if ( is_network_admin() ) {
		return 'network';
	} else {
		return 'website';
	}
}

/**
 * Login title
 *
 * Includes the logo if set in the customizer.
 *
 * @since  1.0.0
 * @return string Returns the title markup.
 */
function login_title() {

	// Get the custom logo URL.
	$logo   = get_theme_mod( 'custom_logo' );
	$src    = wp_get_attachment_image_src( $logo , 'full' );
	$output = '';

	// Title markup, inside the h1 > a elements.
	if ( has_custom_logo( get_current_blog_id() ) ) {
		$output .= sprintf(
			'<span class="login-title-logo site-logo"><img src="%s" /></span> ',
			$src[0]
		);
	}

	$output .= sprintf(
		'<span class="login-title-text site-title">%s %s</span> ',
		get_bloginfo( 'name' ),
		__( 'Login', 'sitecore' )
	);

	return $output;
}

/**
 * Login URL
 *
 * @since  1.0.0
 * @return string Returns the URL.
 */
function login_url() {
	return site_url( '/' );
}

/**
 * Login styles
 *
 * @since  1.0.0
 * @return void
 */
function login_styles() {

	$style = '<style>';
	$style .= '
	.login h1 a {
		width: unset;
		height: unset;
		text-indent: 0px;
		background: none;
		font-size: unset;
	}

	.login h1 a .login-title-logo {
		display: block;
		max-width: 120px;
		margin: 0 auto;
	}

	.login h1 a .login-title-logo img {
		display: block;
		max-width: 100%;
		height: auto;
		-ms-interpolation-mode: bicubic;
		border: 0;
	}';
	$style .= '</style>';

	echo $style;
}
