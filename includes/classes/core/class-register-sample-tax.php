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
	 * Taxonomy
	 *
	 * Maximum 20 characters. May only contain lowercase alphanumeric
	 * characters, dashes, and underscores. Dashes discouraged.
	 *
	 * @example 'color'
	 * @example 'vehicle_type'
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The database name of the taxonomy.
	 */
	protected $tax_key = 'sample_tax';

	/**
	 * Associated post types
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of associated post types.
	 */
	protected $post_types = [
		'post',
		'sample_type'
	];

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the taxonomy.
	 */
	protected $singular = 'sample tax';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the taxonomy.
	 */
	protected $plural = 'sample taxes';

	/**
	 * Constructor magic method.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Run the parent constructor method.
		parent :: __construct();

	}

	/**
	 * Metabox callback
	 *
	 * Callback function for metabox markup on edit screens.
	 * False will disable the metabox. Null will use the
	 * core tags callback function, `post_tags_meta_box`.
	 *
	 * This sample uses the categories metabox as a template.
	 *
	 * @todo Categories metabox not working in the WordPress block editor.
	 * Uses the tags metabox.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    mixed The callback function name or false or null.
	 */
	protected function meta_box_cb() {
		return 'post_categories_meta_box';
	}
}
