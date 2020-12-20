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
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#menu',
		esc_html__( 'Menu', SCP_DOMAIN )
	),

	// Projects tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#dashboard',
		esc_html__( 'Dashboard', SCP_DOMAIN )
	),

	// Images tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%3s</a></li>',
		'#header',
		esc_html__( 'Header', SCP_DOMAIN )
	),

	// Videos tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#footer',
		esc_html__( 'Footer', SCP_DOMAIN )
	),

	// Settings tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#users',
		esc_html__( 'Users', SCP_DOMAIN )
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
	<div class="app-tabs" data-tabbed="tabbed" data-tabevent="click" data-tabdeeplinking="false">

		<ul class='app-tabs-list app-tabs-horizontal hide-if-no-js'>
			<?php echo implode( $page_tabs ); ?>
		</ul>

		<?php // Hook for adding tabbed content.
		do_action( 'scp_content_page_about_before' ); ?>

		<!-- Begin content -->
		<div id="menu" class="app-tab-content"><!-- Introduction content -->
			<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-menu.php'; ?>
		</div>
		<div id="dashboard" class="app-tab-content"><!-- Media Options content -->
			<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-dashboard.php'; ?>
		</div>
		<div id="header" class="app-tab-content"><!-- Images content -->
			<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-header.php'; ?>
		</div>
		<div id="footer" class="app-tab-content"><!-- Videos content -->
			<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-footer.php'; ?>
		</div>
		<div id="users" class="app-tab-content"><!-- Dev Tools content -->
			<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-users.php'; ?>
		</div>
		<?php

		// Hook for adding tabbed content.
		do_action( 'scp_content_page_about_after' ); ?>

	</div><!-- End jQuery tabbed content -->
</div>