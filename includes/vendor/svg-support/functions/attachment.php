<?php
/**
 * Display SVG in attachment modal
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function bodhi_svgs_response_for_svg( $response, $attachment, $meta ) {

	if ( $response['mime'] == 'image/svg+xml' && empty( $response['sizes'] ) ) {

		$svg_path = get_attached_file( $attachment->ID );

		if ( ! file_exists( $svg_path ) ) {
			// If SVG is external, use the URL instead of the path
			$svg_path = $response['url'];
		}

		$dimensions = bodhi_svgs_get_dimensions( $svg_path );

		$response['sizes'] = array(
			'full' => array(
				'url' => $response['url'],
				'width' => $dimensions->width,
				'height' => $dimensions->height,
				'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait'
			)
		);

	}

	return $response;

}
add_filter( 'wp_prepare_attachment_for_js', 'bodhi_svgs_response_for_svg', 10, 3 );

function bodhi_svgs_get_dimensions( $svg ) {

	$svg = simplexml_load_file( $svg );

	if ( $svg === FALSE ) {

		$width = '0';
		$height = '0';

	} else {

		$attributes = $svg->attributes();
		$width = (string) $attributes->width;
		$height = (string) $attributes->height;

	}

	return (object) array( 'width' => $width, 'height' => $height );

}

/**
 * Generate attachment metadata (Thanks @surml)
 *
 * Fixes Illegal String Offset Warning for Height & Width
 */
function bodhi_svgs_generate_svg_attachment_metadata( $metadata, $attachment_id ) {

	$mime = get_post_mime_type( $attachment_id );

	if ( $mime == 'image/svg+xml' ) {

		$svg_path = get_attached_file( $attachment_id );
		$upload_dir = wp_upload_dir();
		// get the path relative to /uploads/ - found no better way:
		$relative_path = str_replace($upload_dir['basedir'], '', $svg_path);
		$filename = basename( $svg_path );

		$dimensions = bodhi_svgs_get_dimensions( $svg_path );

		$metadata = array(
			'width'		=> intval($dimensions->width),
			'height'	=> intval($dimensions->height),
			'file'		=> $relative_path
		);

		// Might come in handy to create the sizes array too - But it's not needed for this workaround! Always links to original svg-file => Hey, it's a vector graphic! ;)
		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => false );
			if ( isset( $_wp_additional_image_sizes[$s]['width'] ) )
				$sizes[$s]['width'] = intval( $_wp_additional_image_sizes[$s]['width'] ); // For theme-added sizes
			else
				$sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['height'] ) )
				$sizes[$s]['height'] = intval( $_wp_additional_image_sizes[$s]['height'] ); // For theme-added sizes
			else
				$sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
			if ( isset( $_wp_additional_image_sizes[$s]['crop'] ) )
				$sizes[$s]['crop'] = intval( $_wp_additional_image_sizes[$s]['crop'] ); // For theme-added sizes
			else
				$sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options

			$sizes[$s]['file'] =  $filename;
			$sizes[$s]['mime-type'] =  'image/svg+xml';
		}
		$metadata['sizes'] = $sizes;
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'bodhi_svgs_generate_svg_attachment_metadata', 10, 3 );

// Fix image widget PHP warnings
function bodhi_svgs_get_attachment_metadata( $data ) {

	$res = $data;

	if ( !isset( $data['width'] ) || !isset( $data['height'] ) ) {
		$res = false;
	}

	return $res;

}
// add_filter( 'wp_get_attachment_metadata' , 'bodhi_svgs_get_attachment_metadata' );
// Commented this out 20200307 because it was stripping metadata from other attachments as well. Need to make this target only SVG attachments.