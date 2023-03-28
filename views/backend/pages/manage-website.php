<?php
/**
 * Output of the Manage Website page
 *
 * @package    Site_Core
 * @subpackage Views
 * @category   Admin
 * @since      1.0.0
 */

use SiteCore\Classes\Admin as Admin;

// Instance of the Manage_Website_Page class.
$page = new Admin\Manage_Website_Page;

?>
<div class="wrap manage-website">

	<?php
	printf(
		'<h1>%s</h1>',
		__( $page->heading(), 'sitecore' )
	);

	echo $page->description();
	?>

	<!-- Further development of this page is forthcoming. -->

</div>
