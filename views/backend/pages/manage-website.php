<?php
/**
 * Output of the Manage Website page
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Admin
 * @since      1.0.0
 */

namespace SiteCore\Views\Admin;
use SiteCore\Classes\Admin as Admin;

// Instance of the Manage_Website_Page class.
$page = new Admin\Manage_Website_Page;

// Restrict direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>
<div class="wrap manage-website">

	<?php
	printf(
		'<h1>%s</h1>',
		__( $page->heading(), SCP_DOMAIN )
	);

	printf(
		'<p class="description">%s</p>',
		__( $page->description(), SCP_DOMAIN )
	);
	?>

	<hr />

	<!-- Further development of this page is forthcoming. -->

</div>
