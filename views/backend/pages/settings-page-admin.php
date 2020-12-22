<?php
/**
 * Output of the Administration Settings page
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Admin
 * @since      1.0.0
 */

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
	<form method="post" action="options.php">

		<?php do_action( 'render_screen_tabs' ); ?>

		<p class="submit"><?php submit_button( __( 'Save Settings', SCP_DOMAIN ), 'button-primary', '', false, [] ); ?></p>
	</form>
</div>
