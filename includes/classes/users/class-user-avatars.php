<?php
/**
 * Add an avatar upload field to user profiles.
 *
 * Also provides front-end avatar management via a shortcode and bbPress support.
 *
 * @todo Provide default avatar upload interface on the
 * Discussion Settings page.
 *
 * @todo Better upload & remove interface on profile edit screens.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\Classes\Users;
use SiteCore\Classes as Classes;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Add an avatar upload field to user profiles.
 *
 * @since  1.0.0
 * @access public
 */
class User_Avatars {

	/**
	 * User ID.
	 *
	 * @since 1.0.0
	 * @var int
	 * @access private
	 */
	private $user_id_being_edited;

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		// Print admin styles to head.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ], 20 );

		// Print admin scripts to head.
		add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ], 20 );

		// Avatar upload capability.
		add_action( 'admin_init', [ $this, 'capability' ] );

		// Add avatar upload form to profile screens.
		add_action( 'show_user_profile', [ $this, 'user_avatar_form' ], 9 );
		add_action( 'edit_user_profile', [ $this, 'user_avatar_form' ], 9 );

		// Update profile with new avatar.
		add_action( 'personal_options_update', [ $this, 'edit_user_profile_update' ], 9 );
		add_action( 'edit_user_profile_update', [ $this, 'edit_user_profile_update' ], 9 );

		// Add bbPress forum support.
		add_action( 'bbp_user_edit_after_about', [ $this, 'bbpress_user_profile' ] );

		// Disable the connection to Gravater.
		add_filter( 'get_avatar', [ $this, 'disable_gravatar' ], 9, 5 );
		add_filter( 'get_avatar', [ $this, 'get_avatar' ], 10, 5 );

		// Update options.
		add_action( 'plugins_loaded', [ $this, 'avatar_default' ] );

		// New default avatars.
		add_filter( 'avatar_defaults', [ $this, 'avatar_defaults' ], 10, 1 );
	}

	/**
	 * Print styles
	 *
	 * @since  1.0.0
	 * @access public
	 * @global $pagenow Get the current admin screen.
	 * @return string Returns one or more style blocks.
	 */
	public function admin_print_styles() {

		// Access current admin page.
		global $pagenow;

		// Print styles only on the discussion settings page.
		if ( 'options-discussion.php' == $pagenow ) :
		?>
		<style>
		.form-table td.defaultavatarpicker {
			display: block;
			max-width: 1024px;
			padding: 15px 0;
		}

		.defaultavatarpicker p,
		.defaultavatarpicker br {
			display: none;
		}

		.defaultavatarpicker label {
			display: block !important;
			width: 25%;
			float: left;
		}

		.defaultavatarpicker .avatar {
			display: inline-block;
			border: 1px solid #ccc;
			border-radius: 50%;
			/* The background image is for the blank avatar to display something. */
			background-image: url( <?php echo SCP_URL . 'assets/images/checker-bg.png' ?> );
			background-size: contain;
		}

		@media screen and ( max-width: 1082px ) {
			.defaultavatarpicker label {
				width: 33.33325%;
				width: calc( 100% / 3 );
			}
		}

		@media screen and ( max-width: 570px ) {
			.defaultavatarpicker label {
				width: 50%;
			}
		}
		</style>
		<?php
		endif; // If options-discussion.php.

		// Print styles only on the profile and user edit pages.
		if ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) :

		?>
		<style>
		.user-profile-picture {
			display: none;
		}

		.avatar-upload-button input {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
		}
		</style>
		<?php
		endif; // If profile.php or user-edit.php.
	}

	/**
	 * Print scripts
	 *
	 * @since  1.0.0
	 * @access public
	 * @global $pagenow Get the current admin screen.
	 * @return string Returns one or more script blocks.
	 */
	public function admin_print_scripts() {

		// Access current admin page.
		global $pagenow;

		// Print styles only on the discussion settings page.
		if ( 'options-discussion.php' == $pagenow ) :
		?>
		<script>
		jQuery(document).ready( function ($) {
			$( 'td.defaultavatarpicker p' ).remove();
		});
		</script>
		<?php
		endif; // If options-discussion.php.
	}

	/**
	 * Upload capability
	 *
	 * Only allow users with file upload capabilities to upload local avatars.
	 *
	 * @todo Create multi-checkbox field of available user roles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function capability() {

		add_settings_field(
			'uap_user_avatars_caps',
			__( 'Avatar Upload Permission',	'sitecore' ),
			[ $this, 'capability_field' ],
			'discussion',
			'avatars',
			[ esc_html__( 'Only allow users with file upload capabilities to upload local avatars (Authors and above).', 'sitecore' ) ]
		);

		register_setting(
			'discussion',
			'uap_user_avatars_caps'
		);
	}

	/**
	 * Upload capability settings field
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns the field markup.
	 */
	public function capability_field( $args ) {

		$option = get_option( 'uap_user_avatars_caps' );

		$html = '<p><input type="checkbox" id="uap_user_avatars_caps" name="uap_user_avatars_caps" value="1" ' . checked( 1, $option, false ) . '/>';

		$html .= '<label for="uap_user_avatars_caps"> ' . $args[0] . '</label></p>';

		echo $html;
	}

	/**
	 * Get avatar
	 *
	 * Filters the avatar markup.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $avatar
	 * @param  int/string/object $id_or_email
	 * @param  int $size
	 * @param  string $default
	 * @param  boolean $alt
	 * @return string Returns the avatar markup.
	 */
	public function get_avatar( $avatar = '', $id_or_email = '', $size = 48, $default = '', $alt = false ) {

		// Determine if we receive an ID or string.
		if ( is_numeric( $id_or_email ) ) {
			$user_id = (int) $id_or_email;

		} elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
			$user_id = $user->ID;

		} elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
			$user_id = (int) $id_or_email->user_id;
		}

		if ( empty( $user_id ) ) {
			return $avatar;
		}

		$local_avatars = get_user_meta( $user_id, 'uap_user_avatar', true );

		if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) ) {
			return $avatar;
		}

		$size = (int) $size;

		if ( empty( $alt ) ) {
			$alt = get_the_author_meta( 'display_name', $user_id );
		}

		// Generate a new size.
		if ( empty( $local_avatars[$size] ) ) {

			$upload_path      = wp_upload_dir();
			$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
			$image            = wp_get_image_editor( $avatar_full_path );

			if ( ! is_wp_error( $image ) ) {
				$image->resize( $size, $size, true );
				$image_sized = $image->save();
			}

			if ( is_wp_error( $image_sized ) ) {
				$local_avatars[$size] = $local_avatars[$size] = $local_avatars['full'];
			} else {
				$local_avatars[$size] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
			}

			// Save updated avatar sizes
			update_user_meta( $user_id, 'uap_user_avatar', $local_avatars );

		} elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
			$local_avatars[$size] = home_url( $local_avatars[$size] );
		}

		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

		return apply_filters( 'uap_user_avatar', $avatar );
	}

	/**
	 * User avatar form
	 *
	 * Form to display on the user profile edit screen.
	 *
	 * @todo Avatar preview after selection from device, before update.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $profileuser
	 * @return mixed Returns the form markup.
	 */
	public function user_avatar_form( $profileuser ) {

		// bbPress will try to auto-add this to user profiles - don't let it.
		// Instead we hook our own proper function that displays cleaner.
		if ( function_exists( 'is_bbpress') && is_bbpress() ) {
			return;
		}

		?>
		<h2><?php _e( 'User Avatar', 'sitecore' ); ?></h2>

		<table id="avatar-profile-screen" class="form-table">
			<tbody>
				<tr>
					<th><label for="basic-user-avatar"><?php _e( 'Upload or Delete', 'sitecore' ); ?></label></th>
					<td style="width: 50px;" valign="top">
						<?php echo get_avatar( $profileuser->ID ); ?>
					</td>
					<td>
					<?php
					$options = get_option( 'uap_user_avatars_caps' );

					if ( empty( $options ) || current_user_can( 'upload_files' ) ) :

						// Nonce security.
						wp_nonce_field( 'uap_user_avatar_nonce', '_uap_user_avatar_nonce', false );

						// File upload input.
						$upload = sprintf(
							'<label class="not-button avatar-upload-button" for="basic-user-avatar"><input class="not-screen-reader-text" type="file" name="basic-user-avatar" id="basic-user-avatar" aria-label="%s" /><span class="screen-reader-text">%s</span></label>',
							__( 'Upload Avatar', 'sitecore' ),
							__( 'Upload Avatar', 'sitecore' )
						);
						echo "<p>{$upload}</p>";

						if ( empty( $profileuser->uap_user_avatar ) ) {

							printf(
								'<p class="description">%s</p>',
								__( 'No user avatar is set. Use the upload button to add an avatar.', 'sitecore' )
							);

						} else {

							$delete = sprintf(
								'<label for="basic-user-avatar-erase"><input type="checkbox" name="basic-user-avatar-erase" value="1" /> %s</label>',
								__( 'Delete local avatar', 'sitecore' )
							);
							echo "<p>{$delete}</p>";

							printf(
								'<p class="description">%s</p>',
								__( 'Replace the avatar by uploading a new avatar or erase the current avatar by checking the delete option.', 'sitecore' )
							);
						}

					else :
						if ( empty( $profileuser->uap_user_avatar ) ) {
							printf(
								'<p class="description">%s</p>',
								__( 'You do not have permission to upload an avatar.', 'sitecore' )
							);

						} else {
							printf(
								'<p class="description">%s</p>',
								__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'sitecore' )
							);
						}
					endif;
					?>
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			var form = document.getElementById( 'your-profile' );
			form.encoding = 'multipart/form-data';
			form.setAttribute( 'enctype', 'multipart/form-data' );
		</script>
		<?php
	}

	/**
	 * Update avatar
	 *
	 * Updates the user's avatar setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param int $user_id
	 * @return mixed
	 */
	public function edit_user_profile_update( $user_id ) {

		// Check for nonce otherwise bail.
		if ( ! isset( $_POST['_uap_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_uap_user_avatar_nonce'], 'uap_user_avatar_nonce' ) ) {
			return;
		}

		if ( ! empty( $_FILES['basic-user-avatar']['name'] ) ) {

			// Allowed file extensions/types.
			$mimes = [
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			];

			// Front end support - shortcode, bbPress, etc.
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			// Delete old images if successful.
			$this->avatar_delete( $user_id );

			// Need to be more secure since low privelege users can upload.
			if ( strstr( $_FILES['basic-user-avatar']['name'], '.php' ) ) {
				wp_die( 'For security reasons, the extension ".php" cannot be in your file name.' );
			}

			// Make user_id known to unique_filename_callback function.
			$this->user_id_being_edited = $user_id;
			$avatar = wp_handle_upload( $_FILES['basic-user-avatar'], [ 'mimes' => $mimes, 'test_form' => false, 'unique_filename_callback' => [ $this, 'unique_filename_callback' ] ] );

			// Handle failures.
			if ( empty( $avatar['file'] ) ) {

				switch ( $avatar['error'] ) {

					case 'File type does not meet security guidelines. Try another.' :
						add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error",__("Please upload a valid image file for the avatar.","basic-user-avatars"));' ) );
						break;

					default :
						add_action( 'user_profile_update_errors', create_function( '$a', '$a->add("avatar_error","<strong>".__("There was an error uploading the avatar:","basic-user-avatars")."</strong> ' . esc_attr( $avatar['error'] ) . '");' ) );
				}
				return;
			}

			// Save user information, overwriting previous.
			update_user_meta( $user_id, 'uap_user_avatar', [ 'full' => $avatar['url'] ] );

		} elseif ( ! empty( $_POST['basic-user-avatar-erase'] ) ) {
			$this->avatar_delete( $user_id );
		}
	}

	/**
	 * bbPress support
	 *
	 * Form to display on the bbPress user profile edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function bbpress_user_profile() {

		if ( ! bbp_is_user_home_edit() ) {
			return;
		}

		$user_id     = get_current_user_id();
		$profileuser = get_userdata( $user_id );

		echo '<div id="avatar-profile-screen">';
		echo '<label for="basic-local-avatar">' . __( 'Avatar', 'sitecore' ) . '</label>';
		echo '<fieldset class="bbp-form avatar">';

	 			echo get_avatar( $profileuser->ID );
				$options = get_option( 'uap_user_avatars_caps' );

				if ( empty( $options ) || current_user_can( 'upload_files' ) ) :

					// Nonce security.
					wp_nonce_field( 'uap_user_avatar_nonce', '_uap_user_avatar_nonce', false );

					// File upload input.
					$upload = sprintf(
						'<label class="not-button avatar-upload-button" for="basic-user-avatar"><input class="not-screen-reader-text" type="file" name="basic-user-avatar" id="basic-user-avatar" aria-label="%s" /><span class="screen-reader-text">%s</span></label>',
						__( 'Upload Avatar', 'sitecore' ),
						__( 'Upload Avatar', 'sitecore' )
					);
					echo "<p>{$upload}</p>";

					if ( empty( $profileuser->uap_user_avatar ) ) {

						printf(
							'<p class="description">%s</p>',
							__( 'No user avatar is set. Use the upload button to add an avatar.', 'sitecore' )
						);

					} else {

						$delete = sprintf(
							'<label for="basic-user-avatar-erase"><input type="checkbox" name="basic-user-avatar-erase" value="1" /> %s</label>',
							__( 'Delete local avatar', 'sitecore' )
						);
						echo "<p>{$delete}</p>";

						printf(
							'<p class="description">%s</p>',
							__( 'Replace the avatar by uploading a new avatar or erase the current avatar by checking the delete option.', 'sitecore' )
						);
					}

				else :

					if ( empty( $profileuser->uap_user_avatar ) ) {
						printf(
							'<p class="description">%s</p>',
							__( 'You do not have permission to upload an avatar.', 'sitecore' )
						);

					} else {
						printf(
							'<p class="description">%s</p>',
							__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'sitecore' )
						);
					}
				endif;

		echo '</fieldset>';
		echo '</div>';
		?>
		<script type="text/javascript">var form = document.getElementById('bbp-your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
		<?php
	}

	/**
	 * Disable Gravatar
	 *
	 * Replaces the gravatar.com URL for the local server.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $avatar
	 * @param  integer $id_or_email
	 * @param  string $size
	 * @param  string $default
	 * @param  string $alt
	 * @return array
	 */
	function disable_gravatar( $avatar, $id_or_email, $default, $alt ) {

		$localhost = array( 'localhost', '127.0.0.1' );
		$size      = (int) 48;

		if ( ! in_array( $_SERVER['SERVER_ADDR'] , $localhost ) ) {
			return $avatar;
		}

		$document = new \DOMDocument;
		$document->loadHTML( $avatar );

		$images = $document->getElementsByTagName( 'img' );

		if ( $images && $images->length > 0 ) {
			$url_1  = urldecode( $images->item(0)->getAttribute( 'src' ) );
			$url_2  = explode( 'd=', $url_1 );
			if ( is_array( $url_2 ) && isset( $url_2[1] ) ) {
				$url_3 = explode( '&', $url_2[1] );
			} else {
				$url_3 = $url_2;
			}
			$avatar = "<img src='{$url_3[0]}' alt='' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}

		return $avatar;
	}

	/**
	 * Default avatar option
	 *
	 * Updates the default option to a local avatar
	 * if the option is set to use a Gravatar image.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function avatar_default() {

		// Get default avatar option.
		$default = get_option( 'avatar_default' );
		$fresh   = get_option( 'fresh_site' );

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
		if ( true == $fresh || ! $default || 'mystery' == $default ) {
			update_option( 'avatar_default', $mystery );

		// If the blank Gravatar is set then update to the local blank avatar.
		} elseif ( 'blank' == $default ) {
			update_option( 'avatar_default', $blank );

		// If any Gravatar is set then update to the local mystery person avatar.
		} elseif ( in_array( $default, $gravatar ) ) {
			update_option( 'avatar_default', $mystery );
		}
	}

	/**
	 * Get default avatar options
	 *
	 * Some these are simply provided as sample avatar options.
	 * Add or edit for your needs. To implement these defaults,
	 * add them to the array in the `avatar_defaults()` method.
	 *
	 * @see `avatar_defaults()`
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $avatar_defaults
	 * @return array Returns an array of new avatar options.
	 */
	public function get_avatar_defaults( $defaults = [] ) {

		// Local avatar options.
		$defaults = [
			'mystery' => esc_url( SCP_URL . 'assets/images/mystery.png' ),
			'light'   => esc_url( SCP_URL . 'assets/images/mystery-light.png' ),
			'dark'    => esc_url( SCP_URL . 'assets/images/mystery-dark.png' ),
			'generic' => esc_url( SCP_URL . 'assets/images/generic.png' ),
			'yellow'  => esc_url( SCP_URL . 'assets/images/yellow.png' ),
			'pink'    => esc_url( SCP_URL . 'assets/images/pink.png' ),
			'blue'    => esc_url( SCP_URL . 'assets/images/blue.png' ),
			'violet'  => esc_url( SCP_URL . 'assets/images/violet.png' ),
			'red'     => esc_url( SCP_URL . 'assets/images/red.png' ),
			'green'   => esc_url( SCP_URL . 'assets/images/green.png' ),
			'orange'  => esc_url( SCP_URL . 'assets/images/orange.png' ),
			'black'   => esc_url( SCP_URL . 'assets/images/black.png' ),
			'white'   => esc_url( SCP_URL . 'assets/images/white.png' ),
			'gray'    => esc_url( SCP_URL . 'assets/images/gray.png' ),
			'brown'   => esc_url( SCP_URL . 'assets/images/brown.png' ),
			'tan'     => esc_url( SCP_URL . 'assets/images/tan.png' ),
			'blank'   => esc_url( SCP_URL . 'assets/images/blank.png' )
		];

		// Return avatar types.
		return apply_filters( 'uap_get_avatar_defaults', $defaults );
	}

	/**
	 * Default avatar options
	 *
	 * Replaces the default avatar list on the Discussion Settings page.
	 *
	 * @see `get_avatar_defaults()`
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $avatar_defaults
	 * @return array Returns an array of new avatar options.
	 */
	public function avatar_defaults( $options = [] ) {

		// Get available default avatars.
		$defaults = $this->get_avatar_defaults();

		// Remove the action to get current user's avatar.
		remove_action( 'get_avatar', [ $this, 'get_avatar' ] );

		// Array of new avatar options.
		$options = [
			$defaults['mystery'] => __( 'Mystery', 'sitecore' ),
			$defaults['light']   => __( 'Mystery Light', 'sitecore' ),
			$defaults['dark']    => __( 'Mystery Dark', 'sitecore' ),
			$defaults['yellow']  => __( 'Yellow', 'sitecore' ),
			$defaults['pink']    => __( 'Pink', 'sitecore' ),
			$defaults['blue']    => __( 'Blue', 'sitecore' ),
			$defaults['violet']  => __( 'Violet', 'sitecore' ),
			$defaults['red']     => __( 'Red', 'sitecore' ),
			$defaults['green']   => __( 'Green', 'sitecore' ),
			$defaults['orange']  => __( 'Orange', 'sitecore' ),
			$defaults['brown']   => __( 'Brown', 'sitecore' ),
			$defaults['tan']     => __( 'Tan', 'sitecore' ),
			$defaults['black']   => __( 'Black', 'sitecore' ),
			$defaults['white']   => __( 'White', 'sitecore' ),
			$defaults['gray']    => __( 'Gray', 'sitecore' ),
			$defaults['blank']   => __( 'Blank', 'sitecore' )
		];

		// Return new avatar options.
		return apply_filters( 'uap_avatar_defaults', $options );
	}

	/**
	 * Delete avatars based on user_id.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int $user_id
	 */
	public function avatar_delete( $user_id ) {

		$old_avatars = get_user_meta( $user_id, 'uap_user_avatar', true );
		$upload_path = wp_upload_dir();

		if ( is_array( $old_avatars ) ) {
			foreach ( $old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				@unlink( $old_avatar_path );
			}
		}

		delete_user_meta( $user_id, 'uap_user_avatar' );
	}

	/**
	 * File names are magic.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $dir
	 * @param  string $name
	 * @param  string $ext
	 * @return string
	 */
	public function unique_filename_callback( $dir, $name, $ext ) {

		$user   = get_user_by( 'id', (int) 0 );
		$name   = $base_name = sanitize_file_name( $user->display_name . '_avatar' );
		$number = 1;

		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}

		return $name . $ext;
	}
}
