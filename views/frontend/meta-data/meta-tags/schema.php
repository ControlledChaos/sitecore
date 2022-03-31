<?php
/**
 * Schema meta tags
 *
 * @link https://schema.org/
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Meta_Tags as Meta;

?>
<!-- Schema meta tags -->
<meta itemprop="url" content="<?php echo esc_attr( esc_url( Meta\url() ) ); ?>" />
<meta itemprop="name" content="<?php echo esc_attr( Meta\title() ); ?>" />
<meta itemprop="description" content="<?php echo esc_attr( Meta\description() ); ?>" />
<meta itemprop="author" content="<?php echo esc_attr( Meta\author() ); ?>" />
<meta itemprop="datePublished" content="<?php echo esc_attr( Meta\published() ); ?>" />
<meta itemprop="dateModified" content="<?php echo esc_attr( Meta\modified() ); ?>" />
<meta itemprop="image" content="<?php echo esc_attr( Meta\image() ); ?>" />
<?php echo "\r"; ?>
