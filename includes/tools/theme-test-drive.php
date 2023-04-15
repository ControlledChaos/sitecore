<?php
/**
 * Theme Test Drive
 *
 * Safely test any theme while visitors view the active theme.
 *
 * @package    Site_Core
 * @subpackage Includes
 * @category   Tools
 * @since      1.0.0
 */

namespace SiteCore\Theme_Test_Drive;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Execute functions
 *
 * @since  1.0.0
 * @return void
 */
function setup() {

	// Return namespaced function.
	$ns = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'admin_menu', $ns( 'add_page' ) );
	add_action( 'plugins_loaded', $ns( 'theme_filters' ) );
}

/**
 * Add theme filters
 *
 * @since  1.0.0
 * @return void
 */
function theme_filters () {
	add_filter( 'template', __NAMESPACE__ . '\get_template' );
	add_filter( 'stylesheet', __NAMESPACE__ . '\get_stylesheet' );
}

/**
 * Add theme page
 *
 * The admin screen for theme test drive.
 *
 * @since  1.0.0
 * @return void
 */
function add_page() {

	$theme_test_drive = add_theme_page(
		__( 'Theme Test Drive Options', 'sitecore' ),
		__( 'Theme Test Drive', 'sitecore' ),
		'edit_theme_options',
		'theme-test-drive',
		__NAMESPACE__ . '\page_markup'
	);
	add_action( 'load-' . $theme_test_drive, __NAMESPACE__ . '\test_drive_help' );
	add_action( 'admin_print_styles-' . $theme_test_drive, __NAMESPACE__ . '\admin_print_styles' );
}

/**
 * Add help tabs
 *
 * Add tabs in the contextual help section.
 *
 * @since  1.0.0
 * @return void
 */
function test_drive_help() {

	$screen = get_current_screen();

	$screen->add_help_tab( [
		'id'       => 'help_ttd_users',
		'title'    => __( 'Logged-In Users', 'sitecore' ),
		'content'  => null,
		'callback' => __NAMESPACE__ . '\help_tab_users'
	] );

	$screen->add_help_tab( [
		'id'       => 'help_ttd_links',
		'title'    => __( 'Preview Links', 'sitecore' ),
		'content'  => null,
		'callback' => __NAMESPACE__ . '\help_tab_links'
	] );
}

/**
 * Users help tab
 *
 * Tab in the contextual help section.
 *
 * @since  1.0.0
 * @return void
 */
function help_tab_users() {

	?>
	<h3 class="screen-reader-text"><?php _e( 'Logged-In Users', 'sitecore' ); ?></h3>

	<p><?php _e( 'Theme test drive is easy to use.' ); ?></p>

	<ol>
		<li><?php _e( 'Select a theme to preview live on the site from the box below (lists all installed themes).', 'sitecore' ); ?></li>
		<li><?php _e( 'Select a user level that can preview the theme test drive.', 'sitecore' ); ?></li>
		<li><?php _e( 'Enable test drive', 'sitecore' ); ?></li>
		<li><?php _e( 'Once the theme is ready for public viewing, disable test drive and enable the theme on the Themes page.', 'sitecore' ); ?></li>
	</ol>

	<p><?php _e( 'Only logged-in users with the minimum access level will be able to see the selected theme in Theme Test Drive. To everyone else the site will display the theme that is activated on the Themes page.', 'sitecore' ); ?></p>

	<?php
}

/**
 * Links help tab
 *
 * Tab in the contextual help section.
 *
 * @todo Update the example URL parameter as needed.
 *
 * @since  1.0.0
 * @return void
 */
function help_tab_links() {

	?>
	<h3 class="screen-reader-text"><?php _e( 'Preview Links', 'sitecore' ); ?></h3>

	<p><?php _e( 'If more than one theme are installed then a list of themes available for preview will display. Each theme name is linked to the front page with that theme previewed.', 'sitecore' ); ?></p>

	<p><?php _e( 'You may add <code>?theme=xxx</code> to any site url, where <code>xxx</code> is the slug of the theme you want to test, to preview that page with the relevant theme. This is especially handy for allowing users not logged in and/or clients to preview a theme in development.', 'sitecore' ); ?></p>
	<?php printf(
		'<p>%s <code>%s</code></p>',
		__( 'Example:', 'sitecore' ),
		esc_url( 'https://example.com/?theme=twentytwentythree' )
	); ?>

	<?php
}

