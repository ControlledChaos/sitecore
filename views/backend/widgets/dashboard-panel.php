<?php
/**
 * Custom dashboard panel output
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

// Get the current user data for the greeting.
$current_user = wp_get_current_user();
$user_id      = get_current_user_id();
$user_name    = $current_user->display_name;
$avatar       = get_avatar(
	$user_id,
	64,
	'',
	$current_user->display_name,
	[
		'class'         => 'welcome-panel-avatar alignnone',
		'force_display' => true
		]
);

?>
<div class="welcome-panel welcome-panel-custom">
	<div class="dashboard-content-wrapper">
		<div id="dashboard-get-started" class="welcome-panel-column">
			<h3><?php _e( 'Get Started', 'sitecore' ); ?></h3>
			<div class="dashboard-panel-section-intro dashboard-panel-user-greeting">

				<figure>
					<a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>">
						<?php echo $avatar; ?>
					</a>
					<figcaption class="screen-reader-text"><?php echo $user_name; ?></figcaption>
				</figure>

				<div>
					<?php echo sprintf(
						'<h4>%1s %2s.</h4>',
						esc_html__( 'Howdy,', 'sitecore' ),
						$user_name
					); ?>
					<p><?php _e( 'This site may display your profile in posts that you author, and it offers user-defined color schemes.', 'sitecore' ); ?></p>
					<p class="dashboard-panel-call-to-action"><a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php _e( 'Manage Your Profile' ); ?></a></p>
					<p class="description"><?php _e( 'Edit your display name & bio.', 'sitecore' ); ?></p>
				</div>

			</div>
		</div>
	</div>
</div>