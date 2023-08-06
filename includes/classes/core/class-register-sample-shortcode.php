<?php
/**
 * Register sample shortcode
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Core
 * @since      1.0.0
 */

namespace SiteCore\Classes\Core;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Register_Sample_Shortcode extends Register_Shortcode {

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {

		$tag  = 'sample';
		$atts = [
			'wrap_text'  => 'no',
			'text_class' => '',
			'text_color' => 'inherit'
		];

		parent :: __construct(
			$tag,
			$atts
		);
	}

	/**
	 * Shortcode output
	 *
	 * @since  1.0.0
	 * @access public
	 * @return string Returns markup and the shortcode content.
	 */
	public function output( $atts, $content = null ) {

		/**
		 * Combines user attributes with attributes array
		 * and fill in defaults when needed.
		 */
		$code_atts = shortcode_atts(
			$this->code_atts,
			$atts,
			$this->code_tag
		);

		/**
		 * Sample paragraph class
		 *
		 * There is no default class in the shortcode
		 * attributes array, just an empty string.
		 * This demonstrates a default that is to be
		 * used always and possibly supplemented by a
		 * shortcode attribute.
		 */
		if ( ! empty( $code_atts['text_class'] ) ) {
			$class = 'sample-shortcode ' . $code_atts['text_class'];
		} else {
			$class = 'sample-shortcode';
		}

		// Add a wrapping element if `wrap_text="yes"`.
		if ( 'yes' === $code_atts['wrap_text'] ) {
			$html  = '<div class="sample-shortcode-wrap">';
			$html .= sprintf(
				'<p class="%s" style="color: %s">%s</p>',
				esc_attr( $class ),
				esc_attr( $code_atts['text_color'] ),
				$content
			);
			$html .= '</div';

		// Default paragraph element, no wrapper.
		} else {
			$html = sprintf(
				'<p class="%s" style="color: %s">%s</p>',
				esc_attr( $class ),
				esc_attr( $code_atts['text_color'] ),
				$content
			);
		}

		// Return, never echo.
		return $html;
	}
}