/**
 * Print page styles
 *
 * @since  1.0.0
 * @return void
 */
function admin_print_styles() {

	// Don't need multiple columns for short lists.
	$count   = count( wp_get_themes() );
	$columns = '1';
	if ( $count >= 21 ) {
		$columns = '3';
	} elseif ( $count >= 10 ) {
		$columns = '2';
	}

	$style  = '<!-- Theme test drive page --><style>';
	$style .= '.ttd-install-no-url { font-size: 1rem; }';
	$style .= '.ttd-inline-emphasis { font-weight: 700; }';
	$style .= '.ttd-inline-error { color: #d63638; }';
	$style .= '.theme-test-drive-preview-links { column-count: ' . $columns . '; max-width: 480px; }';
	$style .= '@media screen and ( max-width: 600px ) {';
	$style .= '.theme-test-drive-preview-links { column-count: 1; max-width: 600px; }';
	$style .= '}';
	$style .= '</style>';

	echo apply_filters( 'scp_test_drive_styles', $style );
}

/**
 * Theme installation, loose method
 *
 * @since  1.0.0
 * @param  string $package URL of the theme .zip package.
 * @return void
 */
function handle_theme_loose( $package ) {
	install_theme_loose( $package );
}

/**
 * Theme installation, rigid method
 *
 * @since  1.0.0
 * @param  string $package URL of the theme .zip package.
 * @return void
 */
function handle_theme_rigid( $package ) {
	install_theme_rigid( $package );
}

