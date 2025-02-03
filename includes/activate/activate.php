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
 * @subpackage Includes
 * @category   Activate
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
}

/**
 * Add plugin row notice
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
 * Add PHP disable notice
 *
 * @since  1.0.0
 * @return void
 */
function get_php_notice() {
	add_action( 'pre_current_active_plugins', __NAMESPACE__ . '\php_notice', 9 );
}

/**
 * PHP disable notice
 *
 * @since  1.0.0
 * @return string Returns the markup of the admin notice.
 */
function php_notice() {

?>
	<div id="plugin-php-notice" class="notice notice-error">
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
<?php

}

/**
 * Wordpress[dot]com disable notice
 *
 * @since  1.0.0
 * @return string Returns the markup of the admin notice.
 */
function megalomattic_notice() {

	// Access global variables.
	global $page, $s, $status;

	// Deactivation link.
	$deactivate = 'plugins.php?action=deactivate&amp;plugin=' . urlencode( SCP_BASENAME ) . '&amp;plugin_status=' . $status . '&amp;paged=' . $page . '&amp;s=' . $s;

	$notice = sprintf(
		'%s %s %s',
		__( 'The', 'sitecore' ),
		SCP_NAME,
		__( 'plugin is not allowed to be used for websites hosted by WordPress[dot]com.', 'sitecore' )
	);

	if ( current_user_can( 'deactivate_plugin', SCP_BASENAME ) ) {
		$notice .= sprintf(
			' <a href="%s">%s</a>',
			wp_nonce_url( $deactivate, 'deactivate-plugin_' . SCP_BASENAME ),
			__( 'Deactivate', 'sitecore' )
		);
	}
?>
	<div id="plugin-php-notice" class="notice notice-error">
		<p><?php echo $notice; ?></p>
	</div>
<?php

}

/**
 * Add Wordpress[dot]com disable notice
 *
 * @since  1.0.0
 * @return void
 */
function megalomattic() {
	add_action( 'pre_current_active_plugins', __NAMESPACE__ . '\megalomattic_notice', 9 );
}
