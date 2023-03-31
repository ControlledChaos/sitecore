<?php
/**
 * Advanced Custom Fields Extended compatibility
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

class Plugin_ACFE extends Plugin {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$paths = [
			'bundled_dir'    => 'acf-extended',
			'bundled_file'   => 'acf-extended.php',
			'installed_dir'  => 'acf-extended',
			'installed_file' => 'acf-extended.php',
			'upgrade_dir'    => 'acf-extended-pro',
			'upgrade_file'   => 'acf-extended.php'
		];

		parent :: __construct(
			$paths,
			true,
			true
		);

		/**
		 * Dequeue ACFE UI changes
		 *
		 * Uncomment to remove ACFE UI modifications.
		 * May cause conflicts with some metaboxes.
		 */
		// add_action( 'admin_enqueue_scripts', [ $this, 'dequeue_acfe_ui' ] );

		/**
		 * ACF local JSON
		 *
		 * Remove some of the JSON directory filters in ACFE.
		 * Set new directory for saving & loading ACF field groups.
		 */
		// remove_action( 'acf/update_field_group', [ 'ACFE_AutoSync', 'pre_update_field_group_json' ], 10 );
		// remove_action( 'acf/untrash_field_group', [ 'ACFE_AutoSync', 'pre_update_field_group_json' ], 10 );
		// remove_action( 'acf/update_field_group', [ 'ACFE_AutoSync', 'post_update_field_group_json' ], 12 );
		// remove_action( 'acf/untrash_field_group', [ 'ACFE_AutoSync', 'post_update_field_group_json' ], 12 );

		add_filter( 'acfe/settings/json_save', [ $this, 'save_acf_json' ], 6 );
		add_filter( 'acfe/settings/json_load', [ $this, 'load_acf_json' ], 6 );

		// Remove pages in menu.
		add_action( 'admin_menu', [ $this, 'acfe_remove_menu' ], 9 );

		// Rich text editor module.


		// New ACFE post type options.
		add_filter( 'register_post_type_args', [ $this, 'acfe_post_type_options' ], 11, 2 );
	}

	/**
	 * Dequeue ACFE UI changes
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function dequeue_acfe_ui() {
		wp_dequeue_style( 'acf-extended-ui' );
        wp_dequeue_script( 'acf-extended-ui' );
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
		return SCP_ACF_URL;
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
		return true;
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
	 * Remove pages in menu
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function acfe_remove_menu() {
		remove_action( 'admin_menu', 'acfe_options_menu', 10 );
		remove_action( 'admin_menu', 'acfe_admin_settings_menu', 10 );
	}

	/**
	 * Editor setting
	 *
	 * Enable ACFE rich text editor module by default.
	 */
	public function acfe_editor_setting() {
		if ( function_exists( 'acf_update_setting' ) ) {
			acf_update_setting( 'acfe/modules/classic_editor', true );
		}
	}

	/**
	 * New ACFE post type options
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $args Array of arguments for registering a post type.
	 * @param  string $post_type Post type key.
	 * @return array Returns an array of new option arguments.
	 */
	public function acfe_post_type_options( $args, $post_type ) {

		// Look for the content settings page and set as a variable.
		$content = get_plugin_page_hookname( 'content-settings', 'content-settings' );

		// Only modify dynamic post types & taxonomies.
		if ( 'acfe-dpt' == $post_type || 'acfe-dt' == $post_type ) {

			// Only show under content settings if the page exists.
			if ( $content ) {

				// Set content settings as menu parent.
				$args['show_in_menu'] = 'content-settings';
			}
			return $args;
		}
		return $args;
	}
}
