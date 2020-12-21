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

	// Menu tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#menu',
		esc_html__( 'Menu', SCP_DOMAIN )
	),

	// Dashboard tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#dashboard',
		esc_html__( 'Dashboard', SCP_DOMAIN )
	),

	// Toolbar tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#toolbar',
		esc_html__( 'Toolbar', SCP_DOMAIN )
	),

	// Header tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%3s</a></li>',
		'#header',
		esc_html__( 'Header', SCP_DOMAIN )
	),

	// Footer tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#footer',
		esc_html__( 'Footer', SCP_DOMAIN )
	),

	// Users tab.
	sprintf(
		'<li class="app-tab"><a href="%1s">%2s</a></li>',
		'#users',
		esc_html__( 'Users', SCP_DOMAIN )
	),

];

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

	<form method="post" action="options.php">

		<!-- Begin tabbed content -->
		<div class="app-tabs" data-tabbed="tabbed" data-tabevent="click" data-tabdeeplinking="true">

			<ul class='app-tabs-list app-tabs-horizontal hide-if-no-js'>
				<?php echo implode( $tabs ); ?>
			</ul>

			<!-- Begin content -->
			<div id="menu" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-menu.php'; ?>
			</div>
			<div id="dashboard" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-dashboard.php'; ?>
			</div>
			<div id="toolbar" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-toolbar.php'; ?>
			</div>
			<div id="header" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-header.php'; ?>
			</div>
			<div id="footer" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-footer.php'; ?>
			</div>
			<div id="users" class="app-tab-content">
				<?php include SCP_PATH . 'views/backend/forms/partials/settings-admin-users.php'; ?>
			</div>

			<p class="submit"><?php submit_button( __( 'Save Settings', SCP_DOMAIN ), 'button-primary', '', false, [] ); ?></p>

		</div><!-- End tabbed content -->
	</form>
</div>
