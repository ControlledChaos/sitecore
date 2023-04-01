<?php
/**
 * Plugin activation
 *
 * The minimum PHP version is not included in the
 * plugin header because the admin notices here are
 * more elegant than the native `die()` screen
 * provided by the management system.
 *
 * @package    Site_Core
 * @subpackage Activate
 * @since      1.0.0
 */

namespace SiteCore\Activate;

// Alias namespaces.
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Add & update options
 *
 * @since  1.0.0
 * @return self
 */
function options() {

	// Get default avatar option.
	$avatar = get_option( 'avatar_default' );
	$fresh  = get_option( 'fresh_site' );

	// Gravatar options to update/override.
	$gravatar = [
		'mystery',
		'mm',
		'blank',
		'gravatar_default',
		'identicon',
		'wavatar',
		'monsterid',
		'retro'
	];

	// Local avatars for option update.
	$mystery = esc_url( SCP_URL . 'assets/images/mystery.png' );
	$blank   = esc_url( SCP_URL . 'assets/images/blank.png' );

	/**
	 * If this is a fresh site, if no default is set, or if mystery Gravatar
	 * is set then update to the local mystery person avatar.
	 */
	if ( true == $fresh || ! $avatar || 'mystery' == $avatar ) {
		update_option( 'avatar_default', $mystery );

	// If the blank Gravatar is set then update to the local blank avatar.
	} elseif ( 'blank' == $avatar ) {
		update_option( 'avatar_default', $blank );

	// If any Gravatar is set then update to the local mystery person avatar.
	} elseif ( in_array( $avatar, $gravatar ) ) {
		update_option( 'avatar_default', $mystery );
	}

	// Options true by default.
	add_option( 'disable_block_widgets', true );
	add_option( 'admin_menu_menus_top', true );
	add_option( 'admin_menu_widgets_top', true );
	add_option( 'toolbar_remove_platform_link', true );
}

/**
 * Get plugin row notice
 *
 * @since  1.0.0
 * @return void
 */
function get_row_notice() {
	add_action( 'after_plugin_row_' . SCP_BASENAME, __NAMESPACE__ . '\row_notice', 5, 3 );
}

/**
 * PHP deactivation notice: after plugin row
 *
 * @since  1.0.0
 * @return string Returns the markup of the plugin row notice.
 */
function row_notice( $plugin_file, $plugin_data, $status ) {

	$colspan = 4;

	// If WP  version< 5.5.
	if ( version_compare( $GLOBALS['wp_version'], '5.5', '<' ) ) {
		$colspan = 3;
	}

	?>
	<style>
		.plugins tr[data-plugin='<?php echo SCP_BASENAME; ?>'] th,
		.plugins tr[data-plugin='<?php echo SCP_BASENAME; ?>'] td {
			box-shadow: none;
		}

		<?php if ( isset( $plugin_data['update'] ) && ! empty( $plugin_data['update'] ) ) : ?>

			.plugins tr.<?php echo 'sitecore'; ?>-plugin-tr td {
				box-shadow: none ! important;
			}

			.plugins tr.<?php echo 'sitecore'; ?>-plugin-tr .update-message {
				margin-bottom: 0;
			}

		<?php endif; ?>
	</style>

	<tr id="plugin-php-notice" class="plugin-update-tr active <?php echo 'sitecore'; ?>-plugin-tr">
		<td colspan="<?php echo $colspan; ?>" class="plugin-update colspanchange">
			<div class="update-message notice inline notice-error notice-alt">
				<?php echo sprintf(
					'<p>%s %s %s %s %s %s</p>',
					__( 'Functionality of the', 'sitecore' ),
					SCP_NAME,
					__( 'plugin has been disabled because it requires PHP version', 'sitecore' ),
					SCP_MIN_PHP_VERSION,
					__( 'or greater. Your system is running PHP version', 'sitecore' ),
					phpversion()
				); ?>
			</div>
		</td>
	</tr>
	<?php
}

/**
 * PHP deactivation notice: admin header
 *
 * @since  1.0.0
 * @return string Returns the markup of the admin notice.
 */
function php_deactivate_notice_header() {

?>
	<div id="plugin-php-notice" class="notice notice-error is-dismissible">
		<?php echo sprintf(
			'<p>%s %s %s %s %s %s</p>',
			__( 'Functionality of the', 'sitecore' ),
			SCP_NAME,
			__( 'plugin has been disabled because it requires PHP version', 'sitecore' ),
			php()->minimum(),
			__( 'or greater. Your system is running PHP version', 'sitecore' ),
			phpversion()
		); ?>
	</div>
<?php

}
