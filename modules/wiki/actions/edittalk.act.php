<?

//--------------------------------------------------------------------------------------------------
//*	edit a wiki article
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	// check permissions and reference
	//----------------------------------------------------------------------------------------------

	if ('' == $req->ref) { $page->do404(); }				// check for ref
	$UID = $aliases->findRedirect('Wiki_Article');			// check correct ref

	if (false == $user->authHas('wiki', 'Wiki_Article', 'edit', $UID) == false) { $page->do403(); }

	//----------------------------------------------------------------------------------------------
	// check permissions and render the page
	//----------------------------------------------------------------------------------------------	
	$page->load('modules/wiki/actions/edittalk.page.php');
	$page->blockArgs['UID'] = $UID;
	$page->blockArgs['raUID'] = $req->ref;
	$page->render();

?>
