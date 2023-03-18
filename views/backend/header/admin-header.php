<?php
/**
 * Form fields for admin settings dashboard tab
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin_Header;

use SiteCore\Tags as Tags;

$site_description = get_bloginfo( 'description', 'display' );

?>
<header id="masthead" class="site-header admin-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">

	<div class="site-branding-wrap">
		<div class="site-branding">

			<?php echo Tags\site_logo(); ?>

			<div class="site-title-description">

				<p class="site-title"><a href="<?php echo esc_attr( esc_url( get_bloginfo( 'url' ) ) ); ?>" target="_blank" rel="noindex nofollow home"><?php bloginfo( 'name' ); ?></a></p>
				<?php

				if ( $site_description ) :
					?>
					<p class="site-description"><?php echo $site_description; ?></p>
				<?php endif; ?>

			</div>
		</div>
		<?php if ( has_nav_menu( 'admin_header' ) ) : ?>
		<nav id="site-navigation" class="main-navigation" role="directory" itemscope itemtype="http://schema.org/SiteNavigationElement">
			<?php
			wp_nav_menu( [
				'theme_location' => 'admin_header',
				'container'      => false,
				'menu_id'        => 'admin-header-menu',
				'fallback_cb'    => false
			] );
			?>
		</nav>
		<?php endif; ?>
	</div>
</header>
