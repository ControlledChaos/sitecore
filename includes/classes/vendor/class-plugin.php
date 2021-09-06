<?php
/**
 * Plugin initialization class
 *
 * Extend this class to load a plugin and
 * add related filters & actions.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Vendor
 * @since      1.0.0
 */

namespace SiteCore\Classes\Vendor;

// Alias namespaces.
use SiteCore as SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Plugin {

	// Define path to the vendor directory for bundled plugins.
	CONST SCP_VENDOR_PATH = SCP_PATH . 'includes/vendor/';

	/**
	 * Installed plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the installed plugin.
	 */
	protected $installed_dir = '';

	/**
	 * Installed plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the installed plugin.
	 */
	protected $installed_file = '';

	/**
	 * Bundled plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the bundled plugin.
	 */
	protected $bundled_dir = '';

	/**
	 * Bundled plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the bundled plugin.
	 */
	protected $bundled_file = '';

	/**
	 * Allow installed
	 *
	 * Allow the installed version to be activated.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean
	 */
	protected $allow_installed = true;

	/**
	 * Allow upgrade
	 *
	 * If a pro or premium version is available
	 * and installed then allow this to be used.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean
	 */
	protected $allow_upgrade = true;

	/**
	 * Upgrade plugin directory
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The directory of the upgrade plugin.
	 */
	protected $upgrade_dir = '';

	/**
	 * Upgrade plugin file
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string The core file of the upgrade plugin.
	 */
	protected $upgrade_file = '';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Deactivate installed versions if not allowed.
		$this->deactivate_installed();
		$this->deactivate_upgrade();
	}

	/**
	 * Use bundled plugin
	 *
	 * Optional in child classes.
	 *
	 * Copy this method into a child class to
	 * define a unique plugin constant that can
	 * be used in the system config file to
	 * disable use of the bundled product.
	 *
	 * The use of constants rather than settings is to
	 * prevent site owners and administrators disabling
	 * code which is needed to operate the site. Non-
	 * developers are less likely to edit the config file.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Default should be true. False only
	 *                 if defined as such elsewhere.
	 */
	protected function use_bundled() {

		/**
		 * Override constant
		 *
		 * This is a dummy constant used so that this
		 * method will return true by default.
		 * Define a unique constant in a child class.
		 */
		if ( defined( 'SCP_USE_THIS_PLUGIN' ) && false == SCP_USE_THIS_PLUGIN ) {
			return false;
		}
		return true;
	}

	/**
	 * Path to plugin file
	 *
	 * Path to the core plugin file within
	 * the vendor directory.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the path to the core plugin file.
	 */
	protected function bundled_path() {

		$dir  = $this->bundled_dir;
		$file = $this->bundled_file;

		// Return the path to the core plugin file.
		return self :: SCP_VENDOR_PATH . $dir . '/' . $file;
	}

	/**
	 * Path to upgrade plugin file
	 *
	 * Path to the core upgrade plugin file within
	 * one of the plugin directories.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the path to the core plugin file.
	 */
	protected function upgrade_path() {

		$dir  = $this->upgrade_dir;
		$file = $this->upgrade_file;
		$path = '';

		if (
			defined( 'WPMU_PLUGIN_DIR' ) &&
			is_file( WPMU_PLUGIN_DIR . '/' . $dir . '/' . $file ) &&
			is_readable( WPMU_PLUGIN_DIR . '/' . $dir . '/' . $file ) ) {
			$path = WPMU_PLUGIN_DIR . '/' . $dir . '/' . $file;

		} elseif (
			defined( 'WP_PLUGIN_DIR' ) &&
			is_file( WP_PLUGIN_DIR . '/' . $dir . '/' . $file ) &&
			is_readable( WP_PLUGIN_DIR . '/' . $dir . '/' . $file )
		) {
			$path = WP_PLUGIN_DIR . '/' . $dir . '/' . $file;
		}
		return apply_filters( 'scp_plugin_upgrade_path', $path );
	}

	/**
	 * Basic basename
	 *
	 * The basename of the basic installed plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the directory/file.
	 */
	protected function basic_basename() {

		$dir      = $this->installed_dir;
		$file     = $this->installed_file;
		$basename = '';

		if (
			defined( 'WP_PLUGIN_DIR' ) &&
			is_file( WP_PLUGIN_DIR . '/' . $dir . '/' . $file ) &&
			is_readable( WP_PLUGIN_DIR . '/' . $dir . '/' . $file )
		) {
			$basename = $dir . '/' . $file;
		}
		return apply_filters( 'scp_plugin_basic_basename', $basename );
	}

	/**
	 * Upgrade basename
	 *
	 * The basename of the upgrade installed plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return string Returns the directory/file.
	 */
	protected function upgrade_basename() {

		$dir      = $this->upgrade_dir;
		$file     = $this->upgrade_file;
		$basename = '';

		if (
			defined( 'WP_PLUGIN_DIR' ) &&
			is_file( WP_PLUGIN_DIR . '/' . $dir . '/' . $file ) &&
			is_readable( WP_PLUGIN_DIR . '/' . $dir . '/' . $file )
		) {
			$basename = $dir . '/' . $file;
		}
		return apply_filters( 'scp_plugin_upgrade_basename', $basename );
	}

	/**
	 * Plugin is active
	 *
	 * Checks if the basic version or an upgrade version
	 * is installed and active.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Returns true if either version of the
	 *                 installed plugin is active.
	 */
	protected function is_active() {

		if ( is_plugin_active( $this->basic_basename() ) ) {
			return true;
		} elseif ( is_plugin_active( $this->upgrade_basename() ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Include the bundled files
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function include() {

		// Stop here if bundled is disabled.
		if ( ! $this->use_bundled() ) {
			return;
		} elseif ( is_plugin_active( $this->upgrade_basename() ) ) {
			return;
		}

		// Get the bundled core file path.
		$bundled = $this->bundled_path();

		// Include the core file.
		if ( is_file( $bundled ) && is_readable( $bundled ) ) {
			include( $bundled );
		}
	}

	/**
	 * Allow installed
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Returns true if allowed.
	 */
	protected function allow_installed() {

		// Default value of the `allow_installed` property.
		$allow = true;

		// If `allow_installed` set to false.
		if ( false == $this->allow_installed ) {
			$allow = false;
		}
		return apply_filters( 'scp_plugin_allow_installed', $allow );
	}

	/**
	 * Allow upgrade
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return boolean Returns true if allowed.
	 */
	protected function allow_upgrade() {

		// Default value of the `allow_upgrade` property.
		$allow = true;

		// If `allow_upgrade` set to false.
		if ( false == $this->allow_upgrade ) {
			$allow = false;
		}
		return apply_filters( 'scp_plugin_allow_upgrade', $allow );
	}

	/**
	 * Deactivate installed
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function deactivate_installed() {

		// Stop if the installed version is allowed.
		if ( $this->allow_installed() ) {
			return;
		}

		if ( is_plugin_active( $this->basic_basename() ) ) {
			deactivate_plugins( $this->basic_basename() );
		}

		// Print plugin row notice.
		$this->get_basic_row_notice();
	}

	/**
	 * Deactivate upgrade
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function deactivate_upgrade() {

		// Stop if the upgrade version is allowed.
		if ( $this->allow_upgrade() ) {
			return;
		}

		if ( is_plugin_active( $this->upgrade_basename() ) ) {
			deactivate_plugins( $this->upgrade_basename() );
		}

		// Print plugin row notice.
		$this->get_upgrade_row_notice();
	}

	/**
	 * Get the basic plugin row notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_basic_row_notice() {
		add_action( 'after_plugin_row_' . $this->basic_basename(), [ $this, 'deactivate_row_notice' ], 5, 3 );
	}

	/**
	 * Get the upgrade plugin row notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_upgrade_row_notice() {
		add_action( 'after_plugin_row_' . $this->upgrade_basename(), [ $this, 'deactivate_row_notice' ], 5, 3 );
	}

	/**
	 * Plugin deactivation notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the plugin row notice.
	 */
	public function deactivate_row_notice( $plugin_file, $plugin_data, $status ) {

		$colspan = 4;

		// If WP  version< 5.5.
		if ( version_compare( $GLOBALS['wp_version'], '5.5', '<' ) ) {
			$colspan = 3;
		}

		?>
		<style>
			<?php if ( isset( $plugin_data['update'] ) && ! empty( $plugin_data['update'] ) ) : ?>

				.plugins tr.plugin-deactivation-notice td {
					padding: 0  ! important;
					box-shadow: none ! important;
				}

				.plugins tr.plugin-deactivation-notice .update-message {
					margin: 5px 20px 5px 40px;
				}

				.rtl .plugins tr.plugin-deactivation-notice .update-message {
					margin: 5px 40px 5px 20px;
				}

			<?php endif; ?>
		</style>

		<tr class="plugin-deactivation-notice">
			<td colspan="<?php echo $colspan; ?>" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-error notice-alt">
					<?php echo sprintf(
						'<p>%s %s %s</p>',
						__( 'This plugin is not allowed by the', 'sitecore' ),
						SCP_NAME,
						__( 'plugin to be activated.', 'sitecore' )
					); ?>
				</div>
			</td>
		</tr>
		<?php
	}
}
