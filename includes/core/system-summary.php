<?php
/**
 * System summary
 *
 * @package    SiteCore
 * @subpackage Includes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\System_Summary;

use SiteCore\Core as Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Total of registered users
 *
 * @since  1.0.0
 * @return integer Returns the number of registered users.
 */
function total_users() {

	// Count the registered users.
	$count = count_users();

	// Return the number of total registered users.
	return intval( $count['total_users'] );
}

/**
 * PHP version
 *
 * States the version of PHP which is
 * running on the current server.
 *
 * @since  1.0.0
 * @return string Returns a PHP version notice.
 */
function php_version() {

	// Markup of the notice.
	$output = sprintf(
		'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
		__( 'The web server is running', 'sitecore' ),
		esc_url( 'https://www.php.net/releases/index.php' ),
		'PHP ' . phpversion()
	);

	// Return the notice. Apply filter for customization.
	return apply_filters( 'scp_php_version_notice', $output );
}

/**
 * Get database variables
 *
 * @since  1.0.0
 * @global object $wpdb Database access abstraction class.
 * @return array Returns an array of database results.
 */
function get_database_vars() {

	// Access the wpdb class.
	global $wpdb;

	// Return false if no database results.
	if ( ! $results = $wpdb->get_results( 'SHOW GLOBAL VARIABLES' ) ) {
		return false;
	}

	// Set up an array of database results.
	$mysql_vars = [];

	// For each database result.
	if ( is_array( $results ) ) {
		foreach ( $results as $result ) {

			// Result name.
			$mysql_vars[ $result->Variable_name ] = $result->Value;
		}
	}

	// Return an array of database results.
	return $mysql_vars;
}

/**
 * Get database version
 *
 * @since  1.0.0
 * @return string Returns the database name/version or
 *                "Not available".
 */
function get_database_version() {

	// Get database variables.
	$vars = get_database_vars();

	// If the database version is found.
	if ( isset( $vars['version'] ) && ! empty( $vars['version'] ) ) {
		$version = sanitize_text_field( $vars['version'] );

	// If no database version is found.
	} else {
		$version = __( 'not available', 'sitecore' );
	}

	// Return the applicable string.
	return $version;
}

/**
 * Database reference URL
 *
 * @since  1.0.0
 * @return string Returns the escaped, filtered URL.
 */
function database_reference() {

	// Default Wikipedia page.
	$url = esc_url( 'https://en.wikipedia.org/wiki/List_of_relational_database_management_systems' );

	// Return the URL.
	return apply_filters( 'scp_database_reference', $url );
}

/**
 * Database version notice
 *
 * @since  1.0.0
 * @return string Returns the text of database version notice.
 */
function database_version() {

	// Markup of the notice.
	$output = sprintf(
		'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
		__( 'The database version is', 'sitecore' ),
		database_reference(),
		get_database_version()
	);

	// Return the notice. Apply filter for customization.
	return apply_filters( 'scp_database_version_notice', $output );
}

/**
 * System notice
 *
 * States the management system and version.
 *
 * @since  1.0.0
 * @return string Returns the link to the management system.
 */
function system_notice() {

	// Get system name.
	$name = Core\platform_name();

	// Text for site or network dashboard.
	if ( is_multisite() && is_network_admin() ) {
		$text = __( 'This network is running', 'sitecore' );
	} else {
		$text = __( 'This website is running', 'sitecore' );
	}

	// Check for ClassicPress.
	if ( Core\is_classicpress() ) {

		// Markup of the notice.
		$output = sprintf(
			'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
			$text,
			esc_url( 'https://github.com/ClassicPress/ClassicPress-release/releases' ),
			$name . ' ' . get_bloginfo( 'version', 'display' )
		);

	// Default to WordPress.
	} else {

		// Markup of the notice.
		$output = sprintf(
			'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
			$text,
			esc_url( 'https://wordpress.org/download/releases/' ),
			$name . ' ' . get_bloginfo( 'version', 'display' )
		);
	}

	// Return the notice. Apply filter for customization.
	return apply_filters( 'scp_system_notice', $output );
}

/**
 * Search engine notice
 *
 * @since  1.0.0
 * @return mixed Returns a string if search engines discouraged.
 *               Returns null if search engines not discouraged.
 */
