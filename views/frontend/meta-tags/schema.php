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
use SiteCore\Classes\Front\Meta as Meta;

?>
<!-- Schema meta tags -->
<meta itemprop="url" content="<?php echo esc_attr( esc_url( Meta\data()->url() ) ); ?>" />
<meta itemprop="name" content="<?php echo esc_attr( Meta\data()->title() ); ?>" />
<meta itemprop="description" content="<?php echo esc_attr( Meta\data()->description() ); ?>" />
<meta itemprop="author" content="<?php echo esc_attr( Meta\data()->author() ); ?>" />
<meta itemprop="datePublished" content="<?php echo esc_attr( Meta\data()->published() ); ?>" />
<meta itemprop="dateModified" content="<?php echo esc_attr( Meta\data()->modified() ); ?>" />
<meta itemprop="image" content="<?php echo esc_attr( Meta\data()->image() ); ?>" />
<?php echo "\r"; ?>
