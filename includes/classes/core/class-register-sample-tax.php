<?php
/**
 * Register sample taxonomy
 *
 * Copy this file and rename it to reflect
 * its new class name. Add to the autoloader
 * and instantiate where appropriate.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Sample_Tax extends Register_Tax {

	/**
	 * Constructor method
	 *
	 * @see Register_Tax::__construct()
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$types = [
			'sample_type'
		];

		$labels = [
			'singular'    => __( 'sample tax', 'sitecore' ),
			'plural'      => __( 'sample taxes', 'sitecore' ),
			'description' => __( 'A sample taxonomy for the sample post type.', 'sitecore' )
		];

		$options = [
			'meta_box_cb' => 'post_tags_meta_box',
		];

		// Run the parent constructor method.
		parent :: __construct(
			'sample_tax',
			$types,
			$labels,
			$options,
			$this->priority
		);
	}
}