function install_theme_unzip( $file, $dir ) {

	if ( ! current_user_can( 'edit_files' ) ) {
		_e( 'Sorry, you are not authorized to do this.', 'sitecore' );
		return false;
	}

	if ( ! class_exists( 'PclZip' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
	}

	$unzip = new \PclZip( $file );
	$list  = $unzip->properties();
	if ( ! $list['nb'] ) {
		return false;
	}
	// echo "Number of files in archive : ".$list['nb']."<br />";

	_e( 'Copying the files<br />', 'sitecore' );

	$result = $unzip->extract( PCLZIP_OPT_PATH, $dir );
	if ( $result == 0 ) {
		printf(
			__( 'Could not unarchive the file: %s <br />', 'sitecore' ),
			$unzip->errorInfo( true )
		);
		return false;

	} else {
		//print_r($result);
		foreach ( $result as $item ) {
			if ( $item['status'] != 'ok' ) {
				echo $item['stored_filename'] . ' ... ' . $item['status'] . '<br />';
			}
		}
		return true;
	}
}

function install_theme_loose( $package ) {

	printf(
		'%s %s<br />',
		__( 'Downloading the theme from', 'sitecore' ),
		$package
	);
	$file = download_url( $package );

	if ( is_wp_error( $file ) ) {

		printf(
			'%s %s',
			__( 'Download failed:', 'sitecore' ),
			$file->get_error_message()
		);
		return;
	}

	_e( 'Unpacking the theme<br />', 'sitecore' );

	// Unzip theme to theme directory.
	$result = install_theme_unzip( $file, ABSPATH . 'wp-content/themes/' );

	// Once extracted, delete the package.
	unlink( $file );

	if ( $result ) {
		_e( '<br />Theme installed successfully.<br />Refresh page to view in test drive.<br />', 'sitecore' );
	} else {
		printf(
			__( '<br />Error installing the theme. <br />You can try installing the theme manually: <a href="%s">%s</a><br />', 'sitecore' ),
			$package,
			$package
		);
	}
	return;
}

function install_theme_rigid( $package ) {

	global $wp_filesystem;

	if ( ! $wp_filesystem || ! is_object( $wp_filesystem ) ) {
		WP_Filesystem( $credentials );
	}

	if ( ! is_object( $wp_filesystem ) ) {
		_e( '<strong><em>Could not access filesystem.</strong></em><br />', 'sitecore' );
		return;
	}

	if ( $wp_filesystem->errors->get_error_code() ) {
		printf(
			'<strong><em>%s %s</strong></em><br />',
			__( 'Filesystem error:', 'sitecore' ),
			$wp_filesystem->errors->get_error_message()
		);
		return;
	}

	//Get the Base folder
	$base = $wp_filesystem->get_base_dir();
	if ( empty( $base ) ) {
		_e( '<strong><em>Unable to locate WordPress directory.</strong></em><br />', 'sitecore' );
		return;
	}

	printf(
		'Downloading theme file from %s<br />',
		$package
	);
	$file = download_url( $package );

	if ( is_wp_error( $file ) ) {
		printf(
			'<strong><em>%s %s</strong></em><br />',
			__( 'Download failed:', 'sitecore' ),
			$file->get_error_message()
		);
		return;
	}

	$working_dir = $base . 'wp-content/upgrade/themes';

	// Clean up working directory.
	if ( $wp_filesystem->is_dir( $working_dir ) ) {
		$wp_filesystem->delete( $working_dir, true );
	}

	_e( 'Unpacking the theme<br />', 'sitecore' );

	// Unzip package to theme directory.
	$result = unzip_file( $file, $working_dir );
	if ( is_wp_error( $result ) ) {

		unlink( $file );
		$wp_filesystem->delete( $working_dir, true );
		printf(
			'<strong><em>%s %s</strong></em><br />',
			__( 'Unpack failed:', 'sitecore' ),
			$result->get_error_message()
		);
		return;
	}

	_e( 'Installing the theme<br />', 'sitecore' );

	// Copy new version of plugin into place.
	if ( ! copy_dir( $working_dir, $base . 'wp-content/themes' ) ) {

		$wp_filesystem->delete( $working_dir, true );
		_e( '<strong><em>Installation failed (theme already installed?)</strong></em><br />', 'sitecore' );
		return;
	}

	// Get a list of the directories in the working directory before we delete it, We need to know the new folder for the plugin.
	$filelist = array_keys( $wp_filesystem->dirlist( $working_dir ) );

	// Remove working directory.
	$wp_filesystem->delete( $working_dir, true );

	// Once extracted, delete the package.
	unlink( $file );

	_e( '<br />Theme installed successfully.<br />Refresh page to view in test drive.<br />', 'sitecore' );
	return;
}

function get_theme() {

	$get_theme = get_option( 'td_themes' );

	if ( ! empty( $get_theme ) ) {
		return $get_theme;
	} else {
		return '';
	}
}

function get_level() {

	$get_level = get_option( 'td_level' );

	if ( $get_level != '' ) {
		return 'level_' . $get_level;
	} else {
		return 'level_10';
	}
}

function determine_theme() {

	if ( ! isset( $_GET['theme'] ) ) {

		if ( ! current_user_can( get_level() ) ) {
			return false;

		} elseif ( current_user_can( get_level() ) && 1 == get_current_user_id() ) {

			$theme = get_theme();
			if ( $theme == '' ) {
				return false;
			}
		} else {
			$theme = get_theme();
			if ( $theme == '' ) {
				return false;
			}
		}
	}

	$all = $_GET + $_POST;
	if ( isset( $all['theme'] ) ) {
		$theme = $all['theme'];
	}

	$theme_data = wp_get_theme( $theme );

	if ( ! empty( $theme_data ) ) {

		// Don't let people peek at unpublished themes.
		if ( isset( $theme_data['Status'] ) && $theme_data['Status'] != 'publish' ) {
			return false;
		}
		return $theme_data;
	}

	// perhaps they are using the theme directory instead of title.
	$themes = wp_get_themes();

	foreach ( $themes as $theme_data ) {

		// use Stylesheet as it's unique to the theme - Template could point to another theme's templates.
		if ( $theme_data['Stylesheet'] == $theme ) {

			// Don't let people peek at unpublished themes.
			if ( isset( $theme_data['Status'] ) && $theme_data['Status'] != 'publish' ) {
				return false;
			}
			return $theme_data;
		}
	}
	return false;
}

function get_template( $template ) {

	$theme = determine_theme();
	if ( $theme === false ) {
		return $template;
	}
	return $theme['Template'];
}

function get_stylesheet( $stylesheet ) {

	$theme = determine_theme();
	if ( $theme === false ) {
		return $stylesheet;
	}
	return $theme['Stylesheet'];
}

function theme_links() {

	$themes = wp_get_themes();
	$links  = '';

	if ( count( $themes ) > 1 ) {

		$theme_names = array_keys( $themes );
		natcasesort( $theme_names );

		$links = '<ul class="theme-test-drive-preview-links">';

		foreach ( $theme_names as $theme_name ) {

			// Skip unpublished themes.
			if ( isset( $themes[$theme_name]['Status'] ) && $themes[$theme_name]['Status'] != 'publish' ) {
				continue;
			}

			$links .= sprintf(
				'<li><a href="%s?theme=%s" target="_blank" rel="noindex nofollow">%s</a></li>',
				trailingslashit( get_option( 'siteurl' ) ),
				$theme_name,
				$themes[$theme_name]['Name']
			);
		}
		$links .= '</ul>';
	}
	return $links;
}

function theme_select() {

	$themes = wp_get_themes();
	$active = wp_get_theme();

	if ( count( $themes ) > 1 ) {

		$theme_names = array_keys( $themes );
		natcasesort( $theme_names );

		$select  = '<label for="td_themes">';
		$select .= '<select id="td_themes" name="td_themes">' . "\n";

		foreach ( $theme_names as $theme_name ) {

			// Skip unpublished themes.
			if ( isset( $themes[$theme_name]['Status'] ) && $themes[$theme_name]['Status'] != 'publish' ) {
				continue;
			}

			if ( ( get_theme() == $theme_name ) || ( ( get_theme() == '' ) && ( $theme_name == $active ) ) ) {

				$select .= '<option value="' . esc_attr( $theme_name ) . '" selected="selected">' . $themes[$theme_name]['Name'] . '</option>';

			} else {
				$select .= '<option value="' . esc_attr( $theme_name ) . '">' . $themes[$theme_name]['Name'] . '</option>';
			}
		}
		$select .= '</select> ';
	}

	$select .= __( 'Select from installed themes', 'sitecore' );
	$select .= '</label>';

	echo $select;
}

/**
 * Test drive enabled
 *
 * @since  1.0.0
 * @return boolean Returns true if theme test drive is enabled.
 */
function test_drive_enabled() {

	$option = get_option( 'td_themes' );
	if ( $option ) {
		return true;
	}
	return false;
}

/**
 * Test drive admin page
 *
 * @since  1.0.0
 * @return void
 */
function page_markup() {

	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! wp_verify_nonce( @$_POST['_wpnonce'], 'theme-drive' ) ) {
		$die = __( 'Nonce invalid. Please re-submit the form.', 'sitecore' );
		wp_die( $die );
		exit;
	}

	$enabled  = wp_get_theme( get_option( 'td_themes' ) );
	$disabled = '';

	if ( test_drive_enabled() ) {
		$enable_button  = __( 'Update Test Drive', 'sitecore' );
		$disable_button = __( 'Disable Test Drive', 'sitecore' );
	} else {
		$enable_button  = __( 'Enable Test Drive', 'sitecore' );
		$disable_button = __( 'Test Drive Disabled', 'sitecore' );
		$disabled = 'disabled';
	}

	$access_level = get_option( 'td_level' );

	// If the enable/update input is submitted.
	if ( isset( $_POST['button'] ) && $enable_button == $_POST['button'] ) {

		check_admin_referer( 'theme-drive' );
		$get_option = $_POST['td_themes'];
		update_option( 'td_themes', $get_option );

		$enable_button  = __( 'Update Test Drive', 'sitecore' );
		$disable_button = __( 'Disable Test Drive', 'sitecore' );
		$enabled  = wp_get_theme( $get_option );
		$disabled = '';

		$access_level = (int)$_POST['access_level'];
		update_option( 'td_level', $access_level );

		$access_users = __( 'administrators', 'sitecore' );
		if ( '7' == $access_level ) {
			$access_users = __( 'editors and up', 'sitecore' );
		} elseif ( '2' == $access_level ) {
			$access_users = __( 'authors and up', 'sitecore' );
		} elseif ( '1' == $access_level ) {
			$access_users = __( 'contributors and up', 'sitecore' );
		}

		$action_message = sprintf(
			__( 'Theme Test Drive enabled for %s with %s.', 'sitecore' ),
			ucfirst( $access_users ),
			$enabled->get( 'Name' )
		);
		printf(
			'<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>',
			$action_message
		);

	// If the disable input is submitted.
	} elseif ( isset( $_POST['button'] ) && $disable_button == $_POST['button'] ) {

		check_admin_referer( 'theme-drive' );
		delete_option( 'td_themes' );
		delete_option( 'td_level' );

		$enable_button  = __( 'Enable Test Drive', 'sitecore' );
		$disable_button = __( 'Test Drive Disabled', 'sitecore' );
		$action_message = __( 'Theme Test Drive has been disabled.', 'sitecore' );
		printf(
			'<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>',
			$action_message
		);
		$disabled = 'disabled';
	}

	$access_level = get_option( 'td_level' );
	if ( empty( $access_level ) ) {
		$access_level = '10';
	}

	$action_url;
	if ( ! isset( $action_url ) ) {
		$action_url = '';
	}

?>
<div class="wrap" >

	<h1><?php _e( 'Theme Test Drive', 'sitecore' ); ?></h1>
	<p class="description"><?php _e( 'Safely test any theme while visitors view the active theme.', 'sitecore' ); ?></p>

	<form method="post" action="<?php echo $action_url; ?>" novalidate="novalidate">
		<?php wp_nonce_field( 'theme-drive' ); ?>

		<?php if ( isset( $_POST['theme_install_loose'] ) ) {
			printf(
				'<h2>%s</h2>',
				__( 'Theme Installation Results', 'sitecore' )
			);

			if ( ! isset( $_POST['install_theme'] ) ) {
				$install_theme_loose = '';
			} else {
				$install_theme_loose = $_POST['install_theme'];
			}

			if ( $install_theme_loose != '' ) {
				handle_theme_loose( $install_theme_loose );
			} else {
				_e( '<p class="ttd-install-no-url"><span class="ttd-inline-emphasis ttd-inline-error">Error:</span> No theme URL specified.</p>', 'sitecore' );
			}
		} ?>

		<?php
		// Theme links only if more than one installed theme.
		if ( count( wp_get_themes() ) > 1 ) :

		?>
		<h2><?php _e( 'Theme Preview Links', 'sitecore' ); ?></h2>

		<p><?php _e( 'The following list of installed themes are linked to a preview URL to view this site with the corresponding theme. See page help tab for details.', 'sitecore' ); ?></p>

		<?php echo theme_links(); ?>
		<?php endif; // count( wp_get_themes() ) ?>

		<h2><?php _e( 'Select Test Theme', 'sitecore' ); ?></h2>

		<?php if ( test_drive_enabled() ) {
			printf(
				__( '<p>Test drive is enabled with %s.</p>', 'sitecore' ),
				wp_get_theme( $enabled )
			);
		} else {
			printf(
				__( '<p>The active/public theme is %s.</p>', 'sitecore' ),
				wp_get_theme()
			);
		} ?>

		<p><?php _e( 'Choose a theme to display to logged-in users.', 'sitecore' ); ?></p>
		<?php theme_select(); ?>

		<h2><?php _e( 'Access Level', 'sitecore' ); ?></h2>

		<p><?php _e( 'Specify the level of users to have access to the selected theme preview. Default is level 10.', 'sitecore' ); ?></p>
		<ul>
			<li><?php _e( '10: Administrators', 'sitecore' ); ?></li>
			<li><?php _e( '7: Editors', 'sitecore' ); ?></li>
			<li><?php _e( '2: Authors', 'sitecore' ); ?></li>
			<li><?php _e( '1: Contributors', 'sitecore' ); ?></li>
		</ul>
		<p><?php _e( 'The access level is ignored when accessing the site with the preview URL parameter.', 'sitecore' ); ?></p>

		<p>
			<label for="access_level">
				<input type="text" name="access_level" id="access_level" value="<?php echo esc_attr( $access_level ); ?>" size="2" maxlength="2" placeholder="10" /> <?php _e( 'Access level', 'sitecore' ); ?>
			</label>
		</p>

		<p class="submit">
			<input type="submit" name="button" value="<?php echo $enable_button; ?>" class="button button-primary" />
			<input type="submit" name="button" value="<?php echo $disable_button; ?>" class="button" <?php echo $disabled; ?> />
		</p>


		<h2><?php _e( 'Easy Theme Installation', 'sitecore' ); ?></h2>

		<p><?php _e( 'Enter the URL to the theme <code>.zip</code> file then install.', 'sitecore' ); ?></p>
		<p>
			<input type="text" name="install_theme" id="install_theme" value="" />
			<input class="button" type="submit" name="theme_install_loose" value="<?php _e( 'Install Theme', 'sitecore' ); ?>" class="button-primary" />
		</p>
	</form>
</div>
<?php

}
