<?php
/**
 * Register media type taxonomy
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @access     public
 * @since      1.0.0
 */

declare( strict_types = 1 );
namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Media_Type extends Register_Tax {

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
	protected $tax_key = 'media_type';

	/**
	 * Associated post types
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array The array of associated post types.
	 */
	protected $post_types = [
		'attachment'
	];

	/**
	 * Singular name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The singular name of the taxonomy.
	 */
	protected $singular = 'media type';

	/**
	 * Plural name
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The plural name of the taxonomy.
	 */
	protected $plural = 'media types';

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
}
