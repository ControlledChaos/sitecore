<?php
/**
 * Template tags
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Tags;

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
}

/**
 * Site logo
 *
 * @since  1.0.0
 * @param  mixed $html Defaults to null.
 * @return mixed Returns the logo markup or null.
 */
function site_logo( $html = null ) {

	// Get the custom logo data.
	$logo    = get_theme_mod( 'custom_logo' );
	$caption = wp_get_attachment_caption( $logo );
	$alt     = get_post_meta( $logo, '_wp_attachment_image_alt', true );
	$src     = wp_get_attachment_image_src( $logo , 'full' );

	// Image caption.
	if ( $caption ) {
		$caption = $caption;
	} elseif ( $alt ) {
		$caption = $alt;
	} else {
		$caption = sprintf(
			'%s %s',
			get_bloginfo( 'name' ),
			__( 'logo', 'sitecore' )
		);
	}

	// Markup if a logo has been set.
	if ( has_custom_logo( get_current_blog_id() ) ) {

		$html  = '<figure class="site-logo">';
		$html .= sprintf(
				'<a href="%s"><img src="%s" /></a>',
				esc_attr( esc_url( get_bloginfo( 'url' ) ) ),
				esc_attr( esc_url( $src[0] ) )
			);
		$html .= sprintf(
			'<figcaption class="screen-reader-text">%s</figcaption>',
			esc_attr( apply_filters( 'scp_site_logo_caption', $caption ) )
		);
		$html .= '</figure>';
	}

	// Return the logo markup or null.
	return apply_filters( 'scp_site_logo', $html );
}
