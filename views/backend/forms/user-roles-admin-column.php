<?php
/**
 * User roles admin column
 *
 * Outputs a list of roles belonging to the current user.
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Forms
 * @since      1.0.0
 *
 * @var $roles array All applicable roles in name => label pairs.
 */
?>
<div class="md-multiple-roles">
	<?php if ( ! empty( $roles ) ) :
		foreach( $roles as $name => $label ) :
			$roles[$name] = '<a href="users.php?role=' . esc_attr( $name ) . '">' . esc_html( translate_user_role( $label ) ) . '</a>';
		endforeach;
		echo implode( ', ', $roles );
	else : ?>
		<span class="md-multiple-roles-no-role"><?php _e( 'None', 'sitecore' ); ?></span>
	<?php endif; ?>
</div>
