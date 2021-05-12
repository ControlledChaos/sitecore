<?php
/**
 * Advanced Custom Fields compatibility
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

class ACF extends Plugin {

	/**
	 * Installed plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the installed plugin.
	 */
	protected $installed_dir = 'advanced-custom-fields';

	/**
	 * Installed plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the installed plugin.
	 */
	protected $installed_file = 'acf.php';

	/**
	 * Bundled plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the bundled plugin.
	 */
	protected $bundled_dir = 'acf';

	/**
	 * Bundled plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the bundled plugin.
	 */
	protected $bundled_file = 'acf.php';

	/**
	 * Upgrade plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the upgrade plugin.
	 */
	protected $upgrade_dir = 'advanced-custom-fields-pro';

	/**
	 * Upgrade plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the upgrade plugin.
	 */
	protected $upgrade_file = 'acf.php';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		parent :: __construct();

		/**
		 * ACF local JSON
		 *
		 * Remove some of the JSON directory filters in ACFE.
		 * Set new directory for saving & loading ACF field groups.
		 */
		add_filter( 'acf/settings/save_json', [ $this, 'save_acf_json' ] );
		add_filter( 'acf/settings/load_json', [ $this, 'load_acf_json' ] );

		// Filter assets URL.
		if ( ! $this->is_active() ) {
			add_filter( 'acf/settings/url', [ $this, 'acf_settings_url' ] );
		}

		// Hide/show the ACF admin menu item.
		add_filter( 'acf/settings/show_admin', [ $this, 'acf_settings_show_admin' ] );

		// Admin columns for ACF fields.
		$this->acf_columns();
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
		if ( defined( 'SCP_USE_BUNDLED_ACF' ) && false == SCP_USE_BUNDLED_ACF ) {
			return false;
		}
		return true;
	}

	/**
	 * ACF settings URL
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $url
	 * @return string Returns the URL for ACF files.
	 */
	public function acf_settings_url( $url ) {
		$url = SCP_URL . 'includes/vendor/' . $this->bundled_dir . '/';
		return $url;
	}

	/**
	 * Show ACF in admin menu
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  boolean $show_admin
	 * @return boolean ACF displays in menu if true.
	 */
	public function acf_settings_show_admin( $show_admin ) {

		// Hide if in multisite mode & not the main site.
		$show_admin = true;
		if ( is_multisite() && ! is_main_site() && ! is_super_admin( get_current_user_id() ) ) {
			$show_admin = false;
		}
		return apply_filters( 'scp_acf_settings_show_admin', $show_admin );
	}

	/**
	 * Save ACF JSON directory
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $path
	 * @return string Returns the directory path.
	 */
	public function save_acf_json( $path ) {
		$path = SCP_PATH . 'includes/settings/acf-json';
		return $path;
	}

	/**
	 * Load ACF JSON directory
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $paths
	 * @return array Returns an array of load paths.
	 */
	public function load_acf_json( $paths ) {
		unset( $paths[0] );
		$paths[] = SCP_PATH . 'includes/settings/acf-json';
		return $paths;
	}

	/**
	 * Admin columns for ACF fields
	 *
	 * Adds options in the edit field interface to add the field to
	 * list pages, such as "All Posts".
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acf_columns() {
		if ( class_exists( 'ACF_Columns' ) && is_admin() ) {
			return new ACF_Columns;
		}
	}
}
