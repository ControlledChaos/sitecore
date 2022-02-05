<?php
/**
 * Open Graph meta tags
 *
 * @link http://ogp.me/
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Meta_Tags as Meta;

?>
<!-- Open Graph meta tags -->
<meta property="og:url" content="<?php echo esc_attr( esc_url( Meta\url() ) ); ?>" />
<meta property="og:type" content="website" />
<meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>" />
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
<meta property="og:title" content="<?php echo esc_attr( Meta\title() ); ?>" />
<meta property="og:description" content="<?php echo esc_attr( Meta\description() ); ?>" />
<meta property="og:image" content="<?php echo esc_attr( Meta\image() ); ?>" />
<?php echo "\r"; ?>
