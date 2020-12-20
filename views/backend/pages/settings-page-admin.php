<?php
/**
 * Output of the Administration Settings page
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Admin
 * @since      1.0.0
 */

use SiteCore\Classes\Admin as Admin;

// Instance of the Admin_Settings_Page class.
$page = new Admin\Admin_Settings_Page;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Get plugin data.
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data( __FILE__ );
$plugin_name = $plugin_data['Name'];

/**
 * Site Settings tab icon.
 *
 * The Site Settings page has options to make the page top-level in
 * the admin menu and set a Dashicons icon. If an icon has been set
 * for the link in the admin menu then we will use the same icon here
 * for the Site Settings tab.
 *
 * @since  1.0.0
 * @return void
 */

/**
 * Set up the page tabs as an array for adding tabs
 * from another plugin or from a theme.
 *
 * @since  1.0.0
 * @return array
 */
$tabs = [

	// Introduction tab.
	sprintf(
		'<li><a href="%1s"><span class="dashicons dashicons-welcome-learn-more"></span> %2s</a></li>',
		'#intro',
		esc_html__( 'Introduction', SCP_DOMAIN )
	),

	// Projects tab.
	sprintf(
		'<li><a href="%1s"><span class="dashicons dashicons-art"></span> %2s</a></li>',
		'#projects',
		esc_html__( 'Projects', SCP_DOMAIN )
	),

	// Images tab.
	sprintf(
		'<li><a href="%1s"><span class="dashicons dashicons-format-gallery"></span> %3s</a></li>',
		'#images',
		esc_html__( 'Images', SCP_DOMAIN )
	),

	// Videos tab.
	sprintf(
		'<li><a href="%1s"><span class="dashicons dashicons-format-video"></span> %2s</a></li>',
		'#videos',
		esc_html__( 'Videos', SCP_DOMAIN )
	),

	// Settings tab.
	sprintf(
		'<li><a href="%1s"><span class="dashicons dashicons-admin-generic"></span> %2s</a></li>',
		'#settings',
		esc_html__( 'Settings', SCP_DOMAIN )
	),

];

// Apply a filter to the tabs array for adding tabs.
$page_tabs = apply_filters( 'scp_tabs_page_about', $tabs );

?>
<div class="wrap admin-settings">

	<?php
	printf(
		'<h1>%s</h1>',
		__( $page->heading(), SCP_DOMAIN )
	);

	printf(
		'<p class="description">%s</p>',
		__( $page->description(), SCP_DOMAIN )
	);
	?>

	<hr />

	<!-- Begin jQuery tabbed content -->
	<div class="backend-tabbed-content">

		<ul class="ui-tabs-nav">
			<?php echo implode( $page_tabs ); ?>
		</ul>

		<?php // Hook for adding tabbed content.
		do_action( 'scp_content_page_about_before' ); ?>

		<!-- Begin content -->
		<div id="intro"><!-- Introduction content -->
			<?php // include_once SCP_PATH . 'admin/partials/plugin-page-intro.php'; ?>
		</div>
		<div id="projects"><!-- Media Options content -->
			<?php // include_once SCP_PATH . 'admin/partials/plugin-page-projects.php'; ?>
		</div>
		<div id="images"><!-- Images content -->
			<?php // include_once SCP_PATH . 'admin/partials/plugin-page-images.php'; ?>
		</div>
		<div id="videos"><!-- Videos content -->
			<?php // include_once SCP_PATH . 'admin/partials//plugin-page-videos.php'; ?>
		</div>
		<div id="settings"><!-- Dev Tools content -->
			<?php // include_once SCP_PATH . 'admin/partials/plugin-page-settings.php'; ?>
		</div>
		<?php

		// Hook for adding tabbed content.
		do_action( 'scp_content_page_about_after' ); ?>

	</div><!-- End jQuery tabbed content -->
</div>