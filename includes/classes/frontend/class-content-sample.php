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

// Alias namespaces.
use SiteCore\Classes\Vendor as Vendor;

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

		// Instantiate Plugin_ACF class to get the suffix.
		$acf = new Vendor\Plugin_ACF;

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
		 * The ACF suffix returns `-acf` if the Advanced
		 * Custom Fields plugin is active or if the bundled
		 * Applied Content Forms files are included, returns
		 * null if not.
		 */
		$templates = [
			'singular' => [
				'plugin' => 'views/frontend/content/content-single-sample' . $acf->suffix(),
				'theme'  => 'templates/template-parts/content/content-single-sample' . $acf->suffix()
			],
			'archive'  => [
				'plugin' => 'views/frontend/content/content-archive-sample' . $acf->suffix(),
				'theme'  => 'templates/template-parts/content/content-archive-sample' . $acf->suffix()
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
