<?

//--------------------------------------------------------------------------------------------------
//*	list all galleries in root (NOT YET USED IN AWARENET)
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	// check permissions and reference
	//----------------------------------------------------------------------------------------------
	//if (false == $user->authHas('files', 'files_folder', 'show', 'TODO:UIDHERE'))
	//	{ $page->do403(); }

	if ('' == $req->ref) { $req->ref = $user->alias; }
	$UID = $aliases->findRedirect('users_user');			//TODO: will this work on this module?
	
	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/folders/actions/tree.page.php');		
	$page->blockArgs['userUID'] = $UID;								
	$page->render();													

?>