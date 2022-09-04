<?php
/**
 * Article structured data
 *
 * A general use example.
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
<!-- Article structured data -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Article",
	"url": "<?php echo esc_attr( get_bloginfo( 'url' ) ); ?>",
	"mainEntityOfPage": {
		"@type": "WebPage",
		"@id": "<?php echo esc_attr( esc_url( Meta\url() ) ); ?>"
	},
	"headline": "<?php echo esc_attr( Meta\title() ); ?>",
	"alternativeHeadline": "<?php echo esc_attr( Meta\subtitle() ); ?>",
	"description": "<?php echo esc_attr( Meta\description() ); ?>",
	"image": "<?php echo esc_attr( Meta\image() ); ?>",
	"genre": "<?php echo esc_attr( Meta\post_genre() ); ?>",
	"keywords": "<?php echo esc_attr( Meta\keywords() ); ?>",
	"publisher": {
		"@type": "Organization",
		"name": "<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>",
		"logo": {
			"@type": "ImageObject",
			"url": "<?php echo esc_attr( esc_url( Meta\site_logo() ) ); ?>"
		}
	},
	"author": {
		"@type": "Person",
		"name": "<?php echo esc_attr( Meta\author() ); ?>",
		"url": ""

	},
	"editor": "<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>",
	"datePublished": "<?php echo esc_attr( Meta\published() ); ?>",
	"dateModified": "<?php echo esc_attr( Meta\modified() ); ?>",
	"wordcount": "<?php echo esc_attr( Meta\word_count() ); ?>"
}
</script>
