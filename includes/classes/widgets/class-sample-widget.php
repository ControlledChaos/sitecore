<?php
/**
 * Sample widget type
 *
 * @package    Site_Core
 * @subpackage Classes
 * @category   Widgets
 * @since      1.0.0
 */

namespace SiteCore\Classes\Widgets;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Sample_Widget extends Add_Widget {

	/**
	 * Widget base ID
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $type_base = 'sample-widget';

	/**
	 * Name for this widget type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $type_name = 'Sample Widget';

	/**
	 * Description for this widget type
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string Default is empty.
	 */
	protected $type_desc = 'This is a sample widget. Duplicate its class file, rename it, and start developing your own widget.';

	/**
	 * Constructor method
	 *
	 * @since  1.0.0
	 * @access public
	 * @return self
	 */
	public function __construct() {
		parent :: __construct();
	}

	/**
	 * Widget control options
	 *
	 * This sample widget contains a textarea element
	 * so the expanded height and width are greater
	 * than the default to give room to type.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return array Returns an array of control options.
	 */
	protected function controls() {

		// Control options array.
		$options = [
			'width'  => 400,
			'height' => 350
		];
		return $options;
	}

	/**
	 * Update the widget form
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $new_instance New settings for this instance as input by the user via
	 *                             WP_Widget::form().
	 * @param  array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance          = $old_instance;
		$new_instance      = wp_parse_args(
			(array) $new_instance,
			[
				'title'   => '',
				'content' => ''
			]
		);
		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		$instance['content'] = wp_kses_post( $new_instance['content'] );

		return $instance;
	}
}
