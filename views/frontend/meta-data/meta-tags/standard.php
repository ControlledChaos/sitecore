<?php
/**
 * Standard meta tags
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Meta_Tags as Meta;

?>
<?php echo "\r"; ?>
<!-- Standard meta tags -->
<meta name="title" content="<?php echo esc_attr( Meta\title() ); ?>" />
<meta name="description" content="<?php echo esc_attr( Meta\description() ); ?>" />
<meta name="author" content="<?php echo esc_attr( Meta\author() ); ?>" />
<meta name="copyright" content="<?php echo esc_attr( Meta\copyright() ); ?>" />
<meta name="language" content="<?php echo esc_attr( get_locale() ); ?>" />
<?php echo "\r"; ?>
