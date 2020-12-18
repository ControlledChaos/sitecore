<?php
/**
 * Sample/starter class
 *
 * @see `includes/classes/README.md`;
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   General
 * @since      1.0.0
 */

namespace SiteCore\Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Sample {

	/**
	 * Sample string
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Returns the string.
	 *                Document what is expected or required.
	 */
	protected $sample_string = 'Sample string variable';

	/**
	 * Sample integer
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    integer Returns the integer.
	 *                Document what is expected or required.
	 */
	protected $sample_integer = 33;

	/**
	 * Sample array
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array Returns the array.
	 */
	protected $sample_array = [];

	/**
	 * Sample boolean
	 *
	 * Document how and where this is used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean Returns true or false.
	 */
	protected $sample_boolean = false;

	/**
	 * Instance of the class
	 *
	 * This method can be used to call an instance
	 * of the class from outside the class.
	 *
	 * Delete this method if not needed.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object Returns an instance of the class.
	 */
	public static function instance() {

		// Varialbe for the instance of the class.
		static $sample_instance = null;

		// Set variable for new instance.
		if ( is_null( $sample_instance ) ) {
			$sample_instance = new self;
		}

		// Return the instance.
		return $sample_instance;
	}

	/**
	 * Constructor method
	 *
	 * Calls the parent constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {}

	/**
	 * Sample method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function sample_method() {
		return null;
	}
}

/**
 * Class instance
 *
 * Puts an instance of the class into a function.
 *
 * Returns an instance of the class that can be used
 * instead of calling the class static instance method.
 *
 * Delete this function if not needed.
 *
 * @example Call a method from the namespaced class:
 *          `Classes\scp_sample_class()->sample_method();`
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function scp_sample_class() {
	return Sample :: instance();
}

/**
 * Run an instance of the class.
 *
 * Uncomment to use.
 */
// scp_sample_class();
