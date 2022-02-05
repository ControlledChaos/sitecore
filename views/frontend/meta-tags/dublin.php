<?php
/**
 * Dublin Core meta tags
 *
 * @link http://dublincore.org/documents/dcmi-terms/
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Front
 * @since      1.0.0
 */

// Alias namespaces.
use SiteCore\Meta_Tags as Meta;

?>
<!-- Dublin Core meta tags -->
<meta name="DC.Title" content="<?php echo esc_attr( Meta\title() ); ?>" />
<meta name="DC.Description" content="<?php echo esc_attr( Meta\description() ); ?>" />
<meta name="DC.Format" content="text/html" />
<meta name="DC.Identifier" content="<?php echo esc_attr( esc_url( Meta\url() ) ); ?>" />
<meta name="DC.Source" content="<?php echo esc_attr( esc_url( site_url() ) ); ?>" />
<meta name="DC.Relation" content="<?php echo esc_attr( esc_url( site_url() ) ); ?>" scheme="IsPartOf" />
<meta name="DC.Creator" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
<meta name="DC.Subject" content="<?php echo esc_attr( Meta\description() ); ?>" />
<meta name="DC.Contributor" content="<?php echo esc_attr( Meta\author() ); ?>" />
<?php if ( is_singular() ) : ?>
<meta name="DC.Date" content="<?php echo esc_attr( get_the_date() ); ?>" />
<?php endif; ?>
<meta name="DC.Publisher" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
<meta name="DC.Rights" content="<?php echo esc_attr( Meta\copyright() ); ?>" />
<meta name="DC.Language" content="<?php echo esc_attr( get_locale() ); ?>" />
<?php echo "\r"; ?>
