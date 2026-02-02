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
 *       Use media upload with square crop; resize file.
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
	 * User ID
	 *
	 * @since 1.0.0
	 * @var   integer
	 */
	private $user_id_being_edited;

	/**
	 * Initialize all the things
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Print admin styles to head.
		add_action( 'admin_print_styles', [ $this, 'admin_print_styles' ], 20 );

		// Print admin scripts to head.
		add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ], 20 );

		// Avatar upload capability.
		add_action( 'admin_init', [ $this, 'capability' ] );

		// Add avatar upload form to profile screens.
		add_action( 'show_user_profile', [ $this, 'edit_user_profile' ], 9 );
		add_action( 'edit_user_profile', [ $this, 'edit_user_profile' ], 9 );

		// Update profile with new avatar.
		add_action( 'personal_options_update', [ $this, 'edit_user_profile_update' ], 9 );
		add_action( 'edit_user_profile_update', [ $this, 'edit_user_profile_update' ], 9 );

		// Add bbPress forum support.
		add_action( 'bbp_user_edit_after_about', [ $this, 'bbpress_user_profile' ] );

		// Add avatar shortcode.
		add_shortcode( 'local-user-avatar', [ $this, 'shortcode' ] );

		// Disable the connection to Gravatar for local avatars.
		add_filter( 'get_avatar', [ $this, 'disable_gravatar' ], 9, 5 );

		// Redefine avatar.
		add_filter( 'get_avatar', [ $this, 'get_avatar' ], 10, 6 );

		// Filter normal avatar data.
		add_filter( 'get_avatar_data', [ $this, 'get_avatar_data' ], 10, 2 );

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

		// Access global variables.
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

		.options-discussion-php .defaultavatarpicker label {
			box-sizing: border-box;
			display: block !important;
			width: 25%;
			float: left;
			margin: 0 !important;
			padding: 0.5em;
		}

		.defaultavatarpicker .avatar {
			display: inline-block;
			max-width: 48px;
			height: auto;
			margin: 0 0.25em !important;
			border: 1px solid #ccc;
			border-radius: 50%;
			/* The background image is for the blank avatar to display something. */
			background-image: url( <?php echo SCP_URL . 'assets/images/checker-bg.png' ?> );
			background-size: contain;
		}

		@media screen and ( max-width: 1082px ) {
			.options-discussion-php .defaultavatarpicker label {
				width: 33.33325%;
				width: calc( 100% / 3 );
			}
		}

		@media screen and ( max-width: 570px ) {
			.options-discussion-php .defaultavatarpicker label {
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

		// Access global variables.
		global $pagenow;

		// Print styles only on the discussion settings page.
		if ( 'options-discussion.php' == $pagenow ) :
		?>
		<script>
		jQuery(document).ready( function ($) {
			$( 'td.defaultavatarpicker fieldset p' ).remove();
		});
		</script>
		<?php
		endif; // If options-discussion.php.
	}

	/**
	 * Disable Gravatar
	 *
	 * Replaces the gravatar.com URL for the local server.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $avatar
	 * @param  integer $id
	 * @param  string $size
	 * @param  string $default
	 * @param  string $alt
	 * @return array
	 */
	public function disable_gravatar( $avatar, $id, $default, $alt ) {

		$localhost = [ 'localhost', '127.0.0.1' ];
		$size = (int) 96;
		$alt  = '';
		if ( is_numeric( $id ) ) {
			$user = get_user_by( 'id', $id );
			$alt  = $user->display_name;
		}

		if ( ! in_array( $_SERVER['SERVER_ADDR'] , $localhost ) ) {
			return $avatar;
		}

		$document = new \DOMDocument;
		$document->loadHTML( $avatar );
		$images   = $document->getElementsByTagName( 'img' );

		if ( $images && $images->length > 0 ) {
			$url_1  = urldecode( $images->item(0)->getAttribute( 'src' ) );
			$url_2  = explode( 'd=', $url_1 );
			if ( is_array( $url_2 ) && isset( $url_2[1] ) ) {
				$url_3 = explode( '&', $url_2[1] );
			} else {
				$url_3 = $url_2;
			}
			$avatar = "<img src='{$url_3[0]}' alt='{$alt}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' srcset='{$url_3[0]} 2x' />";
		}

		return $avatar;
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
			'scp_scp_user_avatars_caps',
			__( 'Upload Permission',	'sitecore' ),
			[ $this, 'capability_field' ],
			'discussion',
			'avatars',
			[ esc_html__( 'Only allow users with file upload capabilities to upload local avatars (authors and above).', 'sitecore' ) ]
		);

		register_setting(
			'discussion',
			'scp_scp_user_avatars_caps'
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

		$option = get_option( 'scp_scp_user_avatars_caps' );

		$html = '<p><input type="checkbox" id="scp_scp_user_avatars_caps" name="scp_scp_user_avatars_caps" value="1" ' . checked( 1, $option, false ) . '/>';

		$html .= '<label for="scp_scp_user_avatars_caps"> ' . $args[0] . '</label></p>';

		echo $html;
	}

	/**
	 * Discussion settings option
	 *
	 * @since 1.0.0
	 * @access public
	 * @param  array $args
	 * @return void
	 */
	public function avatar_settings_field( $args ) {

		$options = get_option( 'scp_user_avatars_caps' );
		$checked = ! empty( $options['scp_user_avatars_caps'] ) ? 1 : 0;

		?>
		<label for="scp_user_avatars_caps">
			<input type="checkbox" name="scp_user_avatars_caps" id="scp_user_avatars_caps" value="1" <?php checked( $checked, 1 ); ?>/>
			<?php esc_html_e( 'Only allow users with file upload capabilities to upload local avatars (authors and above)', 'sitecore' ); ?>
		</label>
		<?php
	}

	/**
	 * Sanitize the Discussion settings option
	 *
	 * @since 1.0.0
	 * @access public
	 * @param  array $input
	 * @return array
	 */
	public function sanitize_options( $input ) {
		$new_input['scp_user_avatars_caps'] = empty( $input ) ? 0 : 1;
		return $new_input;
	}

	/**
	 * Filter the normal avatar data and show our avatar if set.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param  array $args Arguments passed to get_avatar_data() after processing.
	 * @param  mixed $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash, user email, WP_User object, WP_Post object, or WP_Comment object.
	 * @global $wpdb The database.
	 * @return array The filtered avatar data.
	 */
	public function get_avatar_data( $args = [], $id_or_email = false ) {

		// Access global variables.
		global $wpdb;

		if ( ! empty( $args['force_default'] ) ) {
			return $args;
		}
		$return_args = $args;

		// Determine if we received an ID or string. Then, set the $user_id variable.
		if ( is_numeric( $id_or_email ) && 0 < $id_or_email ) {
			$user_id = (int) $id_or_email;
		} elseif ( is_object( $id_or_email ) && isset( $id_or_email->user_id ) && 0 < $id_or_email->user_id ) {
			$user_id = $id_or_email->user_id;
		} elseif ( is_object( $id_or_email ) && isset( $id_or_email->ID ) && isset( $id_or_email->user_login ) && 0 < $id_or_email->ID ) {
			$user_id = $id_or_email->ID;
		} elseif ( is_string( $id_or_email ) && false !== strpos( $id_or_email, '@' ) ) {
			$_user = get_user_by( 'email', $id_or_email );

			if ( ! empty( $_user ) ) {
				$user_id = $_user->ID;
			}
		}

		if ( empty( $user_id ) ) {
			return $args;
		}

		$user_avatar_url = null;

		// Get the user's local avatar from usermeta.
		$local_avatars = get_user_meta( $user_id, 'scp_user_avatar', true );

		if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) ) {

			// Try to pull avatar from WP User Avatar.
			$wp_user_avatar_id = get_user_meta( $user_id, $wpdb->get_blog_prefix() . 'user_avatar', true );
			if ( ! empty( $wp_user_avatar_id ) ) {
				$wp_user_avatar_url = wp_get_attachment_url( intval( $wp_user_avatar_id ) );
				$local_avatars = [ 'full' => $wp_user_avatar_url ];
				update_user_meta( $user_id, 'scp_user_avatar', $local_avatars );
			} else {
				// We don't have a local avatar, just return.
				return $args;
			}
		}

		/**
		 * Filter the default avatar size during upload.
		 * @param integer $size The default avatar size. Default 96.
		 * @param array $args The default avatar args available at the time of this filter.
		 */
		$size = apply_filters( 'scp_user_avatars_default_size', (int) $args['size'], $args );

		// Generate a new size
		if ( empty( $local_avatars[$size] ) ) {

			$upload_path      = wp_upload_dir();
			$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
			$image            = wp_get_image_editor( $avatar_full_path );
			$image_sized      = null;

			if ( ! is_wp_error( $image ) ) {
				$image->resize( $size, $size, true );
				$image_sized = $image->save();
			}

			// Deal with original being >= to original image (or lack of sizing ability).
			if ( empty( $image_sized ) || is_wp_error( $image_sized ) ) {
				$local_avatars[ $size ] = $local_avatars['full'];
			} else {
				$local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );
			}

			// Save updated avatar sizes
			update_user_meta( $user_id, 'scp_user_avatar', $local_avatars );

		} elseif ( substr( $local_avatars[ $size ], 0, 4 ) != 'http' ) {
			$local_avatars[ $size ] = home_url( $local_avatars[ $size ] );
		}

		if ( is_ssl() ) {
			$local_avatars[ $size ] = str_replace( 'http:', 'https:', $local_avatars[ $size ] );
		}

		$user_avatar_url = $local_avatars[ $size ];

		if ( $user_avatar_url ) {
			$return_args['url'] = $user_avatar_url;
			$return_args['found_avatar'] = true;
		}

		/**
		 * Allow filtering the avatar data that we are overriding.
		 *
		 * @since 1.0.0
		 * @param array $return_args The list of user avatar data arguments.
		 */
		return apply_filters( 'scp_user_avatar_data', $return_args );
	}

	/**
	 * Add a backwards compatible hook to further filter our customized avatar HTML.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $avatar HTML for the user's avatar.
	 * @param  mixed  $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash, user email, WP_User object, WP_Post object, or WP_Comment object.
	 * @param  integer $size Square avatar width and height in pixels to retrieve.
	 * @param  string $default URL for the default image or a default type.
	 * @param  string $alt Alternative text to use in the avatar image tag.
	 * @param  array  $args Arguments passed to get_avatar_data(), after processing.
	 * @return string The filtered avatar HTML.
	 */
	public function get_avatar( $avatar, $id_or_email, $size = 96, $default = '', $alt = false, $args = [] ) {

		/**
		 * Filter to further customize the avatar HTML.
		 *
		 * @since 1.0.0
		 * @param string $avatar HTML for the user's avatar.
		 * @param mixed  $id_or_email The avatar to retrieve. Accepts a user_id, Gravatar MD5 hash,
	 	 *                            user email, WP_User object, WP_Post object, or WP_Comment object.
	 	 * @return string The filtered avatar HTML.
		 * @deprecated since 1.0.0
		 */
		return apply_filters( 'scp_user_avatar', $avatar, $id_or_email );
	}

	/**
	 * Form to display on the user profile edit screen
	 *
	 * @since 1.0.0
	 * @access public
	 * @param  object $user
	 * @return void
	 */
	public function edit_user_profile( $user ) {

		/**
		 * bbPress will try to auto-add this to user profiles. Instead this
		 * hooks to function that displays cleaner.
		 */
		if ( function_exists( 'is_bbpress') && is_bbpress() ) {
			return;
		}

		$figcaption = __( 'Default Avatar', 'sitecore' );
		if ( ! empty( $user->scp_user_avatar ) ) {
			$figcaption = __( 'Current Avatar', 'sitecore' );
		}

		?>
		<h2><?php _e( 'Avatar', 'sitecore' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="basic-user-avatar"><?php esc_html_e( 'Upload Avatar', 'sitecore' ); ?></label></th>
				<td style="width: 50px;" valign="top">
					<figure style="margin: 0">
						<?php echo get_avatar( $user->ID ); ?>
						<figcaption><?php echo $figcaption; ?></figcaption>
					</figure>
				</td>
				<td>
				<?php
				$options = get_option( 'scp_user_avatars_caps' );
				if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
					// Nonce security.
					wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

					// File upload input.
					echo '<input type="file" name="basic-user-avatar" id="basic-local-avatar" />';

					if ( empty( $user->scp_user_avatar ) ) {
						echo '<p class="description">' . esc_html__( 'No local avatar is set. Use the upload field to add a local avatar.', 'sitecore' ) . '</p>';
					} else {
						echo '<p><input type="checkbox" name="scp-user-avatar-erase" id="scp-user-avatar-erase" value="1" /><label for="scp-user-avatar-erase">' . esc_html__( 'Delete current avatar', 'sitecore' ) . '</label></p>';
						echo '<p class="description">' . esc_html__( 'Replace the avatar by uploading a new avatar, or erase the current avatar by checking the delete option.', 'sitecore' ) . '</p>';
					}

				} else {
					if ( empty( $user->scp_user_avatar ) ) {
						echo '<p class="description">' . esc_html__( 'No local avatar is set.', 'sitecore' ) . '</p>';
					} else {
						echo '<p class="description">' . esc_html__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'sitecore' ) . '</p>';
					}
				}
				?>
				</td>
			</tr>
		</table>
		<script type="text/javascript">var form = document.getElementById('your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
		<?php
	}

	/**
	 * Update the user's avatar setting
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  integer $user_id
	 * @return mixed
	 */
	public function edit_user_profile_update( $user_id ) {

		// Check for nonce.
		if ( ! isset( $_POST['_scp_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_scp_user_avatar_nonce'], 'scp_user_avatar_nonce' ) ) {
			return;
		}

		if ( ! empty( $_FILES['basic-user-avatar']['name'] ) ) {

			// Allowed file extensions/types.
			$mimes = [
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
				'gif'          => 'image/gif'
			];

			// Front end support - shortcode, bbPress, etc
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			$this->avatar_delete( $this->user_id_being_edited );

			// Need to be more secure since low privilege users can upload.
			if ( strstr( $_FILES['basic-user-avatar']['name'], '.php' ) )
				wp_die( 'For security reasons, the extension ".php" cannot be in your file name.' );

			// Make user_id known to unique_filename_callback function.
			$this->user_id_being_edited = $user_id;
			$avatar = wp_handle_upload(
				$_FILES['basic-user-avatar'], [
					'mimes'     => $mimes,
					'test_form' => false,
					'unique_filename_callback' => [ $this, 'unique_filename_callback' ]
				]
			);

			// Handle failures.
			if ( empty( $avatar['file'] ) ) {
				switch ( $avatar['error'] ) {
				case 'File type does not meet security guidelines. Try another.' :
					add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ){
						esc_html__( 'Please upload a valid image file for the avatar.', 'sitecore' );
					} );
					break;
				default :
					add_action( 'user_profile_update_errors', function( $error = 'avatar_error' ) {

						// No error.
						if ( empty( $avatar['error'] ) ) {
							return;
						}

						'<strong>' . esc_html__( 'There was an error uploading the avatar:', 'sitecore' ) . '</strong> ' .  esc_attr( $avatar['error'] );
					} );
				}
				return;
			}

			// Save user information (overwriting previous).
			update_user_meta( $user_id, 'scp_user_avatar', [ 'full' => $avatar['url'] ] );

		// Delete the current avatar.
		} elseif ( ! empty( $_POST['scp-user-avatar-erase'] ) ) {
			$this->avatar_delete( $user_id );
		}
	}

	/**
	 * Frontend avatar management via shortcode
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	function shortcode( $atts, $content = '' ) {

		if ( ! is_user_logged_in() ) {
			return;
		}

		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		if ( isset( $_POST['manage_avatar_submit'] ) ){
			$this->edit_user_profile_update( $user_id );
		}

		ob_start();

		?>
		<form id="basic-user-avatar-form" method="post" enctype="multipart/form-data">
			<?php
			echo get_avatar( $user->ID );

			$options = get_option( 'scp_user_avatars_caps' );
			if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
				// Nonce security.
				wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

				// File upload input.
				echo '<p><input type="file" name="basic-user-avatar" id="basic-local-avatar" /></p>';

				if ( empty( $user->scp_user_avatar ) ) {
					echo '<p class="description">' . apply_filters( 'bu_avatars_no_avatar_set_text',esc_html__( 'No local avatar is set. Use the upload field to add a local avatar.', 'sitecore' ), $user ) . '</p>';
				} else {
					echo '<p><input type="checkbox" name="scp-user-avatar-erase" id="scp-user-avatar-erase" value="1" /> <label for="scp-user-avatar-erase">' . apply_filters( 'bu_avatars_delete_avatar_text', esc_html__( 'Delete current avatar', 'sitecore' ), $user ) . '</label></p>';
					echo '<p class="description">' . apply_filters( 'bu_avatars_replace_avatar_text', esc_html__( 'Replace the avatar by uploading a new avatar, or erase the current avatar by checking the delete option.', 'sitecore' ), $user ) . '</p>';
				}

				echo '<input type="submit" name="manage_avatar_submit" value="' . apply_filters( 'bu_avatars_update_button_text', esc_attr__( 'Update Avatar', 'sitecore' ) ) . '" />';

			} else {
				if ( empty( $user->scp_user_avatar ) ) {
					echo '<p class="description">' . apply_filters( 'bu_avatars_no_avatar_set_text', esc_html__( 'No local avatar is set.', 'sitecore' ), $user ) . '</p>';
				} else {
					echo '<p class="description">' . apply_filters( 'bu_avatars_permissions_text', esc_html__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'sitecore' ), $user ) . '</p>';
				}
			}
			?>
		</form>
		<?php

		$content = ob_get_clean();
		return $content;
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

		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		echo '<div>';
			echo '<label for="basic-local-avatar">' . esc_html__( 'Avatar', 'sitecore' ) . '</label>';
 			echo '<fieldset class="bbp-form avatar">';

	 			echo get_avatar( $user->ID );
				$options = get_option( 'scp_user_avatars_caps' );
				if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
					// Nonce security.
					wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

					// File upload input.
					echo '<br /><input type="file" name="basic-user-avatar" id="basic-local-avatar" /><br />';

					if ( empty( $user->scp_user_avatar ) ) {
						echo '<span class="description" style="margin-left:0;">' . apply_filters( 'bu_avatars_no_avatar_set_text', esc_html__( 'No local avatar is set. Use the upload field to add a local avatar.', 'sitecore' ), $user ) . '</span>';
					} else {
						echo '<input type="checkbox" name="scp-user-avatar-erase" id="scp-user-avatar-erase" value="1" style="width:auto" /> <label for="scp-user-avatar-erase">' . apply_filters( 'bu_avatars_delete_avatar_text', __( 'Delete current avatar', 'sitecore' ), $user ) . '</label><br />';
						echo '<span class="description" style="margin-left:0;">' . apply_filters( '', esc_html__( 'Replace the avatar by uploading a new avatar, or erase the current avatar by checking the delete option.', 'sitecore' ), $user ) . '</span>';
					}

				} else {
					if ( empty( $user->scp_user_avatar ) ) {
						echo '<span class="description" style="margin-left:0;">' . apply_filters( 'bu_avatars_no_avatar_set_text', esc_html__( 'No local avatar is set.', 'sitecore' ), $user ) . '</span>';
					} else {
						echo '<span class="description" style="margin-left:0;">' . apply_filters( 'bu_avatars_permissions_text', esc_html__( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', 'sitecore' ), $user ) . '</span>';
					}
				}

			echo '</fieldset>';
		echo '</div>';
		?>
		<script type="text/javascript">var form = document.getElementById('bbp-your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
		<?php
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
			'mm'      => esc_url( SCP_URL . 'assets/images/mystery.png' ),
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
		return apply_filters( 'scp_get_avatar_defaults', $defaults );
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
			$defaults['mystery'] => __( 'Core', 'sitecore' ),
			$defaults['light']   => __( 'Light', 'sitecore' ),
			$defaults['dark']    => __( 'Dark', 'sitecore' ),
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
		return apply_filters( 'scp_avatar_defaults', $options );
	}

	/**
	 * Delete avatars based on user ID
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  integer $user_id
	 * @return void
	 */
	public function avatar_delete( $user_id ) {

		$old_avatars = get_user_meta( $user_id, 'scp_user_avatar', true );
		$upload_path = wp_upload_dir();

		if ( is_array( $old_avatars ) ) {
			foreach ( $old_avatars as $old_avatar ) {
				$old_avatar_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $old_avatar );
				@unlink( $old_avatar_path );
			}
		}
		delete_user_meta( $user_id, 'scp_user_avatar' );
	}

	/**
	 * Unique avatar filename
	 *
	 * Includes the user ID rather than login name for security.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $dir
	 * @param  string $name
	 * @param  string $ext
	 * @return string
	 */
	public function unique_filename_callback( $dir, $name, $ext ) {

		$user = get_user_by( 'id', (int) $this->user_id_being_edited );
		$name = $base_name = sanitize_file_name( 'user-' . $user->ID . '-avatar' );

		$number = 1;
		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}
		return $name . $ext;
	}
}
