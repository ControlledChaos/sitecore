<?php
/**
 * Content import/export
 *
 * Adds a metabox to the ACF content tools screen.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Classes\Tools;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Content_Import_Export extends \ACF_Admin_Tool {

	/**
	 * Menu icon
	 *
	 * @since  1.0.0
	 * @access public
	 * @var string Admin menu icon class.
	 */
	public $icon = '';

	/**
	 * Initialize metabox
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$this->name  = 'import-export-content-types';
		$this->title = __( 'Native Content Tools', 'sitecore' );
    	$this->icon  = 'dashicons-upload';
	}

	/**
	 * Metabox output
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function html() {

	?>
	<p><?php _e( 'Import and export native content as well as custom post types.', 'sitecore' ); ?></p>
	<p>
		<a href="<?php echo admin_url( 'import.php' ); ?>" class="button button-primary">
			<?php _e( 'Import Content', 'sitecore' ); ?>
		</a>
		<a href="<?php echo admin_url( 'export.php' ); ?>" class="button button-primary">
			<?php _e( 'Export Content', 'sitecore' ); ?>
		</a>
	</p>
	<?php

	}
}
