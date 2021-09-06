<?php
/**
 * Tools class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Classes\Tools;
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Tools extends Classes\Base {

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
	 * Constructor method
	 *
	 * Calls the parent constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();
	}

	/**
	 * Instantiate the RTL_Test class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function rtl_test() {
		new RTL_Test;
	}

	/**
	 * Instantiate the Customizer_Reset class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function customizer_reset() {
		new Customizer_Reset;
	}

	/**
	 * Instantiate the Disable_FloC class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function disable_floc() {
		new Disable_FloC;
	}

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
