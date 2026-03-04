<?php
/**
 * Custom dashboard panel output
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Widgets
 * @since      1.0.0
 */

use function SiteCore\Core\{
	is_classicpress,
	can_fse
};

/**
 * The customize panel is only available
 * to user who can customize themes.
 */
if ( current_user_can( 'customize' ) || current_user_can( 'edit_theme_options' ) ) {
	$customize = sprintf(
        '<li class="content-tab"><a href="%1s"><span class="dashicons dashicons-art"></span> %2s</a></li>',
		'#customize',
       __( 'Customize', 'sitecore' )
	);
} else {
	$customize = null;
}

/**
 * The system panel is only available
 * to user who can manage options (admins).
 */
if ( is_classicpress() ) {
	$system_icon = 'dashicons-admin-generic';
} else {
	$system_icon = 'dashicons-database';
}
if ( current_user_can( 'manage_options' ) ) {
	$system = sprintf(
        '<li class="content-tab"><a href="%1s"><span class="dashicons %2s"></span> %3s</a></li>',
		'#system',
		$system_icon,
       __( 'System', 'sitecore' )
	);
} else {
	$system = null;
}

$content = sprintf(
	'<li class="content-tab"><a href="%1s"><span class="dashicons dashicons-edit-page"></span> %2s</a></li>',
	'#content',
   __( 'Content', 'sitecore' )
);

$tabs = apply_filters( 'scp_dashboard_panel_tabs', [

    // Welcome tab, initially active.
    sprintf(
        '<li class="content-tab active"><a href="%1s"><span class="dashicons dashicons-welcome-learn-more"></span> %2s</a></li>',
        '#welcome',
        __( 'Welcome', 'sitecore' )
	),
	$content,
	$customize,
	$system
] );

?>
<div id="dashboard-panel" class="dashboard-panel">
	<div class="admin-tabs" data-tabbed="tabbed" data-tabdeeplinking="false">

		<ul class="admin-tabs-list hide-if-no-js">
			<?php echo implode( $tabs ); ?>
		</ul>
		<?php

		// Welcome tab.
		include_once( SCP_PATH . 'views/backend/widgets/dashboard-tabs/welcome-dashboard-tab' . $acf->suffix() . '.php' );

		// Customize tab.
		if ( current_user_can( 'customize' ) || current_user_can( 'edit_theme_options' ) ) {

			if ( can_fse() ) {
				include_once( SCP_PATH . 'views/backend/widgets/dashboard-tabs/fse-dashboard-tab' . $acf->suffix() . '.php' );
			} else {
				include_once( SCP_PATH . 'views/backend/widgets/dashboard-tabs/customize-dashboard-tab' . $acf->suffix() . '.php' );
			}
		}

		// Content tab.
		include_once( SCP_PATH . 'views/backend/widgets/dashboard-tabs/content-dashboard-tab' . $acf->suffix() . '.php' );

		// System tab.
		if ( current_user_can( 'manage_options' ) ) {
			include_once( SCP_PATH . 'views/backend/widgets/dashboard-tabs/system-dashboard-tab' . $acf->suffix() . '.php' );
		}
		?>
	</div>
</div>
