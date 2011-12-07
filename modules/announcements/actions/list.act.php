<?

//--------------------------------------------------------------------------------------------------
//*	list all announcements
//--------------------------------------------------------------------------------------------------
//reqopt: sc - UID of a Schools_School object to filter announcements to [string]

	//----------------------------------------------------------------------------------------------
	//	check user permissions and any reference
	//----------------------------------------------------------------------------------------------
	if (false == $user->authHas('announcements', 'announcements_announcement', 'show')) {
		$page->do403();
	}
	
	$school = $user->school;
	if (true == array_key_exists('school', $req->args)) { $school = $req->args['sc']; }

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------

	$page->load('modules/announcements/actions/list.page.php');
	$page->blockArgs['schoolUID'] = $school;
	$page->render();

?>