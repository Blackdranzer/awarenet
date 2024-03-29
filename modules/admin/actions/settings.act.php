<?

//--------------------------------------------------------------------------------------------------
//*	action for viewing/editing module settings
//--------------------------------------------------------------------------------------------------
//role: admin - only administrators may do this

	//----------------------------------------------------------------------------------------------
	//	check user role
	//----------------------------------------------------------------------------------------------	
	if ($kapenta->user->role != admin) { $kapenta->page->do403('you are not authorized to edit module settings'); }

	//----------------------------------------------------------------------------------------------
	//	check that the module is known to the system (protect against directory traversal, etc)
	//----------------------------------------------------------------------------------------------
	if (false == $kapenta->moduleExists($kapenta->request->ref)) {
		$kapenta->session->msg("Invalid module name.", 'bad');
		$kapenta->page->do302('admin/listmodules/');
	}

	//----------------------------------------------------------------------------------------------
	//	show the page (or bounce to /)
	//----------------------------------------------------------------------------------------------
	$kapenta->page->load('modules/admin/actions/settings.page.php');
	$kapenta->page->blockArgs['showModule'] = $kapenta->request->ref;
	$kapenta->page->render();

?>
