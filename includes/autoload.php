<?php
/**
 * Register plugin classes
 *
 * @package  SiteCore
 * @category General
 * @access   public
 * @since    1.0.0
 */

// Theme file namespace.
namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Define the `classes` directory.
define( 'SCP_CLASS', SCP_PATH . 'includes/classes/class-' );

/**
 * Autoload array
 *
 * Array of classes to register. Exludes the
 * activation & deactivation classes.
 */
const CLASSES = [
	'SiteCore\Classes\Init' => SCP_CLASS . 'init.php',
	'SiteCore\Classes\ACF' => SCP_CLASS . 'acf.php',
	'SiteCore\Classes\ACF_Extend' => SCP_CLASS . 'acf-extend.php',
	'SiteCore\Classes\Register_Type' => SCP_CLASS . 'register-type.php',

	// Sample post type registration class.
	// 'SiteCore\Classes\Register_Sample' => SCP_CLASS . 'register-sample.php'
];

// Autoload class files.
spl_autoload_register(
	function ( string $classname ) {
		if ( isset( CLASSES[ $classname ] ) ) {
			require CLASSES[ $classname ];
		}
	}
);
