<?

//--------------------------------------------------------------------------------------------------
//	page to display a single file
//--------------------------------------------------------------------------------------------------

	if ('' == $req->ref) { $page->do404(); }
	
	$page->load('modules/files/actions/show.page.php');
	$page->blockArgs['raUID'] = $req->ref;
	$page->render();

?>
