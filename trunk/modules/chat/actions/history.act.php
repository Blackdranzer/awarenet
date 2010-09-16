<?

//--------------------------------------------------------------------------------------------------
//*	view chat history (ones own, admins can view anyones history)
//--------------------------------------------------------------------------------------------------

	$userUID = $user->UID;
	$pageNo = '1';

	//----------------------------------------------------------------------------------------------
	//	check arguments and permissions
	//----------------------------------------------------------------------------------------------
	if (true == array_key_exists('page', $req->args)) { $pageNo = $req->args['page']; }

	if ( ($rquest['ref'] != '')
		AND (true == $db->objectExists('Users_User', $req->ref))
		AND (true == $user->authHas('chat', 'Chat_Discussion', 'viewhistory') == true) ) 
		{ $userUID = $req->ref; }

	//TODO: permissions check here

	//----------------------------------------------------------------------------------------------
	//	render the page
	//----------------------------------------------------------------------------------------------
	$page->load('modules/chat/actions/history.page.php');
	$page->blockArgs['userUID'] = $userUID;
	$page->render();

?>
