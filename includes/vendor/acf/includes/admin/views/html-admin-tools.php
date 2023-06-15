<?php

/**
*  html-admin-tools
*
*  View to output admin tools for both archive and single
*
*  @date	20/10/17
*  @since	5.6.3
*
*  @param	string $screen_id The screen ID used to display metaboxes
*  @param	string $active The active Tool
*  @return	n/a
*/

$class = $active ? 'single' : 'grid';

?>
<div class="wrap" id="acf-admin-tools">

	<h1><?php _e( 'Content Tools', 'acf' ); ?> <?php if (  $active ) : ?><a class="page-title-action" href="<?php echo acf_get_admin_tools_url(); ?>"><?php _e( 'Back to Tools', 'acf' ); ?></a><?php else : ?><a class="page-title-action" href="<?php echo admin_url( 'edit.php?post_type=acf-field-group' ); ?>"><?php _e( 'Field Groups', 'acf' ); ?></a><?php endif; ?></h1>

	<p class="description"><?php _e( 'Import and export various content types.', 'acf' ); ?></p>

	<div class="acf-meta-box-wrap -<?php echo $class; ?>">
		<?php do_meta_boxes( $screen_id, 'normal', '' ); ?>
	</div>
</div>
