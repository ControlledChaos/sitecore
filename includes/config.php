<?php
/**
 * Constants
 *
 * The constants defined here do not override any default bavavior
 * or default user interfaces. However, the corresponding behavior
 * can be overridden in the system config file (e.g. `wp-config`,
 * `app-config` ).
 *
 * The reason for using constants in the config file rather than
 * in a settings file is to prevent site administrators wrongly
 * or incorrectly configuring the site built by developers.
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Configuration
 * @since      1.0.0
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * User admin color picker
 *
 * @since 1.0.0
 * @var   boolean Whether to allow admin color pickers.
 */
if ( ! defined( 'SCP_ALLOW_ADMIN_COLOR_PICKER' ) ) {
	define( 'SCP_ALLOW_ADMIN_COLOR_PICKER', true );
}
