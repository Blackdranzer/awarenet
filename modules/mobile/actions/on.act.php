<?

//--------------------------------------------------------------------------------------------------
//*	turn on mobile browsing for this session
//--------------------------------------------------------------------------------------------------

	//----------------------------------------------------------------------------------------------
	//	turn on mobile features
	//----------------------------------------------------------------------------------------------

	$session->set('mobile', 'true');
	$session->set('contentWidth', '320');

	$session->msg("Session now in mobile compatability mode.", 'ok');

	//----------------------------------------------------------------------------------------------
	//	redirect to front page or user notifications
	//----------------------------------------------------------------------------------------------

	if (('public' == $user->role) || ('banned' == $user->role)) { $page->do302('users/login/'); }
	$page->do302('notifications/');

?>