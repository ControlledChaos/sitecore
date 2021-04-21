<?php
/**
 * Disable FloC
 *
 * Disable Google's next-generation tracking technology.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Tools
 * @since      1.0.0
 * @author     Code Potent
 *
 * @link https://codepotent.com/classicpress/plugins/disable-floc/
 */

namespace SiteCore\Classes\Tools;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Disable_FloC {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Add http header to disable FLoC.
		add_filter( 'wp_headers', [ $this, 'http_header' ] );
	}

	/**
	 * Add http header to disable FLoC
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $headers
	 * @return array Returns a modified array of http headers.
	 */
	public function http_header( $headers ) {

		// No Permissions-Policy header present? Add one and return.
		if ( empty( $headers['Permissions-Policy'] ) ) {
			$headers['Permissions-Policy'] = 'interest-cohort=()';
			return $headers;
		}

		// Separate Permissions-Policy directives.
		$policies = explode( ',', $headers['Permissions-Policy'] );

		// Check for existence of interest-cohort directive; set flag.
		foreach ( $policies as $n => $policy ) {

			$policies[$n] = $policy = trim( $policy );

			if ( stripos( $policy, 'interest-cohort' ) === 0 ) {
				$directive_present = true;
			}
		}

		// If interest-cohort directive not present, add it.
		if ( ! isset( $directive_present ) ) {
			$policies[] = 'interest-cohort=()';
		}

		// Assign policies to the header.
		$headers['Permissions-Policy'] = implode( ', ', $policies );

		// Return headers.
		return $headers;
	}
}