function search_engines() {

	// Text for network dashboards.
	if ( is_multisite() && is_network_admin() ) {
		$text = __( 'Search engines are discouraged for the primary site', 'sitecore' );

	// Text for site dashboards.
	} else {
		$text = __( 'Search engines are discouraged', 'sitecore' );
	}

	// Check if search engines are asked not to index the site.
	if (
		! is_user_admin() &&
		current_user_can( 'manage_options' ) &&
		'0' == get_option( 'blog_public' )
	) {
		// Markup of the notice.
		$output = sprintf(
			'<a class="scp-search-engines" href="%s">%s</a>',
			esc_url( admin_url( 'options-reading.php' ) ),
			$text
		);

	// Print nothing if search engines are not discouraged.
	} else {
		$output = null;
	}

	// Return the notice. Apply filter for customization.
	return apply_filters( 'scp_search_engines', $output );
}

/**
 * Available themes
 *
 * The available & allowed themes notice.
 *
 * @since  1.0.0
 * @return string Returns the markup of the notice.
 */
function available_themes() {

	// Count available & allowed themes.
	$themes = count( wp_get_themes( [ 'allowed' => true ] ) );

	// Begin the markup of the notice.
	$html = '';
	if ( ! empty( $themes ) ) {

		// Conditional text by theme count.
		$before = _n( 'There is', 'There are', intval( $themes ), 'sitecore' );

		if ( is_network_admin() ) {
			$after = _n( 'network enabled theme.', 'network enabled themes.', intval( $themes ), 'sitecore' );
		} else {
			$after = _n( 'available theme.', 'available themes.', intval( $themes ), 'sitecore' );
		}

		// Link to the themes page if the current user can manage themes.
		if ( current_user_can( 'install_themes' ) || current_user_can( 'customize' ) ) {
			$html = sprintf(
				'%s <a href="%s">%s %s</a>',
				$before,
				esc_url( self_admin_url( 'themes.php' ) ),
				$themes,
				$after
			);

		// Otherwise text with no link.
		} else {
			$html = sprintf(
				'%s %s %s',
				$before,
				$themes,
				$after
			);
		}

	// If no allowed themes are found.
	} else {
		$html = sprintf(
			'%s',
			__( 'There are no themes available.', 'sitecore' )
		);
	}

	// Return the markup of the notice.
	return $html;
}

/**
 * Active theme URI
 *
 * Use `is_null()` to check for a return value.
 *
 * @since  1.0.0
 * @return mixed Returns the URI for the active theme's website
 *               or returns null.
 */
function active_theme_uri() {

	// Get theme data.
	$theme     = wp_get_theme();
	$theme_uri = $theme->get( 'ThemeURI' );

	// If the theme header has a URI.
	if ( $theme_uri ) {
		$uri = $theme_uri;
	}

	// Return the URI string ot null.
	return $uri;
}

/**
 * Active theme notice
 *
 * @since  1.0.0
 * @return string Returns the text of the active theme notice.
 */
function active_theme() {

	// Get the active theme name.
	$theme_name = wp_get_theme();

	/**
	 * If the theme header has the URI tag then
	 * print the link in the header.
	 */
	if ( ! is_null( active_theme_uri() ) ) {

		// Markup of the notice for network dashboards.
		if ( is_network_admin() ) {
			$theme_name = sprintf(
				'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
				__( 'The active theme of the primary site is', 'sitecore' ),
				active_theme_uri(),
				$theme_name
			);

		// Markup of the notice for site dashboards.
		} else {
			$theme_name = sprintf(
				'%s <a href="%s" target="_blank" rel="nofollow noreferrer noopener">%s</a>',
				__( 'The active theme is', 'sitecore' ),
				active_theme_uri(),
				$theme_name
			);
		}

	/**
	 * If the theme header does not have the URI tag and
	 * the current user can switch themes then print a
	 * link to the themes management screen.
	 */
	} elseif ( current_user_can( 'switch_themes' ) ) {

		// Markup of the notice for network dashboards.
		if ( is_network_admin() ) {
			$theme_name = sprintf(
				'%s <a href="%s">%s</a>',
				__( 'The active theme of the primary site is', 'sitecore' ),
				esc_url( self_admin_url( 'themes.php' ) ),
				$theme_name
			);

		// Markup of the notice for site dashboards.
		} else {
			$theme_name = sprintf(
				'%s <a href="%s">%s</a>',
				__( 'The active theme is', 'sitecore' ),
				esc_url( admin_url( 'themes.php' ) ),
				$theme_name
			);
		}

	// Default to the theme name with no link.
	} else {
		$theme_name = sprintf(
			'%s %s',
			__( 'The active theme is', 'sitecore' ),
			$theme_name
		);
	}

	// Return the notice. Apply filter for customization.
	return apply_filters( 'scp_active_theme', $theme_name );
}
