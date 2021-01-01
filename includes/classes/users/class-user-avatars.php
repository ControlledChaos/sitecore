<?php
/**
 * Add an avatar upload field to user profiles.
 *
 * Also provides front-end avatar management via a shortcode and bbPress support.
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Users
 * @since      1.0.0
 */

namespace SiteCore\Classes\Users;

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

		// Actions.
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_action( 'show_user_profile', [ $this, 'edit_user_profile' ] );
		add_action( 'edit_user_profile', [ $this, 'edit_user_profile' ] );
		add_action( 'personal_options_update', [ $this, 'edit_user_profile_update' ] );
		add_action( 'edit_user_profile_update', [ $this, 'edit_user_profile_update' ] );
		add_action( 'bbp_user_edit_after_about', [ $this, 'bbpress_user_profile' ] );

		// Shortcode.
		add_shortcode( SCP_DOMAIN, [ $this, 'shortcode' ] );

		// Filters.
		add_filter( 'get_avatar', [ $this, 'get_avatar' ], 10, 5 );
		add_filter( 'avatar_defaults', [ $this, 'avatar_defaults' ] );

		add_action( 'admin_print_styles', [ $this, 'admin_head' ] );
	}

	public function admin_head() {

		?>
		<style>
		.user-profile-picture {
			display: none;
		}
		</style>
		<?php
	}

	/**
	 * Start the admin engine.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_init() {

		// Discussion setting to restrict avatar upload capabilites.
		add_settings_field(
			'basic-user-avatars-caps',
			__( 'Local Avatar Permissions',	SCP_DOMAIN ),
			[ $this, 'avatar_settings_field' ],
			'discussion',
			'avatars',
			[ esc_html__( 'Only allow users with file upload capabilities to upload local avatars (Authors and above).', SCP_DOMAIN ) ]
		);

		register_setting(
			'discussion',
			'scp_user_avatars_caps'
		);

	}

	/**
	 * Discussion settings option.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function avatar_settings_field( $args ) {

		$option = get_option( 'scp_user_avatars_caps' );

		$html = '<p><input type="checkbox" id="scp_user_avatars_caps" name="scp_user_avatars_caps" value="1" ' . checked( 1, $option, false ) . '/>';

		$html .= '<label for="scp_user_avatars_caps"> ' . $args[0] . '</label></p>';

		echo $html;

	}

	/**
	 * Filter the avatar WordPress returns.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $avatar
	 * @param  int/string/object $id_or_email
	 * @param  int $size
	 * @param  string $default
	 * @param  boolean $alt
	 * @return string
	 */
	public function get_avatar( $avatar = '', $id_or_email, $size = 96, $default = '', $alt = false ) {

		// Determine if we recive an ID or string.
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;

		if ( empty( $user_id ) )
			return $avatar;

		$local_avatars = get_user_meta( $user_id, 'scp_user_avatar', true );

		if ( empty( $local_avatars ) || empty( $local_avatars['full'] ) )
			return $avatar;

		$size = (int) $size;

		if ( empty( $alt ) )
			$alt = get_the_author_meta( 'display_name', $user_id );

		// Generate a new size.
		if ( empty( $local_avatars[$size] ) ) {

			$upload_path      = wp_upload_dir();
			$avatar_full_path = str_replace( $upload_path['baseurl'], $upload_path['basedir'], $local_avatars['full'] );
			$image            = wp_get_image_editor( $avatar_full_path );

			if ( ! is_wp_error( $image ) ) {
				$image->resize( $size, $size, true );
				$image_sized = $image->save();
			}

			// Deal with original being >= to original image (or lack of sizing ability).
			$local_avatars[$size] = is_wp_error( $image_sized ) ? $local_avatars[$size] = $local_avatars['full'] : str_replace( $upload_path['basedir'], $upload_path['baseurl'], $image_sized['path'] );

			// Save updated avatar sizes
			update_user_meta( $user_id, 'scp_user_avatar', $local_avatars );

		} elseif ( substr( $local_avatars[$size], 0, 4 ) != 'http' ) {
			$local_avatars[$size] = home_url( $local_avatars[$size] );
		}

		$author_class = is_author( $user_id ) ? ' current-author' : '' ;
		$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars[$size] . "' class='avatar avatar-{$size}{$author_class} photo' height='{$size}' width='{$size}' />";

		return apply_filters( 'scp_user_avatar', $avatar );

	}

	/**
	 * Form to display on the user profile edit screen.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param object $profileuser
	 * @return mixed
	 */
	public function edit_user_profile( $profileuser ) {

		// bbPress will try to auto-add this to user profiles - don't let it.
		// Instead we hook our own proper function that displays cleaner.
		if ( function_exists( 'is_bbpress') && is_bbpress() )
			return;
		?>

		<h2><?php _e( 'Avatar', SCP_DOMAIN ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="basic-user-avatar"><?php _e( 'Upload Avatar', SCP_DOMAIN ); ?></label></th>
				<td style="width: 50px;" valign="top">
					<?php echo get_avatar( $profileuser->ID ); ?>
				</td>
				<td>
				<?php
				$options = get_option( 'scp_user_avatars_caps' );
				if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
					// Nonce security ftw.
					wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

					// File upload input.
					echo '<input type="file" name="basic-user-avatar" id="basic-local-avatar" /><br />';

					if ( empty( $profileuser->scp_user_avatar ) ) {
						echo '<span class="description">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', SCP_DOMAIN ) . '</span>';
					} else {
						echo '<input type="checkbox" name="basic-user-avatar-erase" value="1" /> ' . __( 'Delete local avatar', SCP_DOMAIN ) . '<br />';
						echo '<span class="description">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', SCP_DOMAIN ) . '</span>';
					}

				} else {
					if ( empty( $profileuser->scp_user_avatar ) ) {
						echo '<span class="description">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', SCP_DOMAIN ) . '</span>';
					} else {
						echo '<span class="description">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', SCP_DOMAIN ) . '</span>';
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
	 * Update the user's avatar setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param int $user_id
	 * @return mixed
	 */
	public function edit_user_profile_update( $user_id ) {

		// Check for nonce otherwise bail.
		if ( ! isset( $_POST['_scp_user_avatar_nonce'] ) || ! wp_verify_nonce( $_POST['_scp_user_avatar_nonce'], 'scp_user_avatar_nonce' ) )
			return;

		if ( ! empty( $_FILES['basic-user-avatar']['name'] ) ) {

			// Allowed file extensions/types.
			$mimes = [
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			];

			// Front end support - shortcode, bbPress, etc.
			if ( ! function_exists( 'wp_handle_upload' ) )
				require_once ABSPATH . 'wp-admin/includes/file.php';

			// Delete old images if successful.
			$this->avatar_delete( $user_id );

			// Need to be more secure since low privelege users can upload.
			if ( strstr( $_FILES['basic-user-avatar']['name'], '.php' ) )
				wp_die( 'For security reasons, the extension ".php" cannot be in your file name.' );

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

			// Save user information (overwriting previous).
			update_user_meta( $user_id, 'scp_user_avatar', [ 'full' => $avatar['url'] ] );

		} elseif ( ! empty( $_POST['basic-user-avatar-erase'] ) ) {
			// Nuke the current avatar
			$this->avatar_delete( $user_id );
		}

	}

	/**
	 * Enable avatar management on the frontend via this shortocde.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	function shortcode() {

		// Don't bother if the user isn't logged in.
		if ( ! is_user_logged_in() )
			return;

		$user_id     = get_current_user_id();
		$profileuser = get_userdata( $user_id );

		if ( isset( $_POST['manage_avatar_submit'] ) ){
			$this->edit_user_profile_update( $user_id );
		}

		ob_start();
		?>
		<form id="basic-user-avatar-form" action="<?php the_permalink(); ?>" method="post" enctype="multipart/form-data">
			<?php
			echo get_avatar( $profileuser->ID );

			$options = get_option( 'scp_user_avatars_caps' );
			if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
				// Nonce security ftw.
				wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

				// File upload input.
				echo '<p><input type="file" name="basic-user-avatar" id="basic-local-avatar" /></p>';

				if ( empty( $profileuser->scp_user_avatar ) ) {
					echo '<p class="description">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', SCP_DOMAIN ) . '</p>';
				} else {
					echo '<input type="checkbox" name="basic-user-avatar-erase" value="1" /> ' . __( 'Delete local avatar', SCP_DOMAIN ) . '<br />';
					echo '<p class="description">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', SCP_DOMAIN ) . '</p>';
				}

			} else {
				if ( empty( $profileuser->scp_user_avatar ) ) {
					echo '<p class="description">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', SCP_DOMAIN ) . '</p>';
				} else {
					echo '<p class="description">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', SCP_DOMAIN ) . '</p>';
				}
			}
			?>
			<input type="submit" name="manage_avatar_submit" value="<?php _e( 'Update Avatar', SCP_DOMAIN ); ?>" />
		</form>
		<?php

		return ob_get_clean();

	}

	/**
	 * Form to display on the bbPress user profile edit screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function bbpress_user_profile() {

		if ( !bbp_is_user_home_edit() )
			return;

		$user_id     = get_current_user_id();
		$profileuser = get_userdata( $user_id );

		echo '<div>';
			echo '<label for="basic-local-avatar">' . __( 'Avatar', SCP_DOMAIN ) . '</label>';
 			echo '<fieldset class="bbp-form avatar">';

	 			echo get_avatar( $profileuser->ID );
				$options = get_option( 'scp_user_avatars_caps' );
				if ( empty( $options['scp_user_avatars_caps'] ) || current_user_can( 'upload_files' ) ) {
					// Nonce security ftw.
					wp_nonce_field( 'scp_user_avatar_nonce', '_scp_user_avatar_nonce', false );

					// File upload input.
					echo '<br /><input type="file" name="basic-user-avatar" id="basic-local-avatar" /><br />';

					if ( empty( $profileuser->scp_user_avatar ) ) {
						echo '<span class="description" style="margin-left:0;">' . __( 'No local avatar is set. Use the upload field to add a local avatar.', SCP_DOMAIN ) . '</span>';
					} else {
						echo '<input type="checkbox" name="basic-user-avatar-erase" value="1" style="width:auto" /> ' . __( 'Delete local avatar', SCP_DOMAIN ) . '<br />';
						echo '<span class="description" style="margin-left:0;">' . __( 'Replace the local avatar by uploading a new avatar, or erase the local avatar (falling back to a gravatar) by checking the delete option.', SCP_DOMAIN ) . '</span>';
					}

				} else {
					if ( empty( $profileuser->scp_user_avatar ) ) {
						echo '<span class="description" style="margin-left:0;">' . __( 'No local avatar is set. Set up your avatar at Gravatar.com.', SCP_DOMAIN ) . '</span>';
					} else {
						echo '<span class="description" style="margin-left:0;">' . __( 'You do not have media management permissions. To change your local avatar, contact the site administrator.', SCP_DOMAIN ) . '</span>';
					}
				}

			echo '</fieldset>';
		echo '</div>';
		?>
		<script type="text/javascript">var form = document.getElementById('bbp-your-profile');form.encoding = 'multipart/form-data';form.setAttribute('enctype', 'multipart/form-data');</script>
		<?php

	}

	/**
	 * Remove the custom get_avatar hook for the default avatar list output on
	 * the Discussion Settings page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $avatar_defaults
	 * @return array
	 */
	public function avatar_defaults( $avatar_defaults = [] ) {

		// remove_action( 'get_avatar', [ $this, 'get_avatar' ] );

		$new_avatar_defaults = $avatar_defaults;

		// Maybe block Gravatars
		if ( get_option( 'scp_block_gravatar' ) ) {
			$new_avatar_defaults = [
				'mystery' => __( 'Mystery Person', SCP_DOMAIN ),
				'blank'   => __( 'Blank', SCP_DOMAIN )
			];
		}

		// Return avatar types, maybe without Gravatar options
		return $new_avatar_defaults;

	}

	/**
	 * Delete avatars based on user_id.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int $user_id
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

		$user = get_user_by( 'id', (int) $this->user_id_being_edited );
		$name = $base_name = sanitize_file_name( $user->display_name . '_avatar' );
		$number = 1;

		while ( file_exists( $dir . "/$name$ext" ) ) {
			$name = $base_name . '_' . $number;
			$number++;
		}

		return $name . $ext;

	}

}
