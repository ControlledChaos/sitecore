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

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

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
		__( $this->heading(), SCP_DOMAIN )
	);

	printf(
		'<p class="description">%s</p>',
		__( $this->description(), SCP_DOMAIN )
	);
	?>

	<hr />

	<form method="post" action="options.php">

		<?php do_action( 'render_screen_tabs' ); ?>

		<p class="submit"><?php submit_button( __( 'Save Settings', SCP_DOMAIN ), 'button-primary', '', false, [] ); ?></p>
	</form>
</div>
