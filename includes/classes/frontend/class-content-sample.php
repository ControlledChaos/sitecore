<?php
/**
 * Sample post content filter
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Front
 * @since      1.0.0
 */

namespace SiteCore\Classes\Front;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Content_Sample extends Content_Filter {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$types = [
			'post',
			'sample_type'
		];

		$taxes = [
			'sample_tax'
		];

		$formats = [
			'aside'
		];

		/**
		 * Content template file paths
		 *
		 * Do not include the `.php` file extension.
		 * This is added by the parent class.
		 *
		 * The `-acf` suffix is added by the parent class
		 * id the `acf_suffix` key is true.
		 */
		$templates = [
			'acf_suffix' => true,
			'singular'   => [
				'plugin' => 'views/frontend/content/content-single-sample',
				'theme'  => 'templates/template-parts/content/content-single-sample'
			],
			'archive'    => [
				'plugin' => 'views/frontend/content/content-archive-sample',
				'theme'  => 'templates/template-parts/content/content-archive-sample'
			]
		];

		parent :: __construct(
			$types,
			$taxes,
			$formats,
			$templates,
			10
		);
	}
}
