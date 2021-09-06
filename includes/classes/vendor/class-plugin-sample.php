<?php
/**
 * Sample plugin compatibility
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Classes\Vendor;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Plugin_Sample extends Plugin {

	/**
	 * Installed plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the installed plugin.
	 */
	protected $installed_dir = 'sample';

	/**
	 * Installed plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the installed plugin.
	 */
	protected $installed_file = 'sample.php';

	/**
	 * Bundled plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the bundled plugin.
	 */
	protected $bundled_dir = 'sample';

	/**
	 * Bundled plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the bundled plugin.
	 */
	protected $bundled_file = 'sample.php';

	/**
	 * Upgrade plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the upgrade plugin.
	 */
	protected $upgrade_dir = 'sample-pro';

	/**
	 * Upgrade plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the upgrade plugin.
	 */
	protected $upgrade_file = 'sample.php';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();
	}

	/**
	 * Use bundled plugin
	 *
	 * @since  1.0.0
	 * @access public
	 * @return boolean Default should be true. False only
	 *                 if defined as such elsewhere.
	 */
	public function use_bundled() {

		// Override constant.
		if ( defined( 'SCP_USE_SAMPLE_PLUGIN' ) && false == SCP_USE_SAMPLE_PLUGIN ) {
			return false;
		}
		return true;
	}
}
