<?php
/**
 * System dashboard tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

use function SiteCore\Core\{
	is_classicpress,
	platform_name,
	platform_version,
};
use SiteCore\System_Summary as Summary;

// Platform avatar.
if ( is_classicpress() ) {
	$avatar = SCP_PATH . 'assets/images/classicpress-avatar.svg';
} else {
	$avatar = SCP_PATH . 'assets/images/wordpress-avatar.svg';
}

// Database icon.
if ( is_classicpress() ) {
	$database_icon = 'dashicons-admin-generic';
} else {
	$database_icon = 'dashicons-database';
}

?>
<div id="system" class="tab-content dashboard-panel-content dashboard-content-system" style="display: none;">

	<h2><?php _e( 'Website & Hosting', 'sitecore' ); ?></h2>
	<p class="description"><?php _e( 'Information about what makes this website run.', 'sitecore' ); ?></p>

	<div class="dashboard-panel-column-container">

		<div class="dashboard-panel-column">

			<h3><?php _e( 'Content Platform', 'sitecore' ); ?></h3>

			<div class="dashboard-panel-section-intro dashboard-panel-system-greeting">

				<figure>
					<a href="<?php echo admin_url( 'about.php' ); ?>" title="<?php echo __( 'About', 'sitecore' ) . ' ' . platform_name(); ?>">
						<?php
						if ( is_readable( $avatar ) ) {
							echo file_get_contents( $avatar );
						} ?>
					</a>
					<figcaption class="screen-reader-text"><?php echo platform_name() . ' ' . __( 'Avatar', 'sitecore' ); ?></figcaption>
				</figure>

				<div>
					<?php printf(
						'<h4>%s %s</h4>',
						platform_name(),
						__( 'Website', 'sitecore' )
					); ?>
					<p class="about-description">
						<?php printf(
							__( 'This website is running version %s of %s.', 'sitecore' ),
							platform_version(),
							platform_name()
						); ?>
					</p>

					<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo esc_url( admin_url( 'about.php' ) ); ?>"><?php _e( 'Release Notes' ); ?></a></p>
					<p class="description">
						<?php printf(
							__( 'Information on the active version of %s.', 'sitecore' ),
							platform_name()
						); ?>
					</p>
				</div>
			</div>
		</div>

		<div class="dashboard-panel-column">

			<h3><?php _e( 'System Overview', 'sitecore' ); ?></h3>

			<ul class="scp-widget-details-list scp-widget-system-list">
				<li><icon class="dashicons dashicons-editor-code"></icon> <?php echo Summary\php_version(); ?></li>

				<li><icon class="dashicons <?php echo $database_icon; ?>"></icon> <?php echo Summary\database_version(); ?></li>

				<?php if ( current_user_can( 'activate_plugins' ) ) : ?>
				<li><icon class="dashicons dashicons-admin-plugins"></icon> <?php echo Summary\count_active_plugins(); ?></li>
				<?php endif; ?>

				<?php if ( current_user_can( 'install_themes' ) || current_user_can( 'customize' ) ) : ?>
				<li><icon class="dashicons dashicons-art"></icon> <?php echo Summary\available_themes(); ?></li>
				<?php endif; ?>

				<li><icon class="dashicons dashicons-admin-appearance"></icon> <?php echo Summary\active_theme(); ?></li>

				<?php
				if ( ! empty( Summary\search_engines() ) ) {
					echo sprintf(
						'<li><icon class="dashicons dashicons-search"></icon> %s</li>',
						Summary\search_engines()
					);
				} ?>
			</ul>
		</div>

		<div class="dashboard-panel-column dashboard-panel-last">

			<h3><?php _e( 'Website Options', 'sitecore' ); ?></h3>

			<ul id="dashboard-website-options">
				<li><a href="<?php echo admin_url( 'options-general.php' ); ?>"><?php _e( 'General Settings', 'sitecore' ); ?></a></li>

				<li><a href="<?php echo admin_url( 'options.php' ); ?>"><?php _e( 'Options Editor', 'sitecore' ); ?></a></li>

				<?php if ( ! get_option( 'disable_site_health', false ) ) : ?>
				<li><a href="<?php echo admin_url( 'site-health.php' ); ?>"><?php _e( 'Site Health', 'sitecore' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
