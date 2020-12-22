<?php
/**
 * Settings class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Settings
 * @since      1.0.0
 */

namespace SiteCore\Classes\Settings;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Settings {

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
		return new self;
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
