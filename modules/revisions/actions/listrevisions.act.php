<?

//--------------------------------------------------------------------------------------------------
//*	list all revisions on everything in the database
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if ('admin' != $user->role) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	//	render page
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/revisions/actions/revisions.page.php');
	$kapenta->page->render();

?>
