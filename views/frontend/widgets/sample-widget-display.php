<?php
/**
 * Sample widget display
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front, Widgets
 * @since      1.0.0
 */

$title   = $this->front_title( $instance );
$options = $this->options();

if ( ! empty( $title ) ) {
	$title = $args['before_title'] . $title . $args['after_title'];
}

?>
<?php echo $args['before_widget']; ?>

	<?php echo $title; ?>

	<div class="<?php echo $this->type_class() . '-inner' ?>">
		<?php echo wpautop( $instance['content'] ); ?>
	</div>

<?php echo $args['after_widget']; ?>
