<?php
/**
 * Plugin activation class
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Administration
 * @access     public
 * @since      1.0.0
 */

namespace SiteCore;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Activate {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		if ( version_compare( phpversion(), SCP_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'php_deactivate_notice' ] );
		}
	}

	/**
	 * PHP deactivation notice
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the markup of the admin notice.
	 */
	public function php_deactivate_notice() {

	?>
		<div id="plugin-php-notice" class="notice notice-error is-dismissible">
			<?php echo sprintf(
				'<p>%s %s %s %s %s</p>',
				__( 'Functionality of the', SCP_DOMAIN ),
				esc_html( SCP_NAME ),
				__( 'plugin has been disabled because it requires PHP version', SCP_DOMAIN ),
				SCP_PHP_VERSION,
				__( 'or greater. Your system is running PHP version', SCP_DOMAIN ),
				phpversion()
			); ?>
		</div>
	<?php

	}
}

/**
 * Activate plugin
 *
 * Puts an instance of the class into a function.
 *
 * @since  1.0.0
 * @access public
 * @return object Returns an instance of the class.
 */
function activation_class() {
	return new Activate;
}
