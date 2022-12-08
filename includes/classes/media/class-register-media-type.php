<?php
/**
 * Register media type taxonomy
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Media
 * @since      1.0.0
 */

namespace SiteCore\Classes\Media;

// Alias namespaces.
use SiteCore\Classes\Core as Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Media_Type extends Core\Register_Tax {

	/**
	 * Constructor magic method.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$types = [
			'attachment'
		];

		$labels = [
			'singular'    => __( 'media type', 'sitecore' ),
			'plural'      => __( 'media types', 'sitecore' ),
			'description' => __( 'Organize the media library by file types.', 'sitecore' ),
			'menu_icon'   => 'dashicons-tag'
		];

		$options = [];

		// Run the parent constructor method.
		parent :: __construct(
			'media_type',
			$types,
			$labels,
			$options,
			10
		);
	}
}
