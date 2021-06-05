<?php
/**
 * Twitter card meta tags
 *
 * @link https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup.html
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Classes\Front\Meta as Meta;

?>
<!-- Twitter Card meta -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:domain" content="<?php echo esc_attr( esc_url( home_url() ) ); ?>" />
<meta name="twitter:site" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
<meta name="twitter:url" content="<?php esc_attr( esc_url( Meta\data()->url() ) ); ?>" />
<meta name="twitter:title" content="<?php esc_attr( Meta\data()->title() ); ?>" />
<meta name="twitter:description" content="404 <?php esc_attr( Meta\data()->description() ); ?>" />
<meta name="twitter:image:src" content="<?php esc_attr( Meta\data()->image() ); ?>" />
<?php echo "\r"; ?>
