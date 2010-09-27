<?

//--------------------------------------------------------------------------------------------------
//*	edit a wiki article
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	check permissions and reference
	//----------------------------------------------------------------------------------------------
	if ('' == $req->ref) { $page->do404(); }				// check for ref	
	$UID = $aliases->findRedirect('Wiki_Article');			// check correct ref
	if (false == $user->authHas('wiki', 'Wiki_Article', 'edit', $UID)) { $page->do403(); }
	
	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/wiki/actions/edit.page.php');
	$page->blockArgs['UID'] = $UID;
	$page->blockArgs['raUID'] = $req->ref;
	$page->render();

?>